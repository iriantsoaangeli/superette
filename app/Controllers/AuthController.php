<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function connecter()
    {
        $this->session->set('connecte', true);

        return redirect()->to('/accueil');
    }
}
