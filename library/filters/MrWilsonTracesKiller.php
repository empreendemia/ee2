<?php
/**
 * MrWilsonTracesKiller.php - Ee_Filter_MrWilsonTracesKiller
 * Filtro contra traços do Mr. Wilson (por exemplo, CAPS LOCK)
 * 
 * @author Mauro Ribeiro
 * @since 2011-09
 */
class Ee_Filter_MrWilsonTracesKiller implements Zend_Filter_Interface
{
    /**
     * Tipo do texto que o Sr. Wilson escreveu
     * @var string
     * @example "name", "title"
     */
    private $type = 'person';
    
    /**
     * Só filtra se tiver tudo em capslock?
     * @var boolean
     */
    private $capslock = true;

    /**
     * Número mínimo de termos para executar o filtro
     * @var int
     */
    private $min_terms = 1;

    /**
     * número máximo de letras numa palavra para deixá-la toda em minúscula
     * @var int
     */
    private $max_lower = 2;

    /**
     * Termos que fazem sentido manter em caixa alta (por exemplo siglas)
     * @var array(string)
     */
    private $uppercase_list = array(
        'RH', 'ME', 'TI', 'RI', 'BR', 'SA', 'S/A', 'CEO', 'CTO', 'CFO'
    );

    /**
     * Transforma uma string em tudo caixa baixa
     * @param string $texto         TexTo DO CaRa 
     * @return string               texto do cara
     */
    public function lowerCase($texto){
        //Letras minúsculas com acentos
        $texto = strtolower($texto);
        //$texto = strtr($texto, "ĄĆĘŁŃÓŚŹŻABCDEFGHIJKLMNOPRSTUWYZQXVЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮÂÀÁÄÃÊÈÉËÎÍÌÏÔÕÒÓÖÛÙÚÜÇ", "ąćęłńóśźżabcdefghijklmnoprstuwyzqxvёйцукенгшщзхъфывапролджэячсмитьбюâàáäãêèéëîíìïôõòóöûùúüç");
        return $texto;
    }

    /**
     * Construtor do filtro
     * @param type $options 
     */
    public function __construct($options = null)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else if (!is_array($options)) {
            $options = func_get_args();
            $temp    = array();
            if (!empty($options)) $temp['type'] = array_shift($options);
            if (!empty($options)) $temp['capslock'] = array_shift($options);
            if (!empty($options)) $temp['min_terms'] = array_shift($options);
            if (!empty($options)) $temp['max_lower'] = array_shift($options);
            if (!empty($options)) $temp['uppercase_list'] = array_shift($options);
            $options = $temp;
        }

        foreach ($options as $id => $option) {
            $this->$id = $options[$id];
        }
    }
 
    /**
     * Filtro do input do Mr. Wilson
     * @param string $value
     * @return string
     */
    public function filter($value)
    {

        // quebra os termos
        $terms = explode(' ',$value);
        $n_terms = count($terms);
        $new_terms = array();
        $html_decoded = strip_tags(html_entity_decode($value));

        // se a opção $capslock está ligada e o texto está em CAPS LOCK
        // ou se a opção $capslock está desligada 
        if (isset($value) && $value != '' && ($this->capslock == true && (strtoupper($value) == $value || strtoupper($html_decoded) == $html_decoded)) || $this->capslock == false) {
            // se tem mais termos que o mínimo
            if ($n_terms >= $this->min_terms) {
                // Em caso de nome
                // JOAO -> Joao
                // joao -> Joao
                // DA SILVA -> da Silva
                // da silva -> da Silva
                if ($this->type == 'name') {
                    // verifica cada termo
                    foreach ($terms as $term) {
                        // se o termo tem mais do que o mínimo de letras permitido
                        if (strlen($term) > $this->max_lower) {
                            // diminui tudo
                            $new_term = $this->lowerCase($term);
                            // aumenta a primeira
                            $new_term[0] = strtoupper($new_term[0]);
                        }
                        else {
                            // diminui tudo
                            $new_term = $this->lowerCase($term);
                        }
                        $new_terms[] = $new_term;
                    }
                }
                // Em caso de títulos
                // WEBDESIGN CORPORATIVO -> Webdesign Corporativo
                // SOLUCOES EM TI -> Solucoes em TI
                // RAD -> RAD (até 4 letras)
                else if ($this->type == 'title') {
                    // verifica cada termo
                    foreach ($terms as $term) {
                        // se a palavra está na lista de permitidos, deixa assim mesmo
                        if (in_array($term, $this->uppercase_list)) {
                            $new_term = $term;
                        }
                        // se a palavra tem mais caracteres do que o máximo permitido
                        else if (strlen($term) > $this->max_lower) {
                            // diminui tudo
                            $new_term = $this->lowerCase($term);
                            // aumenta a primeira
                            $new_term[0] = strtoupper($new_term[0]);
                        }
                        // deixa por assim mesmo
                        else {
                            $new_term = $this->lowerCase($term);
                        }
                        $new_terms[] = $new_term;
                    }
                }
                // Apenas a primeira letra de cada frase em maiúscula.
                else {
                    foreach ($terms as $term) {
                        $new_terms[] = $this->lowerCase($term);
                    }
                    $new_terms[0][0] = strtoupper($new_terms[0][0]);
                }
                return implode(' ', $new_terms);
            }
            else {
                return $value;
            }
        }
        else {
            return $value;
        }
    }

}