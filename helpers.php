<?php

function redirect($url)
{
    header('Location: ' . $url);
    die();
}

function isLogin(){
    return $_SESSION['auth'];
}

