<?php

namespace App\Controllers;

use Myth\Auth\Models\GroupModel;

class Home extends BaseController
{

    protected $groupModel;

    public function __construct()
    {
        $this->groupModel = new GroupModel();
    }

    public function index(): string
    {
        return view('welcome_message');
    }

    public function login()
    {
        $email = !empty(user()->email) ? user()->email : null; // Mendapatkan email pengguna yang sedang login

        $data['config'] = config('Auth');

        return view('auth/login', $data);
    }

    public function register()
    {
        return view('auth/register');
    }

    public function user()
    {
        $email = !empty(user()->email) ? user()->email : null; // Mendapatkan email pengguna yang sedang login

        $data = [
            'title' => "Profile | WarungPedia",
            'profil' => $email
        ];

        return view('auth/user/index', $data);
    }
}
