<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';
    protected $allowedFields = ['id_transaksi', 'id_user', 'id_paket', 'tanggal_booking', 'tanggal_checkout', 'total', 'status'];
    protected $primaryKey = 'id_transaksi'; // atau nama kolom yang benar untuk primary key tabel transaksi Anda


    public function getTransaksiByUserId($userId)
    {
        return $this->select('transaksi.id_transaksi, layanan.nama_layanan, ruangan.nomor_ruangan, transaksi.tanggal_booking, transaksi.tanggal_checkout, transaksi.total, transaksi.status')
            ->join('paket', 'transaksi.id_paket = paket.id_paket')
            ->join('layanan', 'paket.id_layanan = layanan.id_layanan')
            ->join('ruangan', 'paket.id_ruangan = ruangan.id_ruangan')
            ->where('transaksi.id_user', $userId)
            ->findAll();
    }

    public function getDaftarTransaksi()
    {
        return $this->select('users.email, layanan.nama_layanan, transaksi.total')
            ->join('paket', 'paket.id_paket = transaksi.id_transaksi')
            ->join('layanan', 'layanan.id_layanan = paket.id_layanan')
            ->join('users', 'users.id = transaksi.id_user')
            ->where('transaksi.status', 'Pembayaran Berhasil')
            ->orderBy('transaksi.created_at')
            ->findAll();
    }

    public function getDetailTransaksiById($idTransaksi)
    {
        return $this->select('layanan.nama_layanan, layanan.kelas_paket, ruangan.nomor_ruangan, paket.deskripsi, transaksi.tanggal_booking, transaksi.tanggal_checkout, transaksi.total, transaksi.status, users.username')
            ->join('paket', 'transaksi.id_paket = paket.id_paket')
            ->join('layanan', 'paket.id_layanan = layanan.id_layanan')
            ->join('ruangan', 'paket.id_ruangan = ruangan.id_ruangan')
            ->join('users', 'users.id = transaksi.id_user')
            ->where('transaksi.id_transaksi', $idTransaksi)
            ->first();
    }

    public function updateStatusTransaksi($idTransaksi, $newStatus)
    {
        $data = ['status' => $newStatus];
        return $this->update($idTransaksi, $data);
    }
}
