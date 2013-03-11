<?php
/**
 * User.php - Ee_Model_Data_User
 * Representação dos dados do usuário
 * 
 * @package models
 * @subpackage data
 * @author Mauro Ribeiro
 * @since 2011-06
 */
require_once 'Data.php';
require_once APPLICATION_PATH.'/../library/components/UserFile/UserFile.php';

class Ee_Model_Data_User extends Ee_Model_Data
{
    /**
     * ID do usuário
     * @var int
     */
    public $id;
    /**
     * ID da empresa que o usuário pertence
     * @var int
     */
    public $company_id;
    /**
     * Empresa que o usuário pertence
     * @var Ee_Model_Data_Company
     */
    public $company;
    /**
     * Email de login do usuário
     * @var string
     * @example "nome-do-usuario@nome-da-empresa.com.br"
     */
    public $login;
    /**
     * Senha do usuário 
     * @var string
     */
    public $password;
    /**
     * Grupo em que o usuário se encontra
     * @var string
     * @example "deleted", "unconfirmed", "user" e "admin
     */
    public $group;
    /**
     * Data de cadastro do usuário
     * @var datetime
     * @example "2010-01-01 00:00"
     */
    public $date_created;
    /**
     * Data de atualização dos dados do usuário
     * @var datetime
     * @example "2010-01-01 00:00"
     */
    public $date_updated;
    /**
     * Opções do usuário
     * @var string
     * @deprecated
     */
    public $options = '1';
    /**
     * Emails que o usuário deseja receber
     * @var string
     * @example "1000" deseja receber newsletter
     */
    public $mails = '1111';
    /**
     * Nome do usuário
     * @var string
     * @example "João"
     */
    public $name;
    /**
     * Sobrenome
     * @var string
     * @example "Da Silva" 
     */
    public $family_name;
    /**
     * Nome do arquivo da imagem do usuário
     * @var string
     * @example "ol4tud0b3m.jpg"
     */
    public $image;
    /**
     * Descrição do usuário
     * @var string
     */
    public $description;
    /**
     * Cargo do usuário na empresa
     * @var string
     * @example "Diretor de Marketing"
     */
    public $job;
    /**
     * Telefone do usuário
     * @var string
     * @example "55 11 12345678"
     */
    public $phone;
    /**
     * Celular do usuário
     * @var string
     * @example "55 11 98765432"
     */
    public $cell_phone;
    /**
     * Email de contato do usuário
     * @var string
     * @example "nome-do-usuario@nome-da-empresa.com.br" 
     */
    public $email;

    /**
     * Nome completo do usuário
     * @return string   nome completo do camarada
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function fullName() {
        return $this->name.' '.$this->family_name;
    }

    /**
     * Caminho para imagem do usuário
     * @param int $size     tamanho da imagem > 0 em px
     * @return string       caminho da imagem do brother
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function imagePath($size = 200) {
        $userfile = new Ee_UserFile(array('type'=>'userThumb','id'=>$this->id,'image'=>$this->image,'size'=>$size));
        return $userfile->url;
    }

    /**
     * Tag <img> da foto do usuário
     * @param int $size     tamanho da imagem > 0 em px
     * @return string       tag <img src> da imagem do camarada
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function image($size = 200) {
        $userfile = new Ee_UserFile(array('type'=>'userThumb','id'=>$this->id,'image'=>$this->image,'size'=>$size));

        if ($userfile->url)
            $image = '<img src="'.$userfile->url.'" width="'.$size.'" height="'.$size.'" />';
        else
            $image = '<img src="images/ui/no_user_thumb.gif" width="'.$size.'" height="'.$size.'" />';

        return $image;
    }

    /**
     * URL para o perfil do usuário
     * @return string       url para o perfil do camarada
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function url() {

        $router = Zend_Controller_Front::getInstance()->getRouter();

        $url    = $router->assemble(
                array(
                'company_id' => $this->company->slug,
                'user_id' => $this->id
                ),
                'company_member'
            );

        return $url;
    }

    /**
     * Tag <img> com o link para o perfil do cara
     * @param int $size     tamanho da imagem > 0 em px
     * @return string       tag <img> com o link para o perfil do camarada
     * @author Mauro Ribeiro
     * @since 2011-06
     */
    public function imageLink($size = 200) {
        $output = '<a href="'.$this->url().'" target="_top" >'.$this->image($size).'</a>';
        return $output;
    }

    /**
     * Tipo de relação que este usuário tem com outro
     * @param type $user_id     id do outro usuário
     * @return string           "self", "contact", "sender", "receiver"
     * @return boolean          false
     */
    public function contactStatus($user_id) {
        $contact_mapper = new Ee_Model_Contacts();
        return $contact_mapper->status($this->id, $user_id);
    }

}

