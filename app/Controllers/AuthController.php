<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->session->has('connecte')) {
            return redirect()->to('/accueil');
        }
        return view('login');
    }

    public function connecter()
    {
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->to('/')->with('error', 'Veuillez remplir tous les champs.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->to('/')->with('error', 'Identifiant ou mot de passe incorrect.');
        }

        $this->session->set([
            'connecte' => true,
            'user_id'  => $user['id'],
            'user_nom' => $user['username'],
            'user_role'=> $user['role'],
        ]);

        return redirect()->to('/accueil');
    }

    public function deconnecter()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }
}
