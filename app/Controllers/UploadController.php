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
            $file = file($_FILES['file']['tmp_name'], FILE_SKIP_EMPTY_LINES);
            foreach ($file as $line_num => $line) {
                $line = trim(preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', '', $line));
                if($line !== ''){
                    $lines[] = ['cards' => trim($line)];

                }
            }

            $insert = Game::insert($lines);
            return View::render('index', ['successMessage' => 'The file is uploaded']);

        } catch (\Exception $e) {

        }

    }
}
