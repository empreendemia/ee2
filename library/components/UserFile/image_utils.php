<?php
/**
 * image_utils.php
 * Funções de manipulação de imagens
 * @author Mauro Ribeiro
 * @since 2012-08
 */

require_once 'Zend/Config/Ini.php';

/**
 * Pega a extensão do arquivo
 * @param string $str       nome do arquivo (exemplo: "imagem.jpg")
 * @return string           extensão do arquivo (exemplo: "jpg") 
 * @author Mauro Ribeiro
 */
function get_file_extension($str) {

    $i = strrpos($str,".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}

/**
 * Verifica se um arquivo é uma imagem
 * @param string $filePath      caminho do arquivo
 * @return boolean
 * @author Mauro Ribeiro
 */
function is_image($filePath) {
    $fileInfo = getimagesize($filePath);
    $allowedMimes = array(
        'image/jpg',
        'image/jpeg',
        'image/png',
        'image/gif'
    );

    if(in_array($fileInfo['mime'], $allowedMimes))
       return true;

    return false;
}

/**
 * Recorta uma imagem para um tamanho quadrado
 * @param image $src_img    imagem a ser recortada
 * @param string $new_img   caminho e nome do arquivo para onde será salva
 * @param type $scale       tamanho da imagem em px
 * @author Mauro Ribeiro
 */
function crop_image($src_img, $new_img, $scale) {
    $filetype = get_file_extension($src_img);
    $filetype = strtolower($filetype);

    // cria uma nova imagem a partir da original
    switch($filetype){
        case "jpeg":
        case "jpg":
          $src_img = ImageCreateFromjpeg ($src_img);
         break;
         case "gif":
          $src_img = imagecreatefromgif ($src_img);
         break;
         case "png":
          $src_img = imagecreatefrompng ($src_img);
         break;
    }

    $width = imagesx($src_img);
    $height = imagesy($src_img);
    // proporção da largura caso height = scale
    $ratiox = $width / $height * $scale;
    // proporção da altura caso width = scale
    $ratioy = $height / $width * $scale;

    // o menor é igual à escala e o maior é proporcional
    $newheight = ($width <= $height) ? $ratioy : $scale;
    $newwidth = ($width <= $height) ? $scale : $ratiox;

    // calcula o recorte
    $cropx = ($newwidth - $scale != 0) ? ($newwidth - $scale) / 2 : 0;
    $cropy = ($newheight - $scale != 0) ? ($newheight - $scale) / 2 : 0;

    // buffer da imagem redimencionada
    $resampled = imagecreatetruecolor($newwidth, $newheight);
    // buffer da imagem recortada quadrada
    $cropped = imagecreatetruecolor($scale, $scale);

    // redimenciona
    imagecopyresampled($resampled, $src_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    // recorta
    imagecopy($cropped, $resampled, 0, 0, $cropx, $cropy, $newwidth, $newheight);

    // salva nova imagem
    switch($filetype)
    {

        case "jpeg":
        case "jpg":
         imagejpeg($cropped,$new_img,80);
         break;
         case "gif":
         imagegif($cropped,$new_img,80);
         break;
         case "png":
         imagepng($cropped,$new_img,8);
         break;
    }
}


/**
 * Redimenciona uma imagem para um tamanho quadrado
 * @param image $src_img    imagem a ser recortada
 * @param string $new_img   caminho e nome do arquivo para onde será salva
 * @param type $scale       tamanho da imagem em px
 */
function resize_image($src_img, $new_img, $size)    {
    $filetype = get_file_extension($src_img);
    $filetype = strtolower($filetype);

    // cria uma nova imagem a partir da original
    switch($filetype) {
        case "jpeg":
        case "jpg":
        $src_img = ImageCreateFromjpeg ($src_img);
        break;
        case "gif":
        $src_img = imagecreatefromgif ($src_img);
        break;
        case "png":
        $src_img = imagecreatefrompng ($src_img);
        break;
    }

    $true_width = imagesx($src_img);
    $true_height = imagesy($src_img);

    // a dimensão (altura vs largura) maior é igual a escala
    // a menor é proporcional
    if ($true_width >= $true_height)
    {
        $width=$size;
        $height = ($width/$true_width)*$true_height;
    }
    else
    {
        $height=$size;
        $width = ($height/$true_height)*$true_width;
    }
    // buffer da nova imagem
    $img_des = imagecreatetruecolor($size,$size);
    // como pode sobrar espaço vazio, preenche com branco
    $bgcolor = imagecolorallocate($img_des, 255, 255, 255);
    imagefilledrectangle($img_des, 0, 0, $size, $size, $bgcolor);
    imagecopyresampled ($img_des, $src_img, ($size - $width) / 2, ($size - $height) / 2, 0, 0, $width, $height, $true_width, $true_height);

    // salva a imagem redimensionada
    switch($filetype)
    {
        case "jpeg":
        case "jpg":
         imagejpeg($img_des,$new_img,80);
         break;
         case "gif":
         imagegif($img_des,$new_img,80);
         break;
         case "png":
         imagepng($img_des,$new_img,8);
         break;
    }
}



/**
 * Escala uma imagem
 * @param image $src_img    imagem a ser recortada
 * @param string $new_img   caminho e nome do arquivo para onde será salva
 * @param int $size         força o tamanho da imagem em px
 * @param int $max          apenas diminui o tamanho da imagem em px
 */
function scale_image($src_img, $new_img, $size, $max = null)    {
    $filetype = get_file_extension($src_img);
    $filetype = strtolower($filetype);
    
    // cria uma nova imagem a partir da original
    switch($filetype) {
        case "jpeg":
        case "jpg":
        $img_src = ImageCreateFromjpeg ($src_img);
        break;
        case "gif":
        $img_src = imagecreatefromgif ($src_img);
        break;
        case "png":
        $img_src = imagecreatefrompng ($src_img);
        break;
    }

    $true_width = imagesx($img_src);
    $true_height = imagesy($img_src);
    
    if ($size == 0 && $max != null) {
        // diminui a imagem
        if (max($true_width, $true_height) > $max) $size = $max;
        // não faz nada
        else ($size = max($true_width, $true_height));
    }

    // escala a dimensão maior para $size
    // escala a menor proporcionalmente
    if ($true_width >= $true_height)
    {
        $width=$size;
        $height = ($width/$true_width)*$true_height;
    }
    else
    {
        $height=$size;
        $width = ($height/$true_height)*$true_width;
    }
    $img_des = imagecreatetruecolor($width,$height);
    $bgcolor = imagecolorallocate($img_des, 255, 255, 255);  
    imagefilledrectangle($img_des, 0, 0, $width, $height, $bgcolor);
    imagecopyresampled ($img_des, $img_src, 0, 0, 0, 0, $width, $height, $true_width, $true_height);

    // salva a nova imagem
    switch($filetype)
    {
        case "jpeg":
        case "jpg":
         imagejpeg($img_des,$new_img,80);
         break;
         case "gif":
         imagegif($img_des,$new_img,80);
         break;
         case "png":
         imagepng($img_des,$new_img,8);
         break;
    }
}
