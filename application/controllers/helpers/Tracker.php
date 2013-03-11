<?php

class Ee_Controller_Helper_Tracker extends Zend_Controller_Action_Helper_Abstract
{

    public function userEvent($action, $value = null) {
        $userdata = new Zend_Session_Namespace('UserData');

        if (isset($userdata->user) && isset($userdata->user->id)) {
            $user_id = $userdata->user->login;
        }
        else {
            if (isset($_SERVER['REMOTE_ADDR'])) $user_id = 'not logged: '.$_SERVER['REMOTE_ADDR'];
            else $user_id = 'not logged';
        }

        $event['category'] = 'User';
        $event['action'] = $action;
        $event['label'] = $user_id;
        $event['value'] = $value;

        $userdata->tracker->events[] = $event;
    }

    public function companyEvent($action, $company_id, $value = null) {
        $userdata = new Zend_Session_Namespace('UserData');
        
        if (is_object($company_id)) $company_id = $company_id->id;

        $event['category'] = 'Company';
        $event['action'] = $action;
        $event['label'] = $company_id;
        $event['value'] = $value;

        $userdata->tracker->events[] = $event;
    }

    public function event($category, $action, $label, $value = null) {
        $userdata = new Zend_Session_Namespace('UserData');

        $event['category'] = $category;
        $event['action'] = $action;
        $event['label'] = $label;
        $event['value'] = $value;

        $userdata->tracker->events[] = $event;
    }
    
    
    // ----------------------------------------------------------------------
    // M I X P A N E L
    // ----------------------------------------------------------------------
    
    /**
     * Registra um novo evento do Mixpanel
     * @param string $event
     * @param array $properties 
     * @author Mauro Ribeiro
     * @since 2012-03-30
     */
    public function track($event, $properties = null) {
        if ($properties == null || ($properties != null && is_array($properties))) {
            $userdata = new Zend_Session_Namespace('UserData');
            $userdata->tracker->tracks[] = array(
                'type'       => 'track',
                'event'      => $event,
                'properties' => $properties
            );
        }
    }
    
    public function trackAnotherUser($event, $properties, $user) {
        $today = new DateTime('now');
        $signup_date = new DateTime($user->date_created);
        $login_date = new DateTime($user->date_updated);
        $signup_days = $today->diff($signup_date);
        $login_days = $today->diff($login_date);
        $user_properties = array(
            'id' => $user->id,
            'login' => $user->login,
            'signup_date' => $user->date_created,
            'company' => $user->company->slug,
            'city' => $user->company->city->slug,
            'region' => $user->company->city->region->slug,
            'sector' => $user->company->sector->slug,
            'profile' => $user->company->profile,
            'type' => $user->company->type,
            'newsletter' => ($user->mails[0] == '1') ? true : false,
            'signup_days' => $signup_days->days,
            'login_days' => $login_days->days,
            'status' => 'not logged'  
        );
        $all_properties = array_merge($properties, $user_properties);
        
        $userdata = new Zend_Session_Namespace('UserData');
        
        $this->identify($user);
        $userdata->tracker->tracks[] = array(
            'type'       => 'track',
            'event'      => $event,
            'properties' => $all_properties
        );
        $this->identify();
    }
    /**
     * Registrar uma ou mais superpropriedades
     * @param string|array properties
     * @param * $value
     * @author Mauro Ribeiro
     * @since 2012-03-30
     */
    public function register($properties, $value = null) {
        $userdata = new Zend_Session_Namespace('UserData');
        // se for uma lista de propriedades
        if (is_array($properties)) {
            $userdata->tracker->tracks[] = array(
                'type'       => 'register',
                'properties' => $properties
            );
        }
        // se for só uma propriedade
        else if ($value) {
            $userdata->tracker->tracks[] = array(
                'type'       => 'register',
                'name'       => $properties,
                'value'      => $value
            );
        }
    }
    
    /**
     * Registrar uma ou mais superpropriedades uma única vez
     * @param string|array properties
     * @param * $value
     * @author Mauro Ribeiro
     * @since 2012-03-30
     */
    public function register_once($properties, $value = null) {
        $userdata = new Zend_Session_Namespace('UserData');
        // se for uma lista de propriedades
        if (is_array($properties)) {
            $userdata->tracker->tracks[] = array(
                'type'       => 'register_once',
                'properties' => $properties
            );
        }
        // se for só uma propriedade
        else if ($value) {
            $userdata->tracker->tracks[] = array(
                'type'       => 'register_once',
                'name'       => $properties,
                'value'      => $value
            );
        }
    }
    
    /**
     * Identifica um usuário
     * @param Ee_Model_Data_User $user
     * @author Mauro Ribeiro
     * @since 2012-03-30
     */
    public function identify($user = null) {
        $userdata = new Zend_Session_Namespace('UserData');
        // se não passou o usuário, procura na sessão
        if ($user == null && $userdata) {
            $user = $userdata->user;
        }
        if ($user) {
            $userdata->tracker->tracks[] = array(
                'type'      => 'identify',
                'id'        => $user->id,
                'name_tag'  => $user->login
            );
        }
    }
    
    /**
     * Registra quando um usuário se cadastrou
     * @param Ee_Model_Data_User $user
     * @author Mauro Ribeiro
     * @since 2012-03-30
     */
    public function signup($user = null) {
        $userdata = new Zend_Session_Namespace('UserData');
        if ($user == null && $userdata) {
            $user = $userdata->user;
        }
        if ($user) {
            $this->identify($user);
            $today = new DateTime('now');
            $this->register(array(
                'id' => $user->id,
                'login' => $user->login,
                'signup_date' => $user->date_created,
                'company' => $user->company->slug,
                'city' => $user->company->city->slug,
                'region' => $user->company->city->region->slug,
                'sector' => $user->company->sector->slug,
                'profile' => $user->company->profile,
                'type' => $user->company->type,
                'newsletter' => ($user->mails[0] == '1') ? true : false,
                'signup_days' => 0,
                'login_days' => 0,
                'status' => 'logged in'
            ));
            $this->track('$signup');
        }
    }
    
    /**
     * Registra quando um usuário loga
     * @param Ee_Model_Data_User $user
     * @author Mauro Ribeiro
     * @since 2012-03-30
     */
    public function login($user = null) {
        $userdata = new Zend_Session_Namespace('UserData');
        if ($user == null && $userdata) {
            $user = $userdata->user;
        }
        if ($user) {
            $this->identify($user);
            $today = new DateTime('now');
            $signup_date = new DateTime($user->date_created);
            $login_date = new DateTime($user->date_updated_old);
            $signup_days = $today->diff($signup_date);
            $login_days = $today->diff($login_date);
            $this->register(array(
                'id' => $user->id,
                'login' => $user->login,
                'signup_date' => $user->date_created,
                'company' => $user->company->slug,
                'city' => $user->company->city->slug,
                'region' => $user->company->city->region->slug,
                'sector' => $user->company->sector->slug,
                'profile' => $user->company->profile,
                'type' => $user->company->type,
                'newsletter' => ($user->mails[0] == '1') ? true : false,
                'signup_days' => $signup_days->days,
                'login_days' => $login_days->days,
                'status' => 'logged in'
            ));
            $this->track('login');
        }
    }


}