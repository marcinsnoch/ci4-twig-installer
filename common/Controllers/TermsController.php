<?php

namespace App\Controllers;

class TermsController extends BaseController
{
    public function index(): void
    {
        $this->twig->display('terms/index');
    }
}
