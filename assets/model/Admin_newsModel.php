<?php

class Admin_newsModel extends BaseObject {
    private function _check() {
        if ($_SESSION['server'] != 0) {
            $this->view->message(array('type' => 'warning', 'text' => 'Управлять новостями может только администрация сервера с ID 0.'));
            return die();
        }
    }
    
    public function create($array) {
        $this->_check();
        $array = $this->db->filter($array);
        
        return $this->db->query("INSERT INTO `ucp_news`(`title`, `text`, `author`, `date`) VALUES('{$array['title']}', '{$array['text']}', '{$_SESSION['Name']}', NOW())");
    }
    
    public function getEdit($id) {
        $this->_check();
        return $this->db->select("SELECT `title`, `text` FROM `ucp_news` WHERE `id` = '$id'", false);
    }
    
    public function saveEdit($id, $title, $text) {
        $this->_check();
        $title = $this->db->filter($title);
        $text = $this->db->filter($text);
        
        return $this->db->query("UPDATE `ucp_news` SET `title` = '$title', `text` = '$text' WHERE `id` = '$id'");
    }
    
    public function delete($id) {
        return $this->db->query("DELETE FROM `ucp_news` WHERE `id` = '$id'");
    }
}