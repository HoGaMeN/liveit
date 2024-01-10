<?php

namespace App\Controllers;

use Myth\Auth\Models\GroupModel;
use App\Models\TransaksiModel;
use App\Models\LayananModel;
use App\Models\RuanganModel;
use App\Models\PaketModel;



class User extends BaseController
{
    protected $ruanganModel;
    protected $layananModel;

    public function __construct()
    {
        $this->ruanganModel = new RuanganModel();
        $this->layananModel = new LayananModel();
    }

    public function index()
    {
        $email = !empty(user()->email) ? user()->email : null; // Mendapatkan email pengguna yang sedang login

        $data = [
            'title' => "Profile | WarungPedia",
            'profil' => $email
        ];

        return view('auth/User/index', $data);
    }

    public function rencana()
    {
        $userId = user_id(); // Dapatkan user ID dari pengguna yang sedang login
        $transaksiModel = new TransaksiModel();

        // Cek apakah user sudah login
        if (!$userId) {
            return redirect()->to('/login');
        }

        // Dapatkan transaksi berdasarkan user ID
        $transaksiUser = $transaksiModel->getTransaksiByUserId($userId);

        $data = [
            'title' => "LIVEIT-Rencana",
            'transaksi' => $transaksiUser, // Data transaksi yang akan ditampilkan di view
        ];

        return view('auth/User/rencana', $data);
    }

    public function sewa()
    {
        $data = [
            'title' => "LIVEIT-Sewa",
            'layanans' => $this->layananModel->getLayanan(),
        ];

        return view('auth/User/form_sewa', $data);
    }

    public function getKetersediaanRuangan($layananId)
    {
        $ruanganModel = new RuanganModel();
        $ketersediaanRuangan = $ruanganModel->getKetersediaanRuangan($layananId);

        return $this->response->setJSON($ketersediaanRuangan);
    }

    public function getNominalPerJam($idLayanan)
    {
        $layananModel = new LayananModel();
        $nominalPerJam = $layananModel->getNominalPerJam($idLayanan);

        return $this->response->setJSON($nominalPerJam);
    }

    public function simpanTransaksi()
    {
        $idLayanan = $this->request->getPost('id_layanan');
        $idRuangan = $this->request->getPost('id_ruangan');
        if (!$idRuangan) {
            return redirect()->back()->with('error', 'Ruangan harus dipilih.');
        }
        $tanggalBooking = $this->request->getPost('checkin');
        $tanggalCheckout = $this->request->getPost('checkout');
        $totalBiaya = $this->request->getPost('total'); // Atau hitung dari data lain yang diberikan

        // Dapatkan id_paket berdasarkan id_layanan dan id_ruangan
        $paketModel = new PaketModel();
        $idPaket = $paketModel->getIdPaket($idLayanan, $idRuangan);

        if (!$idPaket) {
            // Gagal mendapatkan id_paket, tampilkan error atau lakukan penanganan lain
        }

        // Ambil total dari form input
        $totalBiaya = $this->request->getPost('textarea'); // Atau nama field yang sesuai

        // Validasi dan parsing total biaya dari textarea
        $parsedTotal = $this->parseTotalBiaya($totalBiaya);
        if ($parsedTotal === null) {
            // Handle kasus di mana total biaya tidak valid
            return redirect()->back()->with('error', 'Total biaya tidak valid.');
        }

        // Persiapkan data untuk disimpan
        $dataTransaksi = [
            'id_user' => user_id(),
            'id_paket' => $idPaket,
            'id_ruangan' => $idRuangan,
            'tanggal_booking' => $tanggalBooking,
            'tanggal_checkout' => $tanggalCheckout,
            'total' => $parsedTotal,
            'status' => 'Menunggu Pembayaran'
        ];

        // Simpan transaksi
        $transaksiModel = new TransaksiModel();
        $transaksiModel->save($dataTransaksi);

        // Perbarui status ruangan
        $this->ruanganModel->updateStatusRuangan($idRuangan, 'dipesan');

        // Arahkan ke halaman konfirmasi atau tampilkan pesan sukses
        return redirect()->to('/user/rencana')->with('success', 'Transaksi berhasil disimpan');
    }

    protected function parseTotalBiaya($totalBiayaText)
    {
        // Parsing dan validasi logika untuk mendapatkan nilai numerik dari teks total biaya
        // Ini adalah contoh sederhana dan harus disesuaikan dengan format yang Anda gunakan
        $matches = [];
        if (preg_match('/TOTAL = Rp\.(\d+(\.\d{2})?)/', $totalBiayaText, $matches)) {
            return (float) str_replace('.', '', $matches[1]); // Menghapus titik jika format adalah ribuan
        }

        return null; // Gagal parsing, kembalikan null
    }

    public function detailTransaksi($idTransaksi)
    {
        $transaksiModel = new \App\Models\TransaksiModel();
        $dataTransaksi = $transaksiModel->getDetailTransaksiById($idTransaksi);

        if (!$dataTransaksi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Transaksi tidak ditemukan');
        }

        // Periksa struktur $dataTransaksi dengan var_dump() atau print_r() untuk memastikan apakah itu array atau objek
        // var_dump($dataTransaksi); exit;

        // Jika $dataTransaksi adalah array, gunakan sintaks array untuk mengakses data
        $checkInTime = strtotime($dataTransaksi['tanggal_booking']);
        $checkOutTime = strtotime($dataTransaksi['tanggal_checkout']);
        $durasiSewaJam = ($checkOutTime - $checkInTime) / 3600;

        // Periksa apakah waktu sekarang sudah sama atau lewat waktu check-in
        $waktuSekarang = time();
        $hitungMundurBerjalan = $waktuSekarang >= $checkInTime;

        $data = [
            'title' => 'Detail Transaksi',
            'transaksi' => $dataTransaksi,
            'durasiSewaJam' => $durasiSewaJam,
            'hitungMundurBerjalan' => $hitungMundurBerjalan,
            'waktuSekarang' => $waktuSekarang,
            'checkInTime' => $checkInTime,
            'checkOutTime' => $checkOutTime
        ];

        return view('auth/User/detail_transaksi', $data);
    }
}
