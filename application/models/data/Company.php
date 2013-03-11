<?php
/**
 * Company.php - Ee_Model_Data_Company
 * Representação dos dados de uma empresa
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once APPLICATION_PATH.'/../library/components/UserFile/UserFile.php';
require_once 'Data.php';

class Ee_Model_Data_Company extends Ee_Model_Data
{
    /**
     * ID da empresa
     * @var int
     */
    public $id;
    /**
     * ID do setor de atuação da empresa
     * @var int
     */
    public $sector_id;
    /**
     * Setor de atuação da empresa
     * @var Ee_Model_Data_Sector
     */
    public $sector;
    /**
     * ID da cidade onde a empresa está
     * @var int
     */
    public $city_id;
    /**
     * Cidade onde a empresa está
     * @var Ee_Model_Data_City
     */
    public $city;
    /**
     * Reputação da empresa (ainda não está sendo usada)
     * @var int
     */
    public $reputation;
    /**
     * Nome da empresa
     * @var string
     * @example "Nome da Empresa"
     */
    public $name;
    /**
     * Slug da empresa
     * @var string
     * @example "nome-da-empresa"
     */
    public $slug;
    /**
     * Data em que a empresa foi criada
     * @var datetime
     * @example "2010-01-01 12:34"
     */
    public $date_created;
    /**
     * Data em que a empresa foi atualizada
     * @var datetime
     * @example "2010-01-01 12:34"
     */
    public $date_updated;
    /**
     * Plano em que a empresa assinou
     * @var string
     * @example "gratis" e "premium"
     */
    public $plan;
    /**
     * Data de expiração do plano
     * @var date
     * @example "2010-01-01"
     */
    public $plan_expiration;
    /**
     * Tipo da empresa
     * @var string
     * @example "freelancer" para autônomos
     * @example "company" para empresas
     */
    public $type;
    /**
     * Perfil da empresa
     * @var string
     * @example "buyer" para compradores
     * @example "seller" para vendedores
     * @example "all" para ambos
     */
    public $profile;
    /**
     * Status da empresa
     * @var string
     * @example "active" ou "deleted"
     */
    public $status;
    /**
     * Nome do arquivo do logotipo
     * @var string
     * @example "saf98xc2fs.jpg"
     */
    public $image;
    /**
     * Nome do arquivo da imagem do cartão
     * @var string
     * @example "kiu29weusa.jpg"
     */
    public $card_image;
    /**
     * Nome do arquivo da imagem lateral
     * @var string
     * @example "b4tmanftw0.jpg"
     */
    public $side_image;
    /**
     * Atividade exercida pela empresa
     * @var string
     * @example "Varejo de Pastéis"
     */
    public $activity;
    /**
     * Descrição da empresa
     * @var string
     * @example "Venda de pastéis nos mais diversos sabores doces e salgados."
     */
    public $description;
    /**
     * Telefone da empresa
     * @var string
     * @example "55 12 34567890"
     */
    public $phone;
    /**
     * Segundo telefone da empresa
     * @var string
     * @example "55 23 45678901"
     */
    public $phone2;
    /**
     * Email de contato da empresa
     * @var string
     * @example "contato@site-da-empresa.com.br"
     */
    public $email;
    /**
     * Site da empresa
     * @var string
     * @example "www.site-da-empresa.com.br"
     */
    public $website;
    /**
     * Nome da rua onde a empresa está
     * @var string
     */
    public $address_street;
    /**
     * Número do edifício que a empresa está
     * @var string
     */
    public $address_number;
    /**
     * Complemento do endereço
     * @var string
     */
    public $address_complement;
    /**
     * Texto informativo sobre a empresa
     * @var string
     */
    public $about;
    /**
     * URL dos slides (slideshare)
     * @var string
     */
    public $slides_url;
    /**
     * Código embed html dos slides
     * @var string
     */
    public $slides_embed;
    /**
     * URL do vídeo sobre a empresa (youtube, vímeo e videolog)
     * @var string
     */
    public $video_url;
    /**
     * URL do blog
     * @var string
     */
    public $link_blog;
    /**
     * URL do canal no youtube
     * @var string
     */
    public $link_youtube;
    /**
     * URL do canal no vimeo
     * @var string
     */
    public $link_vimeo;
    /**
     * URL do slideshare
     * @var string
     */
    public $link_slideshare;
    /**
     * URL do perfil no Facebook
     * @var string
     */
    public $link_facebook;
    /**
     * Contato no twitter
     * @var string
     */
    public $contact_twitter;
    /**
     * Contato no skype
     * @var string
     */
    public $contact_skype;
    /**
     * Contato no msn
     * @var string
     */
    public $contact_msn;
    /**
     * Contato no gtalk
     * @var string
     */
    public $contact_gtalk;


    /**
     * Caminho para a imagem da empresa
     * @param int $size         tamanho em px da imagem
     * @return string           caminho para o logo da empresa
     * @author Mauro Ribeiro
     * @since 2011-06 
     */
    public function imagePath($size = 200) {
        $userfile = new Ee_UserFile();
        $userfile->getCompanyThumb($this->id, $this->image, $size);
        return $userfile->url;
    }

    /**
     * Tag html do logotipo da empresa
     * @param int $size         tamanho em px da imagem
     * @return string           tag <img> do logo da empresa
     * @author Mauro Ribeiro
     * @since 2011-06 
     */
    public function image($size = 200) {
        $userfile = new Ee_UserFile();
        $userfile->getCompanyThumb($this->id, $this->image, $size);

        // se tem imagem
        if ($userfile->url)
            $image = '<img src="'.$userfile->url.'" width="'.$size.'" height="'.$size.'" />';
        // se não tem imagem
        else
            $image = '<img src="images/ui/no_company_thumb.gif" width="'.$size.'" height="'.$size.'" />';

        return $image;
    }


    /**
     * Link para o perfil da empresa
     * @return string       url para o perfil da empresa
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function url() {

        $router = Zend_Controller_Front::getInstance()->getRouter();

        $url    = $router->assemble(
                array(
                'company_id' => $this->slug,
                ),
                'company_view'
            );

        return $url;
    }


    /**
     * Logotipo da empresa com link para o perfil da empresa
     * @param int $size     tamanho da imagem em px
     * @return string       tag <img> com o link para o perfil da empresa
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function imageLink($size = 200) {
        $output = '<a href="'.$this->url().'" target="_top" >'.$this->image($size).'</a>';
        return $output;
    }

    /**
     * Verifica se a empresa está em um plano. Atualiza banco de dados de acordo
     * com a data de expiração
     * @param string $plan_name     Nome do plano ("gratis" ou "premium")
     * @return boolean           
     * @author Mauro Ribeiro
     * @since 2011-06 
     */
    public function isPlan($plan_name) {
        $plan = false;
        
        if ($this->plan == "gratis" && $plan_name == "gratis") $plan = true;

        if ($this->plan == $plan_name) {
            if (strtotime($this->plan_expiration) > time()) {
                $plan = true;
            }
            else {
                $data->id = $this->id;
                $data->plan = 'gratis';
                $mapper = new Ee_Model_Companies();
                $mapper->save($data);
            }
        }

        return $plan;
    }

    /**
     * Tag <img> da imagem lateral da empresa
     * @return string           tag <img> da imagem lateral da empresa
     * @author Mauro Ribeiro
     * @since 2011-06 
     */
    public function premiumSideImage() {
        $userfile = new Ee_UserFile();
        $userfile->getCompanySideImage($this->id, $this->side_image);

        if ($userfile->url)
            $image = '<img src="'.$userfile->url.'" />';
        else
            $image = false;

        return $image;
    }


    /**
     * Tag <img> da imagem do cartão
     * @return string           tag <img> da imagem lateral do cartão
     * @author Mauro Ribeiro
     * @since 2011-06 
     */
    public function premiumCardImage() {
        $userfile = new Ee_UserFile();
        $userfile->getCompanyCardImage($this->id, $this->card_image);

        if ($userfile->url)
            $image = '<img src="'.$userfile->url.'" />';
        else
            $image = false;

        return $image;
    }


    /**
     * Status do contato entre um usuário e uma empresa
     * @return string           "contact", "sender", "receiver", "same"
     * @return boolean          false caso nenhum relacionamento
     * @author Mauro Ribeiro
     * @since 2011-06 
     */
    public function contactStatus($user_id, $company_id = null) {
        if ($company_id == $this->id) return 'same';
        $contact_mapper = new Ee_Model_Contacts();
        return $contact_mapper->companyStatus($this->id, $user_id);
    }


}

