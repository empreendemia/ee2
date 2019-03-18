<?php
/**
 * UniqueCNPJ.php - Ee_Validate_UniqueCNPJ
 * Verifica se o login que o cara escreveu já está registrado no sistema
 * 
 * @author Mauro Ribeiro
 * @since 2018-07
 */
require_once 'Zend/Validate/Abstract.php';

class Ee_Validate_UniqueCNPJ extends Zend_Validate_Abstract {
    const NOT_MATCH = 'uniqueCNPJNotMatch';

    /**
     * Mensagens em caso de dados inválidos
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Este CNPJ já está cadastrado'
    );


    /**
     * Construtor do validador
     */
    public function __construct() {


    }

    /**
     * Verifica se o CNPJ que o cara digitou já está cadastrado
     * @param string $value         CNPJ que o cara digitou
     * @param type $context         outros elementos do formulário
     * @return boolean
     */
    public function isValid($value, $context = null) {
        $value = (string) $value;
        $this->_setValue($value);

        // verifica se o login está disponível
        $company_mapper = new Ee_Model_Companies();
        $available = $company_mapper->isCNPJAvailable($value);

        if ($available) {
            return true;
        }
        else {
            $this->_error(self::NOT_MATCH);
            return false;
        }
    }
}
