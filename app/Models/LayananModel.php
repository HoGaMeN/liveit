<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananModel extends Model
{
    protected $table = 'layanan';
    protected $allowedFields = ['id_layanan', 'nama_layanan'];

    public function getLayanan()
    {
        return $this->findAll();
    }

    public function getNominalPerJam($idLayanan)
    {
        return $this->select('nominal_perjam')
            ->where('id_layanan', $idLayanan)
            ->first();
    }
}
