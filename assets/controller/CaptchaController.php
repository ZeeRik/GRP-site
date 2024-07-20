<?php

class CaptchaController extends BaseObject {

    private $_type = array('/', '*', '+', '');

    public function get() {
        $char1 = rand(2, 10);
        $char2 = rand(2, 5);
        $array = array('+', '*');
        $rand = $array[array_rand($array)];
        $img = imagecreatetruecolor(85, 20);
        $black = imagecolorclosest($img, 0, 0, 0);
        imagecolortransparent($img, $black);
        $color_text = imagecolorallocate($img, 8, 20, 103);
        imagefill($img, 0, 0, 0);
        $arial = "assets/view/fonts/arial.ttf";
        imagettftext($img, 18, 0, 5, 19, $color_text, $arial, "$char1 $rand $char2 =");
        $_SESSION['captcha'] = ($rand == '+') ? $char1 + $char2 : $char1 * $char2;
        header("Contenttype: image/png");
        imagepng($img);
        imagedestroy($img);
    }

    public function check($int) {
        if ($int == $_SESSION['captcha']) {
            unset($_SESSION['captcha']);
            return TRUE;
        } else {
            unset($_SESSION['captcha']);
            return FALSE;
		}
    }
}