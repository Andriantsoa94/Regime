<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\View;

class AuthController extends BaseController
{
    public function index()
    {
        return View('auth/login');
    }
    
}
