<?php

class MyCaptcha
{
    public $code = "";
    public $fontList = array();
    private $rotation = 50;
    private $width = 200;
    private $height = 50;

    public function __construct()
    {
        if (isset($_SESSION["mycaptcha"]))
        {
            $this->code = $_SESSION["mycaptcha"];
        }
        
        $this->fontList = array(
            'fonts/arial.ttf',
            'fonts/hemihead.ttf',
            'fonts/leadcoat.ttf',
            'fonts/stocky.ttf',
            'fonts/stormfaze.ttf'
        );
    }

    public function generateCaptcha()
    {
        $this->code = chr(random_int(ord('A'), ord('Z')))
            . chr(random_int(ord('A'), ord('Z')))
            . chr(random_int(ord('A'), ord('Z')))
            . chr(random_int(ord('A'), ord('Z')));

        $_SESSION["mycaptcha"] = $this->code;
    }

    public function showCaptcha()
    {
        $this->generateCaptcha();
        $image = imagecreatetruecolor($this->width, $this->height);
        $backgroundColor = imagecolorallocate($image, 0, 0, 0);
        imagecolortransparent($image, $backgroundColor);

        for ($i = 0; $i < strlen($this->code); $i++)
        {
            $color = imagecolorallocate($image, 150, 150, 150);
            imagettftext($image, 
                        random_int(22, 25), 
                        random_int(-$this->rotation, $this->rotation),
                        10 + ($i * ($this->width / 4)), 
                        35,
                        $color, 
                        $this->fontList[random_int(0, 4)], 
                        $this->code[$i]);
        }           
        
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
        exit;
    }
    
    public function checkCaptcha($input)
    {
        return ($input == $this->code) ? true : false;
    }
}

