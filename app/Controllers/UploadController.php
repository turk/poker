<?php
namespace App\Controllers;

use App\Model\Game;
use App\Views\View;

class UploadController extends Controller
{
    public function index()
    {
        try {

            $fileExtension = end((explode('.', $_FILES['file']['name'])));
            if ($fileExtension !== 'txt') {
                var_dump('Only txt file');
            }

            $lines = [];
            $fn = fopen($_FILES['file']['tmp_name'], 'rb');
            while (!feof($fn)) {
                $result = fgets($fn);
                $lines[] = ['cards' => trim($result)];
            }
            fclose($fn);

            $insert = Game::insert($lines);
            return View::render('index', ['successMessage' => 'The file is uploaded']);

        } catch (\Exception $e) {

        }

    }
}
