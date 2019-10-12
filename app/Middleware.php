<?php
namespace App;

class Middleware
{
    public function auth()
    {
        if($_SESSION['auth'] === null || $_SESSION['auth'] === false){
            redirect('/login');
        }
    }
}
