<?php

namespace App\Controllers;

use Myth\Auth\Models\GroupModel;
use App\Models\TransaksiModel;


class User extends BaseController
{
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
}
