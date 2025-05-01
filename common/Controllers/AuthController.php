<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login(): void
    {
        $this->twig->display('auth/login');
    }

    public function forgotPassword(): void
    {
        $this->twig->display('auth/forgot_password');
    }

    public function resetPassword(): void
    {
        $this->twig->display('auth/reset_password', ['token' => 'test_token']);
    }

    public function register()
    {
        $this->twig->display('auth/register');
    }

    public function logout()
    {
        return redirect()->to('login');
    }
}
