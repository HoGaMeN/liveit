<?php

namespace App\Controllers;

use Myth\Auth\Models\GroupModel;
use App\Models\RuanganModel;
use App\Models\TransaksiModel;

class Admin extends BaseController
{
    protected $db, $builder;
    protected $ruanganModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('users');
        $this->ruanganModel = new RuanganModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $this->builder->select('users.id as userid, username, email, nama_lengkap, name');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id ');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id ');
        $this->builder->where('auth_groups.name', 'user');
        $query = $this->builder->get();

        $data['users'] = $query->getResult();

        return view('admin/index', $data);
    }

    public function detail_user($id)
    {
        $this->builder->select('users.id as userid, username, email, nama_lengkap, nomor_hp, created_at, name');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id ');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id ');
        $this->builder->where('users.id', $id);
        $query = $this->builder->get();

        $data['user'] = $query->getRow(); // Ubah 'users' menjadi 'user' untuk mengikuti konvensi penamaan singular untuk satu objek

        if (empty($data['user'])) {
            return redirect()->to('/admin');
        }

        return view('admin/detail_user', $data);
    }

    public function daftar_ruangan()
    {
        $data = [
            'title' => "LIVEIT-Daftar Ruangan",
            'ruangans' => $this->ruanganModel->getRuangan(),
        ];

        return view('admin/ruangan', $data);
    }

    public function daftar_transaksi()
    {
        $data = [
            'title' => "LIVEIT-Daftar Transaksi",
            'transaksis' => $this->transaksiModel->getDaftarTransaksi(),
        ];

        return view('admin/daftar_transaksi', $data);
    }
}
