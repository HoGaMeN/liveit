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
}
