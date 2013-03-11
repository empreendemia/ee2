<?php

class Zend_View_Helper_Tracker
{

	public function Tracker() {
        return $this;
	}


	public function eventsScript() {
        $userdata = new Zend_Session_Namespace('UserData');
        $output = '';

        if (isset($userdata->tracker) && isset($userdata->tracker->events)) {
            foreach ($userdata->tracker->events as $event) {
                if (isset($event['value'])) {
                    $output .= '_gaq.push(["_trackEvent", "'.$event['category'].'", "'.$event['action'].'", "'.$event['label'].'", '.$event['value'].']);';
                }
                else {
                    $output .= '_gaq.push(["_trackEvent", "'.$event['category'].'", "'.$event['action'].'", "'.$event['label'].'"]);';
                }
            }
        }
        
        unset($userdata->tracker->events);
        return $output;
	}
    
    /**
     * Evento genérico do google analytics
     * @param string $category
     * @param string $action
     * @param string $label 
     * @param boolean inline    se deve imprimir na chamada
     * @return string|void      retorna a string se for inline
     * @author Mauro Ribeiro
     * @since 2012-03-05
     */
    public function event($category, $action, $label, $value, $inline = false) {

        $event['category'] = $category;
        $event['action'] = $action;
        $event['label'] = $label;
        $event['value'] = $value;

        if ($inline) {
            $output = '';
            if(strpos($_SERVER["SERVER_NAME"], '.com') == false) $output .= '//do_not_do_it_';
            $output .= "_gaq.push(['_trackEvent', '".$event['category']."', '".$event['action']."', '".$event['label']."']);\n";
            return $output;
        }
        else {
            $userdata->tracker->events[] = $event;
        }
    }
   

    public function userEvent($action, $value = null, $inline = false) {
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

        if ($inline) {
            $output = '';
            if(strpos($_SERVER["SERVER_NAME"], '.com') == false) $output .= '//do_not_do_it_';
            $output .= "_gaq.push(['_trackEvent', '".$event['category']."', '".$event['action']."', '".$event['label']."']);\n";
            return $output;
        }
        else {
            $userdata->tracker->events[] = $event;
        }
    }

    public function pageView($page, $inline = false) {
        if ($inline) {
            $output = '';
            if(strpos($_SERVER["SERVER_NAME"], '.com') == false) $output .= '//do_not_do_it_';
            $output .= "_gaq.push(['_trackPageview', '".$page."']);";
            return $output;
        }
        else {
            $userdata = new Zend_Session_Namespace('UserData');
            $userdata->tracker->pageviews[] = $page;
        }
    }

    public function companyEvent($action, $company_id, $value = null, $inline = false) {
        if (is_object($company_id)) $company_id = $company_id->id;

        $event['category'] = 'Company';
        $event['action'] = $action;
        $event['label'] = $company_id;
        $event['value'] = $value;


        if ($inline) {
            $output = '';
            if(strpos($_SERVER["SERVER_NAME"], '.com') == false) $output .= '//do_not_do_it_';
            $output .= "_gaq.push(['_trackEvent', '".$event['category']."', '".$event['action']."', '".$event['label']."']);";
            return $output;
        }
        else {
            $userdata->tracker->events[] = $event;
        }
    }
}