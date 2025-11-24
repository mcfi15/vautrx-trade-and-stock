<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generate()
    {
        $code = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);

        Session::put('captcha_code', $code);

        // Create Image
        $image = imagecreatetruecolor(120, 36);
        $bg = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);

        imagefilledrectangle($image, 0, 0, 120, 36, $bg);

        imagestring($image, 5, 22, 10, $code, $textColor);

        // Anti cache
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
        exit();
    }
}
