<?php

namespace App\Models;

use CodeIgniter\Model;

class RuanganModel extends Model
{
    protected $table = 'ruangan';
    protected $allowedFields = ['id_ruangan', 'nomor_ruangan', 'status'];

    public function getRuangan()
    {
        return $this->findAll();
    }

    public function getKetersediaanRuangan($layananId)
    {
        return $this->select('ruangan.id_ruangan, ruangan.nomor_ruangan')
            ->join('paket', 'ruangan.id_ruangan = paket.id_ruangan')
            ->where('paket.id_layanan', $layananId)
            ->where('ruangan.status', 'kosong')
            ->findAll();
    }

    public function updateStatusRuangan($idRuangan, $status)
    {
        $this->where('id_ruangan', $idRuangan)
            ->set('status', $status)
            ->update();
    }
}
