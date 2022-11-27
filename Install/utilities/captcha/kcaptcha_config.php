<?php
/*
# KCAPTCHA configuration file

class KCaptchaConfig {
    public $alphabet = "0123456789abcdefghijklmnopqrstuvwxyz"; # do not change without changing font files!

# symbols used to draw CAPTCHA
//$allowed_symbols = "0123456789"; #digits
//$allowed_symbols = "23456789abcdegkmnpqsuvxyz"; #alphabet without similar symbols (o=0, 1=l, i=j, t=f)
    public $allowed_symbols = "23456789abcdegikpqsvxyz"; #alphabet without similar symbols (o=0, 1=l, i=j, t=f)

# folder with fonts
    public $fontsdir = __DIR__.'/fonts';

# CAPTCHA string length
    public $length = mt_rand(4,5); # random 5 or 6 or 7
//$length = 6;

# CAPTCHA image size (you do not need to change it, this parameters is optimal)
    public $width = 160;
    public $height = 80;

# symbol's vertical fluctuation amplitude
    public $fluctuation_amplitude = 8;

#noise
//$white_noise_density=0; // no white noise
    public $white_noise_density=1/6;
//$black_noise_density=0; // no black noise
    public $black_noise_density=1/30;

# increase safety by prevention of spaces between symbols
    public $no_spaces = true;

# show credits
    public $show_credits = true; # set to false to remove credits line. Credits adds 12 pixels to image height
    public $credits = 'www.captcha.ru'; # if empty, HTTP_HOST will be shown

# CAPTCHA image colors (RGB, 0-255)
//$foreground_color = array(0, 0, 0);
//$background_color = array(220, 230, 255);
    public $foreground_color = array(mt_rand(0,80), mt_rand(0,80), mt_rand(0,80));
    public $background_color = array(mt_rand(220,255), mt_rand(220,255), mt_rand(220,255));

# JPEG quality of CAPTCHA image (bigger is better quality, but larger file size)
    public $jpeg_quality = 80;
}
*/?>