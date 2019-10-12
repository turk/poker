<?php
namespace App\Controllers;

use App\Views\View;

class IndexController extends Controller
{
    public function index()
    {
        return View::render('index');
    }
}
