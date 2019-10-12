<?php
namespace App\Controllers;

use App\Model\User;
use App\Views\View;

class AuthController
{
    public function getLogin()
    {
        if(isLogin() === true){
            redirect('/');
        }

        return View::render('login');
    }

    public function postLogin()
    {
        if (empty($_POST['password']) || empty($_POST['username'])) {
            return View::render('login', ['errorMessage' => 'Username and password are required.']);
        }

        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $user = User::where('username', $username)->where('password', $password)->first();

        if (!$user) {
            return View::render('login', ['errorMessage' => 'Wrong username or password.']);

        }

        $_SESSION['auth'] = true;
        redirect('/');
    }

    public function logout()
    {
        unset($_SESSION['auth']);
        redirect('/login');
    }
}
