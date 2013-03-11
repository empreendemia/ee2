<?php
/**
 * UniqueLogin.php - Ee_Validate_UniqueLogin
 * Verifica se o login que o cara escreveu já está registrado no sistema
 * 
 * @author Mauro Ribeiro
 * @since 2011-07
 */
require_once 'Zend/Validate/Abstract.php';

class Ee_Validate_UniqueLogin extends Zend_Validate_Abstract {
    const NOT_MATCH = 'uniqueLoginNotMatch';

    /**
     * Mensagens em caso de dados inválidos
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Este email já está cadastrado'
    );


    /**
     * Construtor do validador
     */
    public function __construct() {


    }

    /**
     * Verifica se o login que o cara digitou já está cadastrado
     * @param string $value         login que o cara digitou
     * @param type $context         outros elementos do formulário
     * @return boolean
     */
    public function isValid($value, $context = null) {
        $value = (string) $value;
        $this->_setValue($value);

        // verifica se o login está disponível
        $user_mapper = new Ee_Model_Users();
        $available = $user_mapper->isLoginAvailable($value);

        if ($available) {
            return true;
        }
        else {
            $this->_error(self::NOT_MATCH);
            return false;
        }
    }
}
