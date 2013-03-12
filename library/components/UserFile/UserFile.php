<?php
/**
 * UserFile.php - Ee_UserFile
 * Manipulação dos arquivos dos usuários
 * 
 * @package components
 * @author Mauro Ribeiro
 * @since 2011-07-29
 */
require_once 'Zend/Config/Ini.php';

class Ee_UserFile {
    /**
     * Caminho para a imagem
     * @var string
     * @example "/home/empreendemia/www/public/userfiles/companies/4292/2as.jpg"
     */
    public $path;
    /**
     * URL da imagem relativo ao documentroot do aplicativo
     * @var string
     * @example "userfiles/companies/4292/2as.jpg"
     */
    public $url;
    /**
     * Tamanho da imagem (thumbnail)
     * @var int
     */
    public $size;
    /**
     * Nome do arquivo com extensão
     * @var string
     * @example "9sfx34se2d.jpg"
     */
    public $file;
    /**
     * Nome do arquivo
     * @var string
     * @example "9sfx34se2d"
     */
    public $filename;
    /**
     * Extensão do arquivo
     * @var string
     * @example "jpg"
     */
    public $ext;
    /**
     * Tipo do arquivo
     * @var string
     * @example "userThumb" para fotenha de usuário
     * @example "companyThumb" para loguinhow de empresa
     * @example "companySideImage" para imagem lateral de empresa premium
     * @example "companyCardImage" para imagem do cartão de empresa premium
     * @example "productThumb" para fotenha de produto
     * @example "productImage para imagem da galeria de produto
     */
    public $type;
    /**
     * Configurações de imagem
     * @var object
     * @example $this->config->salt contém salt para hash das pastas
     * @example $this->config->urlpath fala onde estão as imagens
     * @example $this->config->filepath mostra o caminho de onde estão as imagens
     * @example $this->config->temp fala onde é a pasta temporária
     */
    public $config;

    /**
     * Construtor da porra toda
     * @param type $options         parâmetros para inicialização do arquivo
     * @example $userimage = new Ee_UserFile() cria um objeto vazio
     * @example Ee_UserFile(array('type'=>'userImage','id'=>$user->id,'image'=>$user->image,'size'=>200)) pega a imagem do usuário
     */
    public function __construct($options = null) {
        // inicializa a config
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');
        $this->config = $config->files;

        // se passou todos os parâmetros bonitinho, chama getType onde Type = $options['type']
        if (isset($options) && isset($options['type']) && isset($options['id']) && isset($options['image'])) {
            $function = 'get'.$options['type'];
            if (isset($options['size'])) {
                $this->$function($options['id'], $options['image'], $options['size']);
            }
            else {
                $this->$function($options['id'], $options['image']);
            }
        }
    }

    /**
     * Gerador aleatório de nome de 10 caracteres
     * @return string
     */
    public function genFilename() {
        $hash = substr(sha1(date('YmdHis').rand(1, 9999).$this->config->salt), 0, 10);
        return $hash;
    }

    /**
     * Retorna a hash da empresa
     * @param int $company_id       id da empresa
     * @return string 
     */
    private function companyHash($company_id) {
        $hash = $company_id.sha1($company_id.$this->config->salt);
        return $hash;
    }

    
    /**
     * Retorna a hash do grupo da (novo hash a cada 1000 empresas)
     * @param int $company_id       id da empresa
     * @return string 
     */
    private function userHash($user_id) {
        $hash = $user_id.sha1($user_id.$this->config->salt);
        return $hash;
    }

    /**
     * Pega a extensão do arquivo
     * @param string $str       nome do arquivo
     * @return string           extensão do arquivo
     */
    function getFileExtension($str) {
        $i = strrpos($str,".");
        if (!$i) { return ""; }
        $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);
        return $ext;
    }

    /**
     * Caminho para a pasta do usuário
     * @param int $user_id      id do usuário
     * @param boolean $mkdir    cria as pastas se não existir ainda
     * @return string           caminho 
     */
    public function getUserFolder($user_id, $mkdir = false) {
        // indice (1000 usuários em uma pasta)
        $index = (int)($user_id/1000);
        // calcula a hash do usuário
        $hash = $this->userHash($user_id);
        // monta o caminho
        $subpath = 'users/'.$index.'/'.$hash.'/';
        // cria as pastas
        if ($mkdir) {
            if (!file_exists($this->config->filepath.'users/'.$index.'/'))
                    mkdir($this->config->filepath.'users/'.$index.'/');
            if (!file_exists($this->config->filepath.'users/'.$index.'/'.$hash.'/'))
                    mkdir($this->config->filepath.'users/'.$index.'/'.$hash.'/');
        }
        return $subpath;
    }

    /**
     * Pega a foto do usuário
     * @param int $user_id          id do usuário
     * @param string $user_image    nome do arquivo do usuário
     * @param int $size             tamanho da imagem em px
     * @return Ee_UserFile          imagem 
     */
    public function getUserThumb($user_id, $user_image, $size = 200) {
        if ($user_image == null) return false;

        // arredonda para 50, 100 ou 200 px
        if ($size <= 50) $tsize = 50;
        else if ($size <= 100) $tsize = 100;
        else $tsize = 200;

        // caminho para pasta do usuário
        $subpath = $this->getUserFolder($user_id).$tsize.'_'.$user_image;

        $this->url = 'http://'.$this->config->aws->bucket.'.'.$this->config->aws->url.'/'.$subpath;
        $this->path = $subpath;
        //$this->size = $tsize;
        $this->type = 'userThumb';
        $this->file = $user_image;
        $file_explode = explode('.', $user_image);
        $this->filename = $file_explode[1];
        $this->ext = $file_explode[0];
                
        return $this;
    }

    /**
     * Salva uma foto nova do usuário
     * @param int $user_id          id do usuário
     * @param string $user_image    nome do arquivo do usuário
     * @param $image                temporário da nova imagem
     * @return boolean
     */
    public function setUserThumb($user_id, $user_image, $image) {
        require_once 'image_utils.php';
        require_once 'components/Aws-1.6.0/sdk.class.php';
        $folder = $this->getUserFolder($user_id, true);
        $user_folder = $this->config->filepath.$folder;
        // aws
        $aws_folder = $folder;
        $bucket = $this->config->aws->bucket;
        // brinca com o nome
        $ext = get_file_extension($image);
        $filename = $this->genFilename();
        $file = $filename.'.'.$ext;
        // seta a porra toda
        $this->ext = $ext;
        $this->filename = $filename;
        $this->file = $file;
        $this->url = $this->config->bucket.$user_folder;
        $this->path = $user_folder;
        $this->size = null;
        $this->type = 'userThumb';
        
        $s3 = new AmazonS3(array('key' => $this->config->aws->key, 'secret' => $this->config->aws->secret));
        $s3->set_region(AmazonS3::REGION_SA_E1);
        // apaga imagens anteriores
        $s3->delete_object($bucket, $aws_folder.'50_'.$user_image);
        $s3->delete_object($bucket, $aws_folder.'100_'.$user_image);
        $s3->delete_object($bucket, $aws_folder.'200_'.$user_image);
        // dá resize e grava as novas imagens
        resize_image($image, $user_folder.'50_'.$file, 50);
        resize_image($image, $user_folder.'100_'.$file, 100);
        resize_image($image, $user_folder.'200_'.$file, 200);
        // envia para AWS S3
        $s3->create_object($bucket, $aws_folder.'50_'.$file, array(
            'fileUpload' => $user_folder.'50_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        $s3->create_object($bucket, $aws_folder.'100_'.$file, array(
            'fileUpload' => $user_folder.'100_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        $s3->create_object($bucket, $aws_folder.'200_'.$file, array(
            'fileUpload' => $user_folder.'200_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        // apaga temporários
        unlink($user_folder.'50_'.$file);
        unlink($user_folder.'100_'.$file);
        unlink($user_folder.'200_'.$file);
        
        // apaga o temporário
        unlink($image);
        return true;
    }

    /**
     * Caminho para a pasta da empresa
     * @param int $company_id   id da empresa
     * @param boolean $mkdir    cria as pastas se não existir ainda
     * @return string           caminho 
     */
    private function getCompanyFolder($company_id, $mkdir = false) {
        // pega o hash da empresa
        $hash = $this->companyHash($company_id);
        // índice da empresa (1000 empresas por pasta)
        $index = (int)($company_id/1000);
        // monta o caminho
        $folder = 'companies/'.$index.'/'.$hash.'/';
        // cria as pastas
        if ($mkdir) {
            if (!file_exists($this->config->filepath.'companies/'.$index.'/'))
                    mkdir($this->config->filepath.'companies/'.$index.'/');
            if (!file_exists($this->config->filepath.'companies/'.$index.'/'.$hash.'/'))
                    mkdir($this->config->filepath.'companies/'.$index.'/'.$hash.'/');
            if (!file_exists($this->config->filepath.'companies/'.$index.'/'.$hash.'/products/'))
                    mkdir($this->config->filepath.'companies/'.$index.'/'.$hash.'/products/');
        }
        return $folder;
    }

    /**
     * Pega o logotipo da empresa
     * @param int $company_id           id da empresa
     * @param string $company_image     nome do arquivo do logotipo da empresa
     * @param int $size                 tamanho da imagem em px
     * @return Ee_UserFile              imagem 
     */
    public function getCompanyThumb($company_id, $company_image, $size = 200) {
        if ($company_image == null) return false;
        
        // arredonda para 50, 100 ou 200 px
        if ($size <= 50) $tsize = 50;
        else if ($size <= 100) $tsize = 100;
        else $tsize = 200;
        
        // caminho para o arquivo
        $subpath = $this->getCompanyFolder($company_id).$tsize.'_'.$company_image;

        $this->url = 'http://'.$this->config->aws->bucket.'.'.$this->config->aws->url.'/'.$subpath;
        $this->path = $subpath;
        //$this->size = $tsize;
        $this->type = 'companyThumb';
        $this->file = $company_image;
        $file_explode = explode('.', $company_image);
        $this->filename = $file_explode[1];
        $this->ext = $file_explode[0];
                
        return $this;
    }

    /**
     * Salva um novo logotipo para a empresa
     * @param int $company_id           id da empresa
     * @param string $company_image     nome do arquivo da empresa
     * @param $image                    temporário da nova imagem
     * @return boolean
     */
    public function setCompanyThumb($company_id, $company_image, $image) {
        require_once 'image_utils.php';
        require_once 'components/Aws-1.6.0/sdk.class.php';
        // pasta da empresa
        $folder = $this->getCompanyFolder($company_id, true);
        $company_folder = $this->config->filepath.$folder;
        // aws
        $aws_folder = $folder;
        $bucket = $this->config->aws->bucket;
        // brinca com o nome
        $ext = get_file_extension($image);
        $filename = $this->genFilename();
        $file = $filename.'.'.$ext;
        // seta a porra toda
        $this->ext = $ext;
        $this->filename = $filename;
        $this->file = $file;
        $this->url = $this->config->bucket.$company_folder;
        $this->path = $company_folder;
        $this->size = null;
        $this->type = 'companyThumb';
        
        $s3 = new AmazonS3(array('key' => $this->config->aws->key, 'secret' => $this->config->aws->secret));
        $s3->set_region(AmazonS3::REGION_SA_E1);
        // apaga imagens anteriores
        $s3->delete_object($bucket, $aws_folder.'50_'.$company_image);
        $s3->delete_object($bucket, $aws_folder.'100_'.$company_image);
        $s3->delete_object($bucket, $aws_folder.'200_'.$company_image);
        // dá resize e grava as novas imagens
        resize_image($image, $company_folder.'50_'.$file, 50);
        resize_image($image, $company_folder.'100_'.$file, 100);
        resize_image($image, $company_folder.'200_'.$file, 200);
        // envia para AWS S3
        $s3->create_object($bucket, $aws_folder.'50_'.$file, array(
            'fileUpload' => $company_folder.'50_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        $s3->create_object($bucket, $aws_folder.'100_'.$file, array(
            'fileUpload' => $company_folder.'100_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        $s3->create_object($bucket, $aws_folder.'200_'.$file, array(
            'fileUpload' => $company_folder.'200_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        // apaga temporários
        unlink($company_folder.'50_'.$file);
        unlink($company_folder.'100_'.$file);
        unlink($company_folder.'200_'.$file);
        
        // apaga o temporário
        unlink($image);
        return true;
    }


    /**
     * Pega a imagem lateral da empresa
     * @param int $company_id           id da empresa
     * @param string $company_image     nome do arquivo da imagem da empresa
     * @return Ee_UserFile              imagem 
     */
    public function getCompanySideImage($company_id, $company_image) {
        if ($company_image == null) return false;
        // caminho da imagem
        $subpath = $this->getCompanyFolder($company_id).$company_image;

        $this->url = 'http://'.$this->config->aws->bucket.'.'.$this->config->aws->url.'/'.$subpath;
        $this->path = $subpath;
        //$this->size = $tsize;
        $this->type = 'companySideImage';
        $this->file = $company_image;
        $file_explode = explode('.', $company_image);
        $this->filename = $file_explode[1];
        $this->ext = $file_explode[0];
        
        return $this;
    }

    /**
     * Salva uma imagem lateral nova da empresa
     * @param int $company_id           id da empresa
     * @param string $company_image     nome do arquivo da empresa
     * @param $image                    temporário da nova imagem
     * @return boolean
     */
    public function setCompanySideImage($company_id, $old_image, $image) {
        require_once 'image_utils.php';
        require_once 'components/Aws-1.6.0/sdk.class.php';
        // caminho da pasta da empresa
        $folder = $this->getCompanyFolder($company_id, true);
        $company_folder = $this->config->filepath.$folder;
        // aws
        $aws_folder = $folder;
        $bucket = $this->config->aws->bucket;
        // brinca com o nome
        $ext = get_file_extension($image);
        $filename = $this->genFilename();
        $file = $filename.'.'.$ext;
        // seta a porra toda
        $this->ext = $ext;
        $this->filename = $filename;
        $this->file = $file;
        $this->url = $this->config->bucket.$company_folder;
        $this->path = $company_folder;
        $this->size = null;
        $this->type = 'companySideImage';
        
        $s3 = new AmazonS3(array('key' => $this->config->aws->key, 'secret' => $this->config->aws->secret));
        $s3->set_region(AmazonS3::REGION_SA_E1);
        $s3->delete_object($bucket, $aws_folder.$old_image);
        $s3->create_object($bucket, $aws_folder.$file, array(
            'fileUpload' => $image,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        unlink($image);
        return true;
    }

    /**
     * Pega a imagem do cartão da empresa
     * @param int $company_id           id da empresa
     * @param string $company_image     nome do arquivo da imagem da empresa
     * @return Ee_UserFile              imagem 
     */
    public function getCompanyCardImage($company_id, $company_image) {
        if ($company_image == null) return false;
        // caminho da imagem
        $subpath = $this->getCompanyFolder($company_id).$company_image;

        $this->url = 'http://'.$this->config->aws->bucket.'.'.$this->config->aws->url.'/'.$subpath;
        $this->path = $subpath;
        //$this->size = $tsize;
        $this->type = 'companyCardImage';
        $this->file = $company_image;
        $file_explode = explode('.', $company_image);
        $this->filename = $file_explode[1];
        $this->ext = $file_explode[0];
        
        return $this;
    }
    
    /**
     * Salva uma imagem nova do cartão da empresa
     * @param int $company_id           id da empresa
     * @param string $company_image     nome do arquivo da empresa
     * @param $image                    temporário da nova imagem
     * @return boolean
     */
    public function setCompanyCardImage($company_id, $old_image, $image) {
        require_once 'image_utils.php';
        require_once 'components/Aws-1.6.0/sdk.class.php';
        // caminho da pasta da empresa
        $folder = $this->getCompanyFolder($company_id, true);
        $company_folder = $this->config->filepath.$folder;
        // aws
        $aws_folder = $folder;
        $bucket = $this->config->aws->bucket;
        // brinca com o nome
        $ext = get_file_extension($image);
        $filename = $this->genFilename();
        $file = $filename.'.'.$ext;
        // seta a porra toda
        $this->ext = $ext;
        $this->filename = $filename;
        $this->file = $file;
        $this->url = $this->config->urlpath.$company_folder;
        $this->path = $this->config->filepath.$company_folder;
        $this->size = null;
        $this->type = 'companyCardImage';
        
        $s3 = new AmazonS3(array('key' => $this->config->aws->key, 'secret' => $this->config->aws->secret));
        $s3->set_region(AmazonS3::REGION_SA_E1);
        $s3->delete_object($bucket, $aws_folder.$old_image);
        $s3->create_object($bucket, $aws_folder.$file, array(
            'fileUpload' => $image,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        unlink($image);
        return true;
    }

    /**
     * Pega a fotenha do produto
     * @param int $company_id           id da empresa
     * @param int $product_id           id do produto
     * @param string $product_image     nome do arquivo do produto
     * @param int $size                 tamanho da imagem em px
     * @return Ee_UserFile              imagem 
     */
    public function getProductThumb($company_id, $product_id, $product_image, $size = 200) {
        if ($product_image == null) return false;
        
        // arredonda para 50, 100 ou 200 px
        if ($size <= 50) $tsize = 50;
        else if ($size <= 100) $tsize = 100;
        else $tsize = 200;
        
        // caminho para o arquivo
        $subpath = $this->getCompanyFolder($company_id).'products/'.$tsize.'_'.$product_image;

        $this->url = 'http://'.$this->config->aws->bucket.'.'.$this->config->aws->url.'/'.$subpath;
        $this->path = $subpath;
        //$this->size = $tsize;
        $this->type = 'productThumb';
        $this->file = $product_image;
        $file_explode = explode('.', $product_image);
        $this->filename = $file_explode[1];
        $this->ext = $file_explode[0];
                
        return $this;
    }

    /**
     * Salva nova imagem do produto
     * @param int $company_id           id da empresa
     * @param int $product_id           id do produto
     * @param string $old_image         nome do arquivo antigo
     * @param $image                    temporário do novo arquivo
     * @return boolean
     */
    public function setProductThumb($company_id, $product_id, $old_image, $image) {
        
        require_once 'image_utils.php';
        require_once 'components/Aws-1.6.0/sdk.class.php';
        // pasta da empresa
        $folder = $this->getCompanyFolder($company_id, true).'products/';
        $company_folder = $this->config->filepath.$folder;
        // aws
        $aws_folder = $folder;
        $bucket = $this->config->aws->bucket;
        // brinca com o nome
        $ext = get_file_extension($image);
        $filename = $this->genFilename();
        $file = $filename.'.'.$ext;
        // seta a porra toda
        $this->ext = $ext;
        $this->filename = $filename;
        $this->file = $file;
        $this->url = $this->config->bucket.$company_folder;
        $this->path = $company_folder;
        $this->size = null;
        $this->type = 'productThumb';
        
        $s3 = new AmazonS3(array('key' => $this->config->aws->key, 'secret' => $this->config->aws->secret));
        $s3->set_region(AmazonS3::REGION_SA_E1);
        // apaga imagens anteriores
        $s3->delete_object($bucket, $aws_folder.'50_'.$old_image);
        $s3->delete_object($bucket, $aws_folder.'100_'.$old_image);
        $s3->delete_object($bucket, $aws_folder.'200_'.$old_image);
        // dá resize e grava as novas imagens
        resize_image($image, $company_folder.'50_'.$file, 50);
        resize_image($image, $company_folder.'100_'.$file, 100);
        resize_image($image, $company_folder.'200_'.$file, 200);
        // envia para AWS S3
        $s3->create_object($bucket, $aws_folder.'50_'.$file, array(
            'fileUpload' => $company_folder.'50_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        $s3->create_object($bucket, $aws_folder.'100_'.$file, array(
            'fileUpload' => $company_folder.'100_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        $s3->create_object($bucket, $aws_folder.'200_'.$file, array(
            'fileUpload' => $company_folder.'200_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        // apaga temporários
        unlink($company_folder.'50_'.$file);
        unlink($company_folder.'100_'.$file);
        unlink($company_folder.'200_'.$file);
        
        // apaga o temporário
        unlink($image);
        return true;
    }


    /**
     * Pega uma imagem da galeria de imagens do produto
     * @param int $company_id           id da empresa
     * @param int $product_id           id do produto
     * @param string $product_image     nome do arquivo do produto
     * @param int|string $size          tamanho da imagem em px
     * @return Ee_UserFile              imagem 
     */
    public function getProductImage($company_id, $product_id, $product_image, $size = 200) {
        if ($product_image == null) return false;
        
        // arredonda para full ou 50, 100 ou 200 px
        if ($size == 'full') $tsize = 'full';
        else if ($size <= 50) $tsize = 50;
        else if ($size <= 100) $tsize = 100;
        else $tsize = 200;

        // caminho para o arquivo da imagem
        $subpath = $this->getCompanyFolder($company_id).'products/'.$tsize.'_'.$product_image;

        // se existe seta a porra toda
        $this->url = 'http://'.$this->config->aws->bucket.'.'.$this->config->aws->url.'/'.$subpath;
        $this->path = $subpath;
        //$this->size = $tsize;
        $this->type = 'productImage';
        $this->file = $product_image;
        $file_explode = explode('.', $product_image);
        $this->filename = $file_explode[1];
        $this->ext = $file_explode[0];

        return $this;
    }


    /**
     * Salva nova imagem da galeria de imagens do produto
     * @param int $company_id           id da empresa
     * @param int $product_id           id do produto
     * @param string $old_image         nome do arquivo antigo
     * @param $image                    temporário do novo arquivo
     * @param int $maxwidth             largura máxima em px
     * @param int $maxheight            altura máxima em px
     * @return boolean
     */
    public function setProductImage($company_id, $product_id, $old_image, $image, $maxwidth = 800, $maxheight = 600) {
        require_once 'image_utils.php';
        require_once 'components/Aws-1.6.0/sdk.class.php';
        // pasta da empresa
        $folder = $this->getCompanyFolder($company_id, true).'products/';
        $products_folder = $this->config->filepath.$folder;
        // aws
        $aws_folder = $folder;
        $bucket = $this->config->aws->bucket;
        // brinca com o nome
        $ext = get_file_extension($image);
        $filename = $this->genFilename();
        $file = $filename.'.'.$ext;
        // seta a porra toda
        $this->ext = $ext;
        $this->filename = $filename;
        $this->file = $file;
        $this->url = $this->config->bucket.$products_folder;
        $this->path = $products_folder;
        $this->size = null;
        $this->type = 'productImage';
        
        $s3 = new AmazonS3(array('key' => $this->config->aws->key, 'secret' => $this->config->aws->secret));
        $s3->set_region(AmazonS3::REGION_SA_E1);
        // apaga imagens anteriores
        $s3->delete_object($bucket, $aws_folder.'50_'.$old_image);
        $s3->delete_object($bucket, $aws_folder.'100_'.$old_image);
        $s3->delete_object($bucket, $aws_folder.'200_'.$old_image);
        $s3->delete_object($bucket, $aws_folder.'full_'.$old_image);
        // dá resize e grava as novas imagens
        resize_image($image, $products_folder.'50_'.$file, 50);
        resize_image($image, $products_folder.'100_'.$file, 100);
        resize_image($image, $products_folder.'200_'.$file, 200);
        resize_image($image, $products_folder.'full_'.$file, 200);
        // envia para AWS S3
        $s3->create_object($bucket, $aws_folder.'50_'.$file, array(
            'fileUpload' => $products_folder.'50_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        $s3->create_object($bucket, $aws_folder.'100_'.$file, array(
            'fileUpload' => $products_folder.'100_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        $s3->create_object($bucket, $aws_folder.'200_'.$file, array(
            'fileUpload' => $products_folder.'200_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        $s3->create_object($bucket, $aws_folder.'full_'.$file, array(
            'fileUpload' => $products_folder.'full_'.$file,
            'acl' => AmazonS3::ACL_PUBLIC
	));
        // apaga temporários
        unlink($products_folder.'50_'.$file);
        unlink($products_folder.'100_'.$file);
        unlink($products_folder.'200_'.$file);
        unlink($products_folder.'full_'.$file);
        
        // apaga o temporário
        unlink($image);
        return true;
    }
}
//
//<?php
///**
// * UserFile.php - Ee_UserFile
// * Manipulação dos arquivos dos usuários
// * 
// * @package components
// * @author Mauro Ribeiro
// * @since 2011-07-29
// */
//require_once 'Zend/Config/Ini.php';
//
//class Ee_UserFile {
//    /**
//     * Caminho para a imagem
//     * @var string
//     * @example "/home/empreendemia/www/public/userfiles/companies/4292/2as.jpg"
//     */
//    public $path;
//    /**
//     * URL da imagem relativo ao documentroot do aplicativo
//     * @var string
//     * @example "userfiles/companies/4292/2as.jpg"
//     */
//    public $url;
//    /**
//     * Tamanho da imagem (thumbnail)
//     * @var int
//     */
//    public $size;
//    /**
//     * Nome do arquivo com extensão
//     * @var string
//     * @example "9sfx34se2d.jpg"
//     */
//    public $file;
//    /**
//     * Nome do arquivo
//     * @var string
//     * @example "9sfx34se2d"
//     */
//    public $filename;
//    /**
//     * Extensão do arquivo
//     * @var string
//     * @example "jpg"
//     */
//    public $ext;
//    /**
//     * Tipo do arquivo
//     * @var string
//     * @example "userThumb" para fotenha de usuário
//     * @example "companyThumb" para loguinhow de empresa
//     * @example "companySideImage" para imagem lateral de empresa premium
//     * @example "companyCardImage" para imagem do cartão de empresa premium
//     * @example "productThumb" para fotenha de produto
//     * @example "productImage para imagem da galeria de produto
//     */
//    public $type;
//    /**
//     * Configurações de imagem
//     * @var object
//     * @example $this->config->salt contém salt para hash das pastas
//     * @example $this->config->urlpath fala onde estão as imagens
//     * @example $this->config->filepath mostra o caminho de onde estão as imagens
//     * @example $this->config->temp fala onde é a pasta temporária
//     */
//    public $config;
//
//    /**
//     * Construtor da porra toda
//     * @param type $options         parâmetros para inicialização do arquivo
//     * @example $userimage = new Ee_UserFile() cria um objeto vazio
//     * @example Ee_UserFile(array('type'=>'userImage','id'=>$user->id,'image'=>$user->image,'size'=>200)) pega a imagem do usuário
//     */
//    public function __construct($options = null) {
//        // inicializa a config
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/ee.ini','production');
//        $this->config = $config->files;
//
//        // se passou todos os parâmetros bonitinho, chama getType onde Type = $options['type']
//        if (isset($options) && isset($options['type']) && isset($options['id']) && isset($options['image'])) {
//            $function = 'get'.$options['type'];
//            if (isset($options['size'])) {
//                $this->$function($options['id'], $options['image'], $options['size']);
//            }
//            else {
//                $this->$function($options['id'], $options['image']);
//            }
//        }
//    }
//
//    /**
//     * Gerador aleatório de nome de 10 caracteres
//     * @return string
//     */
//    public function genFilename() {
//        $hash = substr(sha1(date('YmdHis').rand(1, 9999).$this->config->salt), 0, 10);
//        return $hash;
//    }
//
//    /**
//     * Retorna a hash da empresa
//     * @param int $company_id       id da empresa
//     * @return string 
//     */
//    private function companyHash($company_id) {
//        $hash = $company_id.sha1($company_id.$this->config->salt);
//        return $hash;
//    }
//
//    
//    /**
//     * Retorna a hash do grupo da (novo hash a cada 1000 empresas)
//     * @param int $company_id       id da empresa
//     * @return string 
//     */
//    private function userHash($user_id) {
//        $hash = $user_id.sha1($user_id.$this->config->salt);
//        return $hash;
//    }
//
//    /**
//     * Pega a extensão do arquivo
//     * @param string $str       nome do arquivo
//     * @return string           extensão do arquivo
//     */
//    function getFileExtension($str) {
//        $i = strrpos($str,".");
//        if (!$i) { return ""; }
//        $l = strlen($str) - $i;
//        $ext = substr($str,$i+1,$l);
//        return $ext;
//    }
//
//    /**
//     * Caminho para a pasta do usuário
//     * @param int $user_id      id do usuário
//     * @param boolean $mkdir    cria as pastas se não existir ainda
//     * @return string           caminho 
//     */
//    public function getUserFolder($user_id, $mkdir = false) {
//        // indice (1000 usuários em uma pasta)
//        $index = (int)($user_id/1000);
//        // calcula a hash do usuário
//        $hash = $this->userHash($user_id);
//        // monta o caminho
//        $subpath = 'users/'.$index.'/'.$hash.'/';
//        // cria as pastas
//        if ($mkdir) {
//            if (!file_exists($this->config->filepath.'users/'.$index.'/'))
//                    mkdir($this->config->filepath.'users/'.$index.'/');
//            if (!file_exists($this->config->filepath.'users/'.$index.'/'.$hash.'/'))
//                    mkdir($this->config->filepath.'users/'.$index.'/'.$hash.'/');
//        }
//        return $subpath;
//    }
//
//    /**
//     * Pega a foto do usuário
//     * @param int $user_id          id do usuário
//     * @param string $user_image    nome do arquivo do usuário
//     * @param int $size             tamanho da imagem em px
//     * @return Ee_UserFile          imagem 
//     */
//    public function getUserThumb($user_id, $user_image, $size = 200) {
//        if ($user_image == null) return false;
//
//        // arredonda para 50, 100 ou 200 px
//        if ($size <= 50) $tsize = 50;
//        else if ($size <= 100) $tsize = 100;
//        else $tsize = 200;
//
//        // caminho para pasta do usuário
//        $subpath = $this->getUserFolder($user_id).$tsize.'_'.$user_image;
//        
//        // se existe o arquivo, seta a porra toda
//        if (file_exists($this->config->filepath.$subpath)) {
//            $this->url = $this->config->urlpath.$subpath;
//            $this->path = $this->config->filepath.$subpath;
//            $this->size = $tsize;
//            $this->type = 'userThumb';
//            $this->file = $user_image;
//            $file_explode = explode('.', $user_image);
//            $this->filename = $file_explode[1];
//            $this->ext = $file_explode[0];
//        }
//
//        return $this;
//    }
//
//    /**
//     * Salva uma foto nova do usuário
//     * @param int $user_id          id do usuário
//     * @param string $user_image    nome do arquivo do usuário
//     * @param $image                temporário da nova imagem
//     * @return boolean
//     */
//    public function setUserThumb($user_id, $user_image, $image) {
//        require_once 'image_utils.php';
//        $user_folder = $this->config->filepath.$this->getUserFolder($user_id, true);
//        // brinca com o nome
//        $ext = get_file_extension($image);
//        $filename = $this->genFilename();
//        $file = $filename.'.'.$ext;
//        // seta a porra toda
//        $this->ext = $ext;
//        $this->filename = $filename;
//        $this->file = $file;
//        $this->url = $this->config->urlpath.$user_folder;
//        $this->path = $this->config->filepath.$user_folder;
//        $this->size = null;
//        $this->type = 'userThumb';
//        // apaga imagem antiga
//        if (file_exists($user_folder.'50_'.$user_image)) unlink($user_folder.'50_'.$user_image);
//        if (file_exists($user_folder.'100_'.$user_image)) unlink($user_folder.'100_'.$user_image);
//        if (file_exists($user_folder.'200_'.$user_image)) unlink($user_folder.'200_'.$user_image);
//        // dá crop e resize na nova e salva
//        crop_image($image, $user_folder.'50_'.$file, 50);
//        crop_image($image, $user_folder.'100_'.$file, 100);
//        crop_image($image, $user_folder.'200_'.$file, 200);
//        // apaga o temporário
//        unlink($image);
//        return true;
//    }
//
//    /**
//     * Caminho para a pasta da empresa
//     * @param int $company_id   id da empresa
//     * @param boolean $mkdir    cria as pastas se não existir ainda
//     * @return string           caminho 
//     */
//    private function getCompanyFolder($company_id, $mkdir = false) {
//        // pega o hash da empresa
//        $hash = $this->companyHash($company_id);
//        // índice da empresa (1000 empresas por pasta)
//        $index = (int)($company_id/1000);
//        // monta o caminho
//        $folder = 'companies/'.$index.'/'.$hash.'/';
//        // cria as pastas
//        if ($mkdir) {
//            if (!file_exists($this->config->filepath.'companies/'.$index.'/'))
//                    mkdir($this->config->filepath.'companies/'.$index.'/');
//            if (!file_exists($this->config->filepath.'companies/'.$index.'/'.$hash.'/'))
//                    mkdir($this->config->filepath.'companies/'.$index.'/'.$hash.'/');
//            if (!file_exists($this->config->filepath.'companies/'.$index.'/'.$hash.'/products/'))
//                    mkdir($this->config->filepath.'companies/'.$index.'/'.$hash.'/products/');
//        }
//        return $folder;
//    }
//
//    /**
//     * Pega o logotipo da empresa
//     * @param int $company_id           id da empresa
//     * @param string $company_image     nome do arquivo do logotipo da empresa
//     * @param int $size                 tamanho da imagem em px
//     * @return Ee_UserFile              imagem 
//     */
//    public function getCompanyThumb($company_id, $company_image, $size = 200) {
//        if ($company_image == null) return false;
//        
//        // arredonda para 50, 100 ou 200 px
//        if ($size <= 50) $tsize = 50;
//        else if ($size <= 100) $tsize = 100;
//        else $tsize = 200;
//
//        // caminho para o arquivo
//        $subpath = $this->getCompanyFolder($company_id).$tsize.'_'.$company_image;
//
//        // se existir, seta a porra toda
//        if (file_exists($this->config->filepath.$subpath)) {
//            $this->url = $this->config->urlpath.$subpath;
//            $this->path = $this->config->filepath.$subpath;
//            $this->size = $tsize;
//            $this->type = 'companyThumb';
//            $this->file = $company_image;
//            $file_explode = explode('.', $company_image);
//            $this->filename = $file_explode[1];
//            $this->ext = $file_explode[0];
//        }
//        
//        return $this;
//    }
//
//    /**
//     * Salva um novo logotipo para a empresa
//     * @param int $company_id           id da empresa
//     * @param string $company_image     nome do arquivo da empresa
//     * @param $image                    temporário da nova imagem
//     * @return boolean
//     */
//    public function setCompanyThumb($company_id, $company_image, $image) {
//        require_once 'image_utils.php';
//        // pasta da empresa
//        $company_folder = $this->config->filepath.$this->getCompanyFolder($company_id, true);
//        // brinca com o nome
//        $ext = get_file_extension($image);
//        $filename = $this->genFilename();
//        $file = $filename.'.'.$ext;
//        // seta a porra toda
//        $this->ext = $ext;
//        $this->filename = $filename;
//        $this->file = $file;
//        $this->url = $this->config->urlpath.$company_folder;
//        $this->path = $this->config->filepath.$company_folder;
//        $this->size = null;
//        $this->type = 'companyThumb';
//        // apaga as imagens antigas
//        if (file_exists($company_folder.'50_'.$company_image)) unlink($company_folder.'50_'.$company_image);
//        if (file_exists($company_folder.'100_'.$company_image)) unlink($company_folder.'100_'.$company_image);
//        if (file_exists($company_folder.'200_'.$company_image)) unlink($company_folder.'200_'.$company_image);
//        // dá resize e grava as novas imagens
//        resize_image($image, $company_folder.'50_'.$file, 50);
//        resize_image($image, $company_folder.'100_'.$file, 100);
//        resize_image($image, $company_folder.'200_'.$file, 200);
//        // apaga o temporário
//        unlink($image);
//        return true;
//    }
//
//
//    /**
//     * Pega a imagem lateral da empresa
//     * @param int $company_id           id da empresa
//     * @param string $company_image     nome do arquivo da imagem da empresa
//     * @return Ee_UserFile              imagem 
//     */
//    public function getCompanySideImage($company_id, $company_image) {
//        if ($company_image == null) return false;
//        // caminho da imagem
//        $subpath = $this->getCompanyFolder($company_id).$company_image;
//
//        // se existir, seta a porratoda
//        if (file_exists($this->config->filepath.$subpath)) {
//            $this->url = $this->config->urlpath.$subpath;
//            $this->path = $this->config->filepath.$subpath;
//            $this->file = $company_image;
//            $file_explode = explode('.', $company_image);
//            $this->filename = $file_explode[1];
//            $this->ext = $file_explode[0];
//            $this->type = 'companySideImage';
//        }
//        
//        return $this;
//    }
//
//    /**
//     * Salva uma imagem lateral nova da empresa
//     * @param int $company_id           id da empresa
//     * @param string $company_image     nome do arquivo da empresa
//     * @param $image                    temporário da nova imagem
//     * @return boolean
//     */
//    public function setCompanySideImage($company_id, $old_image, $image) {
//        require_once 'image_utils.php';
//        // caminho da pasta da empresa
//        $company_folder = $this->config->filepath.$this->getCompanyFolder($company_id, true);
//        // brinca com o nome
//        $ext = get_file_extension($image);
//        $filename = $this->genFilename();
//        $file = $filename.'.'.$ext;
//        // seta a porra toda
//        $this->ext = $ext;
//        $this->filename = $filename;
//        $this->file = $file;
//        $this->url = $this->config->urlpath.$company_folder;
//        $this->path = $this->config->filepath.$company_folder;
//        $this->size = null;
//        $this->type = 'companySideImage';
//        // apaga a imagem antiga
//        if ($old_image && file_exists($company_folder.$old_image)) unlink($company_folder.$old_image);
//        // salva a nova imagem
//        copy($image, $company_folder.$file);
//        // apaga o temporário
//        unlink($image);
//        return true;
//    }
//
//    /**
//     * Pega a imagem do cartão da empresa
//     * @param int $company_id           id da empresa
//     * @param string $company_image     nome do arquivo da imagem da empresa
//     * @return Ee_UserFile              imagem 
//     */
//    public function getCompanyCardImage($company_id, $company_image) {
//        if ($company_image == null) return false;
//        // caminho da imagem
//        $subpath = $this->getCompanyFolder($company_id).$company_image;
//
//        // seta a porra toda
//        if (file_exists($this->config->filepath.$subpath)) {
//            $this->url = $this->config->urlpath.$subpath;
//            $this->path = $this->config->filepath.$subpath;
//            $this->file = $company_image;
//            $file_explode = explode('.', $company_image);
//            $this->filename = $file_explode[1];
//            $this->ext = $file_explode[0];
//            $this->type = 'companyCardImage';
//        }
//        
//        return $this;
//    }
//    
//    /**
//     * Salva uma imagem nova do cartão da empresa
//     * @param int $company_id           id da empresa
//     * @param string $company_image     nome do arquivo da empresa
//     * @param $image                    temporário da nova imagem
//     * @return boolean
//     */
//    public function setCompanyCardImage($company_id, $old_image, $image) {
//        require_once 'image_utils.php';
//        // caminho da pasta da empresa
//        $company_folder = $this->config->filepath.$this->getCompanyFolder($company_id, true);
//        // brinca com o nome
//        $ext = get_file_extension($image);
//        $filename = $this->genFilename();
//        $file = $filename.'.'.$ext;
//        // seta a porra toda
//        $this->ext = $ext;
//        $this->filename = $filename;
//        $this->file = $file;
//        $this->url = $this->config->urlpath.$company_folder;
//        $this->path = $this->config->filepath.$company_folder;
//        $this->size = null;
//        $this->type = 'companyCardImage';
//        // apaga imagem antiga
//        if ($old_image && file_exists($company_folder.$old_image)) unlink($company_folder.$old_image);
//        // salva a nova imagem
//        copy($image, $company_folder.$file);
//        // apaga o temporário
//        unlink($image);
//        return true;
//    }
//
//    /**
//     * Pega a fotenha do produto
//     * @param int $company_id           id da empresa
//     * @param int $product_id           id do produto
//     * @param string $product_image     nome do arquivo do produto
//     * @param int $size                 tamanho da imagem em px
//     * @return Ee_UserFile              imagem 
//     */
//    public function getProductThumb($company_id, $product_id, $product_image, $size = 200) {
//        if ($product_image == null) return false;
//
//        // arredonda para 50, 100 ou 200
//        if ($size <= 50) $tsize = 50;
//        else if ($size <= 100) $tsize = 100;
//        else $tsize = 200;
//
//        // caminho para o arquivo
//        $subpath = $this->getCompanyFolder($company_id).'/products/'.$tsize.'_'.$product_image;
//
//        // se existe, seta a porra toda
//        if (file_exists($this->config->filepath.$subpath)) {
//            $this->url = $this->config->urlpath.$subpath;
//            $this->path = $this->config->filepath.$subpath;
//            $this->size = $tsize;
//            $this->type = 'productThumb';
//            $this->file = $product_image;
//            $file_explode = explode('.', $product_image);
//            $this->filename = $file_explode[1];
//            $this->ext = $file_explode[0];
//        }
//
//        return $this;
//    }
//
//    /**
//     * Salva nova imagem do produto
//     * @param int $company_id           id da empresa
//     * @param int $product_id           id do produto
//     * @param string $old_image         nome do arquivo antigo
//     * @param $image                    temporário do novo arquivo
//     * @return boolean
//     */
//    public function setProductThumb($company_id, $product_id, $old_image, $image) {
//        require_once 'image_utils.php';
//        // caminho para a pasta de produtos
//        $products_folder = $this->config->filepath.$this->getCompanyFolder($company_id, true).'products/';
//        // brinca com os nomes
//        $ext = get_file_extension($image);
//        $filename = $this->genFilename();
//        $file = $filename.'.'.$ext;
//        // seta a porra toda
//        $this->ext = $ext;
//        $this->filename = $filename;
//        $this->file = $file;
//        $this->url = $this->config->urlpath.$products_folder;
//        $this->path = $this->config->filepath.$products_folder;
//        $this->size = null;
//        $this->type = 'productThumb';
//        // apaga os arquivos antigos
//        if (file_exists($products_folder.'50_'.$old_image)) unlink($products_folder.'50_'.$old_image);
//        if (file_exists($products_folder.'100_'.$old_image)) unlink($products_folder.'100_'.$old_image);
//        if (file_exists($products_folder.'200_'.$old_image)) unlink($products_folder.'200_'.$old_image);
//        // resizeia e salva as novas imagens
//        resize_image($image, $products_folder.'50_'.$file, 50);
//        resize_image($image, $products_folder.'100_'.$file, 100);
//        resize_image($image, $products_folder.'200_'.$file, 200);
//        // apaga o temporário
//        unlink($image);
//        return true;
//    }
//
//
//    /**
//     * Pega uma imagem da galeria de imagens do produto
//     * @param int $company_id           id da empresa
//     * @param int $product_id           id do produto
//     * @param string $product_image     nome do arquivo do produto
//     * @param int|string $size          tamanho da imagem em px
//     * @return Ee_UserFile              imagem 
//     */
//    public function getProductImage($company_id, $product_id, $product_image, $size = 200) {
//        if ($product_image == null) return false;
//        
//        // arredonda para full ou 50, 100 ou 200 px
//        if ($size == 'full') $tsize = 'full';
//        else if ($size <= 50) $tsize = 50;
//        else if ($size <= 100) $tsize = 100;
//        else $tsize = 200;
//
//        // caminho para o arquivo da imagem
//        $subpath = $this->getCompanyFolder($company_id).'/products/'.$tsize.'_'.$product_image;
//
//        // se existe seta a porra toda
//        if (file_exists($this->config->filepath.$subpath)) {
//            $this->url = $this->config->urlpath.$subpath;
//            $this->path = $this->config->filepath.$subpath;
//            $this->size = $tsize;
//            $this->type = 'productImage';
//            $this->file = $product_image;
//            $file_explode = explode('.', $product_image);
//            $this->filename = $file_explode[1];
//            $this->ext = $file_explode[0];
//        }
//
//        return $this;
//    }
//
//
//    /**
//     * Salva nova imagem da galeria de imagens do produto
//     * @param int $company_id           id da empresa
//     * @param int $product_id           id do produto
//     * @param string $old_image         nome do arquivo antigo
//     * @param $image                    temporário do novo arquivo
//     * @param int $maxwidth             largura máxima em px
//     * @param int $maxheight            altura máxima em px
//     * @return boolean
//     */
//    public function setProductImage($company_id, $product_id, $old_image, $image, $maxwidth = 800, $maxheight = 600) {
//        require_once 'image_utils.php';
//        // caminho para a pasta dos produtos
//        $products_folder = $this->config->filepath.$this->getCompanyFolder($company_id, true).'products/';
//        // brinca com o nome
//        $ext = get_file_extension($image);
//        $filename = $this->genFilename();
//        $file = $filename.'.'.$ext;
//        // seta a porra toda
//        $this->ext = $ext;
//        $this->filename = $filename;
//        $this->file = $file;
//        $this->url = $this->config->urlpath.$products_folder;
//        $this->path = $this->config->filepath.$products_folder;
//        $this->size = null;
//        $this->type = 'productImage';
//        // apaga imagem antiga
//        if ($old_image && file_exists($products_folder.$old_image)) unlink($products_folder.$old_image);
//        // resizeia e salva as novas imagens
//        resize_image($image, $products_folder.'50_'.$file, 50);
//        resize_image($image, $products_folder.'100_'.$file, 100);
//        resize_image($image, $products_folder.'200_'.$file, 200);
//        scale_image($image, $products_folder.'full_'.$file, 0, 900);
//        // apaga o temporário
//        unlink($image);
//        return true;
//    }
//}
