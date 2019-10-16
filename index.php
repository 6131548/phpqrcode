<?php
namespace app\index\controller;
use think\Controller;
use phpqrcode\QRcode;
class Index extends Controller
{
    
    public function index(){
        if($this->request->isPost()){
          $data = $this->request->post("text");
         
          return $this->gernerImg($data);
        }

        return $this->fetch("index");
        // $this->gernerImg("www.qq.com");
    }
    public function gernerImg($value)
    {
        include APP_PATH."/../vendor/phpqrcode/phpqrcode.php";
        new QRcode();
        $QR = APP_PATH."/test/qrcode.png";
         $errorCorrectionLevel = 'L';//容错级别
         $matrixPointSize = 20;//生成图片大小
         file_put_contents($QR,"");
         // die;
        QRcode::png($value, $QR, $errorCorrectionLevel, $matrixPointSize, 2);
      //   QRcode::png('爱你么么么');
      // exit();
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
        $name = time().'.png';
        $png = $path.$name;
        $img = "test/".$name;
         if(!is_dir($path)) mkdir($path,0777);
        imagepng($QR, $png); 
        $a = $this->imgToBase64($png);
        // echo $a;
        // echo '<img src="' . $a . '">';
        
        //删除图片
        if(file_exists($png)){
            unlink($png);
         }
         return $a;
       
    }

    /**
 * 获取图片的Base64编码(不支持url)
 * @date 2017-02-20 19:41:22
 *
 * @param $img_file 传入本地图片地址
 *
 * @return string
 */
   public function imgToBase64($img_file) {

        $img_base64 = '';
        if (file_exists($img_file)) {
            $app_img_file = $img_file; // 图片路径
            $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

            //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
            $fp = fopen($app_img_file, "r"); // 图片是否可读权限

            if ($fp) {
                $filesize = filesize($app_img_file);
                $content = fread($fp, $filesize);
                $file_content = chunk_split(base64_encode($content)); // base64编码
                switch ($img_info[2]) {           //判读图片类型
                    case 1: $img_type = "gif";
                        break;
                    case 2: $img_type = "jpg";
                        break;
                    case 3: $img_type = "png";
                        break;
                }

                $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码

            }
            fclose($fp);
        }

        return $img_base64; //返回图片的base64
    }
    
}
