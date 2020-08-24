<?php
    session_start();
    # 随机数 防止难识别 去除 Oo0LI1
    $random = 'ABCDEFGHKMNPRSTUVWXYZabcdefghkmnprstuvwxyz23456789';
    # 验证码
    $code = '';
    $codelen = 4;
    $width = 130;
    $height = 45;
    $font = './static/font/Roboto-Medium.ttf';;
    $fontsize = 20;
    $img = imagecreatetruecolor($width, $height);
    $color = imagecolorallocate($img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
    imagefilledrectangle($img, 0, $height, $width, 0, $color);
    $_len = strlen($random) - 1;
    for ($i = 0; $i < $codelen; $i++) {
        $code .= $random[mt_rand(0, $_len)];
    }
    for ($i = 0; $i < 100; $i++) {
        $color = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
        imagestring($img, mt_rand(1, 5), mt_rand(0, $width), mt_rand(0, $height), '*', $color);
    }
    $_x = $width / $codelen;
    for ($i = 0; $i < $codelen; $i++) {
        $fontcolor = imagecolorallocate($img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
        imagettftext($img, $fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $height / 1.4, $fontcolor, $font, $code[$i]);
    }
    for ($i = 0; $i < 6; $i++) {
        $color = imagecolorallocate($img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
        imageline($img, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $color);
    }
    header('Content-type:image/png');
    imagepng($img);
    imagedestroy($img);
    session_start();
    $_SESSION['vCode'] = strtolower($code);
    die();
?>
