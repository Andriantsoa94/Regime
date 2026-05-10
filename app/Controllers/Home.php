<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function admin(): string
    {
        $user = session()->get('user');
        if (!$user || ($user['role'] != 1 && $user['role'] != 'admin')) {
            return redirect()->to('/home');
        }
        return view('admin/dashboard');
    }
}
