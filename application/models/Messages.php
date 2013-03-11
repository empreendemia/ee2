<?php

/**
 * Messages.php - Ee_Model_Messages
 * Mapper para manipulação de mensagens
 * 
 * @package models
 * @author Mauro Ribeiro
 * @since 2011-06
 */

class Ee_Model_Messages extends Ee_Model_Mapper
{

    /**
     * Procura mensagens (agrupadas em threads) enviadas e/ou recebidas entre
     * dois usuários
     * 
     * @param $user_1                           usuário 1
     * @param $user_2                           usuário 2
     * @return array(Ee_Model_Data_Message)     lista de mensagens
     * @return array(null)                      caso não encontre nada
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findThreadsByUsers($user_1, $user_2) {
        // aceita objeto ou id
        if (is_object($user_1)) $user_1_id = $user_1->id;
        else $user_1_id = $user_1;

        // aceita objeto ou id
        if (is_object($user_2)) $user_2_id = $user_2->id;
        else $user_2_id = $user_2;

        $select = $this->_dbTable->select()
            ->from('messages', array('id', 'title', 'date', 'status_sender'))
            ->where('parent_id IS NULL') // apenas conversas inicializadas, sem respostas
            ->where('user_id = '.$user_1_id.' OR to_user_id = '.$user_1_id)
            ->where('user_id = '.$user_2_id.' OR to_user_id = '.$user_2_id)
            ->order('id DESC');

        $rows = $this->_dbTable->fetchAll($select);

        $threads = array();

        foreach ($rows as $row) {
            $thread = new Ee_Model_Data_Message($row);
            $threads[] = $thread;
        }

        return $threads;
    }

    /**
     * Procura mensagens de uma thread
     * 
     * @param int $thread_id                    id da thread (mensagem pai)
     * @return array(Ee_Model_Data_Message)     lista de mensagens
     * @return array(null)                      caso não encontre nada
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function findMessagesByThread($thread_id) {
        // procura pelo pai e por todos seus filhos
        $select = $this->_dbTable->select()
            ->from('messages', array('*'))
            ->where('parent_id = '.$thread_id.' OR id = '.$thread_id)
            ->order('id ASC');

        $rows = $this->_dbTable->fetchAll($select);

        $messages = array();

        foreach ($rows as $row) {
            // concatena o campo do corpo da mensagem em um só
            $row->body .= $row->body_2.$row->body_3.$row->body_4;
            unset($row->body_2);
            unset($row->body_3);
            unset($row->body_4);
            $message = new Ee_Model_Data_Message($row);
            // marca mensagem como lida
            if ($message->status_reader == 'unread') {
                $save_status->id = $message->id;
                $save_status->status_reader = 'read';
                $this->save($save_status);
            }
            $messages[] = $message;
        }

        return $messages;
    }


    /**
     * Conta mensagens não lidas
     * 
     * @param int $user_id      id do usuário
     * @return int              número de mensagens não lidas
     * @author Mauro Ribeiro
     * @since 2011
     */
    public function countUnread($user_id) {
        $select = $this->_dbTable->select()
            ->from('messages', array('COUNT(*) as num'))
            ->where('user_id IS NOT NULL')
            ->where('to_user_id = ?', $user_id)
            ->where('status_reader = "unread"');
        return $this->_dbTable->fetchRow($select)->num;
    }


    public function toUser($message) {
        $body = $message->body;
        $message->date = date('Y-m-d H:i:s');
        $message->type = 'user';
        $message->body = substr($body, 0, 254);
        $message->body_2 = substr($body, 255, 509);
        $message->body_3 = substr($body, 510, 764);
        $message->body_4 = substr($body, 765, 1019);
        $message->status_sender = 'sent';
        $message->status_reader = 'unread';
        $this->save($message);
        $message->body = $body;
        unset($message->body_2);
        unset($message->body_3);
        unset($message->body_4);
    }

    public function unreadThreadsByUser($user) {
        if (is_object($user)) $user_id = $user->id;
        else $user_id = $user;

        $select = $this->_dbTable->select()
            ->from('messages', array('id', 'title', 'user_id', 'date', 'parent_id'))
            ->where('user_id IS NOT NULL')
            ->where('to_user_id = ?',$user_id)
            ->where('status_reader = ?', 'unread')
            ->order('id DESC');

        $rows = $this->_dbTable->fetchAll($select);

        $threads = array();

        foreach ($rows as $row) {
            $thread = new Ee_Model_Data_Message($row);
            $user_mapper = new Ee_Model_Users();
            $user = $user_mapper->find($thread->user_id);
            if ($thread->parent_id) $thread = $this->find($thread->parent_id);
            $thread->user = $user;
            $threads[] = $thread;
        }

        return $threads;
    }

}

