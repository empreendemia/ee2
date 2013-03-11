<?php
/**
 * EmailConfirmation.php - Ee_Validate_EmailConfirmation
 * Valida se o email que o cara digitou duas vezes são iguais
 * 
 * @author Mauro Ribeiro
 * @since 2011-07
 */
require_once 'Zend/Validate/Abstract.php';

class Ee_Validate_EmailConfirmation extends Zend_Validate_Abstract {
    const NOT_MATCH = 'emailConfirmationNotMatch';

    /**
     * Mensagens em caso de dados inválidos
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Email confirmation does not match'
    );

    /**
     * O outro campo que tem que ser igual ao elemento atual
     * @var array
     */
    protected $_fieldsToMatch = array();

    /**
     * Construtor do validador
     * @param array|string $fieldsToMatch
     */
    public function __construct($fieldsToMatch = array()) {
        if (is_array($fieldsToMatch)) {
            foreach ($fieldsToMatch as $field) {
                $this->_fieldsToMatch[] = (string) $field;
            }
        } else {
            $this->_fieldsToMatch[] = (string) $fieldsToMatch;
        }
    }

    /**
     * Verifica se o elemento é válido. Se os dois forem iguais, show!
     * @param $value string         valor da bacaça
     * @param array $context        outros elementos do formulário
     * @return boolean 
     */
    public function isValid($value, $context = null) {
        $value = (string) $value;
        $this->_setValue($value);

        $error = false;

        foreach ($this->_fieldsToMatch as $fieldName) {
            if (!isset($context[$fieldName]) || $value !== $context[$fieldName]) {
                $error = true;
                $this->_error(self::NOT_MATCH);
                break;
            }
        }

        return !$error;
    }
}
