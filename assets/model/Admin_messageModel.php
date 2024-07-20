<?php
class Admin_messageModel extends BaseObject {

    public function email($title, $text) {
        $table = $this->table->get('account', array('Email'), true);

        $users = $this->db->select("SELECT `{$table['Email']}` FROM `{$table['table']}`", TRUE, 1);

        if (empty($users[0])) {
            return FALSE;
        }

        $count = count($users);

        for ($i = 0; $i < $count; $i++) {
            $this->mail->send($users[$i][$table['Email']], $title, $text);
        }

        return TRUE;
    }

    public function message($title, $text) {
        $title = $this->db->filter($title);
        $text = $this->db->filter($text);
        
        $users = $this->db->select("SELECT `Name` FROM `ucp_users`", TRUE, 1);

        if (empty($users[0])) {
            return FALSE;
        }

        $count = count($users);
        $query = "INSERT INTO `ucp_message`(`to`, `from`, `title`, `text`) VALUES ";
        
        for ($i = 0; $i < $count; $i++) {
            $query .= "('{$users[$i]['Name']}', 'Бот Good Rp', '$title', '$text')";
            if ($i != ($count - 1)) {
                $query .= ', ';
            } else {
                $query .= ';';
            }
        }

        return $this->db->query($query, 1);
    }

}
