<?php
namespace app\index\controller;
use phpqrcode\QRcode;
class Index
{
     
      public function index()
      {
          include APP_PATH."/../vendor/phpqrcode/phpqrcode.php";
          new QRcode();
          $QR = APP_PATH."/test/qrcode.png";
           $errorCorrectionLevel = 'L';//容错级别
           $matrixPointSize = 6;//生成图片大小
          QRcode::png('http://www.baidu.com', $QR, $errorCorrectionLevel, $matrixPointSize, 2);

          $logo = APP_PATH."/test/logo.png";

          if ($logo !== FALSE) {
                   $QR = imagecreatefromstring(file_get_contents($QR));
                   $logo = imagecreatefromstring(file_get_contents($logo)); 
                  $QR_width = imagesx($QR);//二维码图片宽度

                  $QR_height = imagesy($QR);//二维码图片高度 
                  $logo_width = imagesx($logo);//logo图片宽度      
                  $logo_height = imagesy($logo);//logo图片高度

                   $logo_qr_width = $QR_width / 5;

                  $scale = $logo_width/$logo_qr_width;

                   $logo_qr_height = $logo_height/$scale;

                   $from_width = ($QR_width - $logo_qr_width) / 2;

              //重新组合图片并调整大小

                   imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,

                       $logo_qr_height, $logo_width, $logo_height);
          }
          $path  = APP_PATH.'/test/';
           $png = $path.'2.png';

           if(!is_dir($path)) mkdir($path,0777);

          file_put_contents($png,"", FILE_APPEND);

          imagepng($QR, $png); 
          echo "<img src=test/2.png />";exit();

      }
    }
