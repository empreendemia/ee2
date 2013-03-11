<?php
/**
 * Product.php - Ee_Model_Data_Product
 * Representação dos dados do produto
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once 'Data.php';
require_once APPLICATION_PATH.'/../library/components/UserFile/UserFile.php';

class Ee_Model_Data_Product extends Ee_Model_Data
{
    /**
     * ID do produto
     * @var int
     */
    public $id;
    /**
     * ID da empresa dona do produto
     * @var int
     */
    public $company_id;
    /**
     * Empresa dona do produto
     * @var Ee_Model_Data_Company
     */
    public $company;
    /**
     * Slug do produto
     * @var string
     * @example "nome-do-produto"
     */
    public $slug;
    /**
     * Nome do produto
     * @var string
     * @example "Nome do produto"
     */
    public $name;
    /**
     * Data de criação do produto
     * @var datetime
     * @example "2010-01-01 01:01"
     */
    public $date_created;
    /**
     * Data de atualização dos dados do produto
     * @var datetime
     * @example "2010-01-01 01:01"
     */
    public $date_updated;
    /**
     * Descrição do produto
     * @var string
     */
    public $description;
    /**
     * Site do produto
     * @var string
     */
    public $website;
    /**
     * Nome do arquivo da imagem do produto
     * @var string
     * @example "aeu44weusa.jpg"
     */
    public $image;
    /**
     * Ordem em que o produto deve aparecer na vitrine
     * @var int
     */
    public $sort;
    /**
     * Texto informativo sobre o produto
     * @var string
     */
    public $about;
    /**
     * Nome do arquivo da imagem da galeria de imagens do produto
     * @var string
     * @example "aeu44weusa.jpg"
     */
    public $image_1;
    /**
     * Nome do arquivo da imagem da galeria de imagens do produto
     * @var string
     * @example "aeu44weusa.jpg"
     */
    public $image_2;
    /**
     * Nome do arquivo da imagem da galeria de imagens do produto
     * @var string
     * @example "aeu44weusa.jpg"
     */
    public $image_3;
    /**
     * Nome do arquivo da imagem da galeria de imagens do produto
     * @var string
     * @example "aeu44weusa.jpg"
     */
    public $image_4;
    /**
     * Nome do arquivo da imagem da galeria de imagens do produto
     * @var string
     * @example "aeu44weusa.jpg"
     */
    public $image_5;
    /**
     * Legenda da $image_1
     * @var string 
     */
    public $subtitle_1;
    /**
     * Legenda da $image_2
     * @var string 
     */
    public $subtitle_2;
    /**
     * Legenda da $image_3
     * @var string 
     */
    public $subtitle_3;
    /**
     * Legenda da $image_4
     * @var string 
     */
    public $subtitle_4;
    /**
     * Legenda da $image_5
     * @var string 
     */
    public $subtitle_5;
    /**
     * Status da oferta do produto
     * @var string 
     * @example "active" ou "inactive"
     */
    public $offer_status;
    /**
     * Descrição da oferta
     * @var string 
     */
    public $offer_description;
    /**
     * Data do cadastro da oferta
     * @var datetime
     */
    public $offer_date_created;
    /**
     * Data do prazo da oferta
     * @var date
     */
    public $offer_date_deadline;

    /**
     * Caminho para a imagem do produto
     * @param int $size         tamanho em px da imagem
     * @return string           caminho para o logo do produto
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function imagePath($size = 200, $image_index = false) {
        $userfile = new Ee_UserFile();
        // imagem em destaque do produto
        if (!$image_index) {
            $userfile->getProductThumb($this->company_id, $this->id, $this->image, $size);

            if ($userfile->url) return $userfile->url;
            else $image = false;
        }
        // imagens da galeria de imagens
        else {
            $index = 'image_'.$image_index;
            $userfile->getProductImage($this->company_id, $this->id, $this->$index, $size);

            if ($userfile->url) {
                if ($size == 'full') return $userfile->url;
                else return false;
            }
            else
                return false;
        }
    }

    /**
     * Tag <img> do produto da imagem
     * @param int $size         tamanho da imagem > 0
     * @param int $image_index  indice da imagem para produtos premium
     * @return string           caminho da imagem do produto
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function image($size = 200, $image_index = false) {
        $userfile = new Ee_UserFile();
        // imagem em destaque
        if (!$image_index) {
            $userfile->getProductThumb($this->company_id, $this->id, $this->image, $size);

            if ($userfile->url)
                $image = '<img src="'.$userfile->url.'" width="'.$size.'" height="'.$size.'" />';
            else
                $image = '<img src="images/ui/no_product_thumb.gif" width="'.$size.'" height="'.$size.'" />';

            return $image;

        }
        // galeria de imagens
        else {
            $index = 'image_'.$image_index;
            $userfile->getProductImage($this->company_id, $this->id, $this->$index, $size);

            if ($userfile->url) {
                if ($size == 'full') return '<img src="'.$userfile->url.'" />';
                else return '<img src="'.$userfile->url.'" width="'.$size.'" height="'.$size.'" />';
            }
            else
                return false;
        }
    }


    /**
     * URL da página do produto
     * @return string       url para o perfil do produto
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function url() {

        $router = Zend_Controller_Front::getInstance()->getRouter();

        $url    = $router->assemble(
                array(
                'company_id' => $this->company->slug,
                'product_id' => $this->slug
                ),
                'company_product'
            );

        return $url;
    }


    /**
     * Tag <img> do produto com link para a página do produto
     * @param int $size     tamanho da imagem > 0
     * @return string       tag <img> com o link para o produto
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function imageLink($size = 200) {
        $output = '<a href="'.$this->url().'">'.$this->image($size).'</a>';
        return $output;
    }


    /**
     * Verifica se um produto está em oferta
     * @return boolean
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function isOffer() {
        if ($this->offer_status == 'active') {
            $deadline = strtotime($this->offer_date_deadline);
            if ($deadline > time())
                return true;
            else
                return false;
        }
        else
            return false;
    }

}

