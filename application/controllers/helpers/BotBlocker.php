<?php
/**
 * Access.php - Ee_Controller_Helper_Access
 * Helper para ferramentas de bloqueio de bots
 * 
 * @package controllers
 * @subpackage helpers
 * @author Mauro Ribeiro
 * @since 2011-09
 */
class Ee_Controller_Helper_BotBlocker extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * Inicia o timer 
     * @author Mauro Ribeiro
     * @since 2011-09
     */
    public function timeStart() {
        $userdata = new Zend_Session_Namespace('UserData');
        $userdata->botblocker->form_start = time();
    }

    /**
     * Finaliza o timer 
     * @author Mauro Ribeiro
     * @since 2011-09
     */
    public function timeFinish() {
        $userdata = new Zend_Session_Namespace('UserData');
        $userdata->botblocker->form_finish = time();
    }

    /**
     * Verifica se o cara preencheu o formulário num tempo razoável. Se
     * preencheu muito rápido, bloqueia
     * @param int $time_check       tempo mínimo em segundos
     * @author Mauro Ribeiro
     * @since 2011-09
     */
    public function timeCheck($time_check = 15) {
        $userdata = new Zend_Session_Namespace('UserData');
        if (isset($userdata->botblocker->form_start) && isset($userdata->botblocker->form_finish)) {
            $time = $userdata->botblocker->form_finish - $userdata->botblocker->form_start;
            if ($time <= $time_check) {
                $userdata->botblocker->block = true;
                return false;
            }
        }
        $userdata->botblocker->block = false;
        return true;
    }

    /**
     * Verifica se o cara está mandando a mesma mensagem
     * @author Mauro Ribeiro
     * @since 2015-02
     */
    public function sameMessage($message) {
        $userdata = new Zend_Session_Namespace('UserData');
        if (isset($userdata->botblocker->same_message)) {
            if ($userdata->botblocker->same_message == $message) {
                return true;
            }
        }     
        $userdata->botblocker->same_message = $message;
        return false;
    }


    /**
     * Bloqueia o bot e envia mensagem para administradores da rede
     * @author Mauro Ribeiro
     * @since 2011-09
     */
    public function block() {
        // seta flag de bloqueio na sessão do cara
        $userdata = new Zend_Session_Namespace('UserData');
        $userdata->botblocker->block = true;
        
        // envia mensagem para admins
        $EeMsg = Zend_Controller_Action_HelperBroker::getStaticHelper('EeMsg');
        $message = '<h1>Spammer safado foi bloqueado</h1>';

        ob_start();
        print_r($_GET);
        $message .= '<h2>GET</h2><pre>'.ob_get_clean().'</pre>';

        ob_start();
        print_r($_POST);
        $message .= '<h2>POST</h2><pre>'.ob_get_clean().'</pre>';

        ob_start();
        print_r($_SESSION);
        $message .= '<h2>SESSION</h2><pre>'.ob_get_clean().'</pre>';

        $EeMsg->adminsEmail('Spammer bloqueado',$message);
        die("eee");
    }


    /**
     * Adiciona filtro de bloqueio, retorna erro 403
     * @author Mauro Ribeiro
     * @since 2011-09
     */
    public function filter() {
        $userdata = new Zend_Session_Namespace('UserData');
        if (isset($userdata->botblocker) && isset($userdata->botblocker->block) && $userdata->botblocker->block == true) {
            //throw new Zend_Controller_Action_Exception('Acesso negado', 403)a;
            die("eee");
        }
    }

}
