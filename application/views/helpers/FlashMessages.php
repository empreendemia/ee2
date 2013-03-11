<?php

class Zend_View_Helper_FlashMessages
{
    public function flashMessages() {
    // Set up some variables, including the retrieval of all flash messages.
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getCurrentMessages();

        $output = '<ul class="flash_messages_list">';

        $count = 1;
        
        foreach ($messages as $message) {
            if (!is_array($message)) $message = array('message'=>$message);

            if (isset($message['message'])) {
                if (!isset($message['status'])) $message['status'] = 'success';
                $output .= '<li id="flash_message_'.$count.'"class="'.$message['status'].'">';
                $output .= $message['message'];
                $output .= '</li>';
                $count++;
            }
        }

        $output .= '</ul>';
        
        return $output;
    }
}