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
    protected $transaksiModel;

    public function __construct()
    {
        $this->ruanganModel = new RuanganModel();
        $this->layananModel = new LayananModel();
        $this->transaksiModel = new TransaksiModel();
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

        // Pastikan bahwa Anda memang mendapatkan data transaksi.
        $dataTransaksi = $transaksiModel->getDetailTransaksiById($idTransaksi);
        if (!$dataTransaksi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Transaksi tidak ditemukan');
        }

        // Set konfigurasi Midtrans.
        \Midtrans\Config::$serverKey = 'Mid-server-1BvPZNaT-5G94_yU4GcU-RBm';
        \Midtrans\Config::$isProduction = true;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        // Generate order ID menggunakan ID transaksi dan timestamp.
        $orderId = 'TRANS-' . $idTransaksi . '-' . time();

        // Buat parameter untuk request token snap Midtrans.
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => 1,
                // 'gross_amount' => $dataTransaksi['total'], // Pastikan 'total' ini sesuai dengan nama kolom di tabel transaksi Anda
            ],
            // Anda dapat menambahkan item lainnya jika diperlukan
        ];

        // Dapatkan snap token dari Midtrans.
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Menyimpan id transaksi ke dalam session
        session()->set('id_transaksi', $idTransaksi);

        // Update order_id di database sebelum mengirimkan ke view.
        // Cek dulu apakah kolom order_id sudah ada di tabel transaksi Anda.
        if (isset($dataTransaksi['order_id']) && $dataTransaksi['order_id'] != $orderId) {
            $updateData = ['order_id' => $orderId];
            $updateResult = $transaksiModel->update($idTransaksi, $updateData);

            // Periksa apakah ada perubahan yang dilakukan, jika tidak ada maka lempar exception.
            if ($updateResult === false) {
                throw new \CodeIgniter\Database\Exceptions\DataException('There is no data to update.');
            }
        }

        // Jika $dataTransaksi adalah array, gunakan sintaks array untuk mengakses data
        $checkInTime = strtotime($dataTransaksi['tanggal_booking']);
        $checkOutTime = strtotime($dataTransaksi['tanggal_checkout']);
        $durasiSewaJam = ($checkOutTime - $checkInTime) / 3600;

        // Periksa apakah waktu sekarang sudah sama atau lewat waktu check-in
        $waktuSekarang = time();
        $hitungMundurBerjalan = $waktuSekarang >= $checkInTime;

        // Siapkan data untuk dikirim ke view.
        $data = [
            'title' => 'Detail Transaksi',
            'transaksi' => $dataTransaksi,
            'snapToken' => $snapToken, // Kirim token snap ke view
            'durasiSewaJam' => $durasiSewaJam,
            'hitungMundurBerjalan' => $hitungMundurBerjalan,
            'waktuSekarang' => $waktuSekarang,
            'checkInTime' => $checkInTime,
            'checkOutTime' => $checkOutTime,
            'token' => $snapToken
            // Data lainnya yang dibutuhkan oleh view Anda
        ];

        return view('auth/User/detail_transaksi', $data);
    }

    // public function pembayaran($idTransaksi)
    // {
    //     // Ambil data transaksi dari database
    //     $transaksi = $this->transaksiModel->find($idTransaksi);

    //     // Periksa jika transaksi tidak ditemukan
    //     if (!$transaksi) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan');
    //     }

    //     // Konfigurasi Midtrans
    //     \Midtrans\Config::$serverKey = 'Mid-server-1BvPZNaT-5G94_yU4GcU-RBm';
    //     \Midtrans\Config::$isProduction = true; // true jika environment produksi
    //     \Midtrans\Config::$isSanitized = true;
    //     \Midtrans\Config::$is3ds = true;

    //     // Parameter yang diperlukan oleh Midtrans
    //     $params = [
    //         'transaction_details' => [
    //             'order_id' => $transaksi['order_id'], // order_id yang unik dari transaksi Anda
    //             'gross_amount' => $transaksi['total'], // total pembayaran
    //         ],
    //         // Anda bisa menambahkan parameter lainnya sesuai dokumentasi Midtrans
    //     ];

    //     // Mendapatkan snap token dari Midtrans
    //     $snapToken = \Midtrans\Snap::getSnapToken($params);

    //     // Passing snapToken ke view untuk digunakan oleh JavaScript Snap
    //     return view('path_to_your_payment_view', [
    //         'snap_token' => $snapToken,
    //         'transaksi' => $transaksi,
    //     ]);
    // }
    public function verifikasiPembayaran()
    {
        // Ambil order_id dari POST request yang dikirim oleh Midtrans notification
        $orderId = $this->request->getPost('order_id');

        // Verifikasi pembayaran ke Midtrans
        $status = $this->verifikasiPembayaranMidtrans($orderId);

        // Logika untuk memperbarui status pembayaran di database
        if ($status == 'success') {
            // Pembayaran berhasil
            $this->transaksiModel->updateStatusTransaksi($orderId, 'Pembayaran Ber
        hasil');
        } else {
            // Pembayaran gagal atau belum selesai
            $this->transaksiModel->updateStatusTransaksi($orderId, 'Pembayaran Gagal');
        }
    }

    private function verifikasiPembayaranMidtrans($orderId)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = 'Mid-server-1BvPZNaT-5G94_yU4GcU-RBm';
        \Midtrans\Config::$isProduction = true; // true jika environment produksi
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            // Melakukan request status transaksi ke Midtrans
            $response = \Midtrans\Transaction::status($orderId);

            // Pastikan bahwa response adalah array
            if (is_array($response)) {
                // Cek status transaksi
                if ($response['transaction_status'] == 'settlement' || $response['transaction_status'] == 'capture') {
                    // Transaksi berhasil
                    return 'success';
                } else {
                    // Transaksi gagal atau belum selesai
                    return 'failure';
                }
            } else {
                // Respon tidak valid
                return 'failure';
            }
        } catch (\Exception $e) {
            // Terjadi kesalahan saat memanggil API Midtrans
            error_log($e->getMessage());
            return 'failure';
        }
    }

    public function pembayaranBerhasil()
    {
        $idTransaksi = session()->get('id_transaksi');
        // Verifikasi pembayaran dan update status transaksi
        if ($idTransaksi) {
            $this->transaksiModel->updateStatusTransaksi($idTransaksi, 'Pembayaran Berhasil');
            session()->remove('id_transaksi');
        }

        // Redirect ke halaman konfirmasi dengan pesan sukses
        return redirect()->to('/user/rencana')->with('message', 'Pembayaran Berhasil');
    }
}
