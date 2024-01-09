<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';
    protected $allowedFields = ['id_user', 'id_paket', 'tanggal_booking', 'tanggal_checkout', 'total'];

    public function getTransaksiByUserId($userId)
    {
        return $this->select('layanan.nama_layanan, ruangan.nomor_ruangan, transaksi.tanggal_booking, transaksi.tanggal_checkout, transaksi.total')
            ->join('paket', 'transaksi.id_paket = paket.id_paket')
            ->join('layanan', 'paket.id_layanan = layanan.id_layanan')
            ->join('ruangan', 'paket.id_ruangan = ruangan.id_ruangan')
            ->where('transaksi.id_user', $userId)
            ->findAll();
    }
}
