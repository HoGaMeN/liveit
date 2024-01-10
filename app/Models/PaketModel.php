<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    protected $table = 'paket';
    protected $allowedFields = ['id_paket', 'id_ruangan', 'id_layanan', 'deskripsii'];

    public function getIdPaket($idLayanan, $idRuangan)
    {
        $paket = $this->select('id_paket')
            ->where('id_layanan', $idLayanan)
            ->where('id_ruangan', $idRuangan)
            ->first();

        return $paket['id_paket']; // Pastikan ini hanya mengembalikan satu nilai
    }
}
