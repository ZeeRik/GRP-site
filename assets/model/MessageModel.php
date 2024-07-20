<?php
class MessageModel extends BaseObject {

    public function add($array, $system = FALSE) {
        $array = $this->db->filter($array, true);
        if ($system == FALSE) {
            $captcha = $this->controller->load('captcha');
            if (!$captcha->check($array['captcha'])) {
                return 0;
            }

            $title = strlen(utf8_decode($array['title']));
            if ($title < 6 OR $title > 32) {
                return 1;
            }

            $text = strlen(utf8_decode($array['text']));
            if ($text < 7 OR $text > 2048) {
                return 2;
            }

            if ($array['name'] == $_SESSION['Name']) {
                return 3;
            }

            $table = $this->table->get('account', array('Name'), true);
            $userCheck = $this->db->select("SELECT `{$table['Name']}` FROM `{$table['table']}` WHERE `{$table['Name']}` = '{$array['name']}'", false, 1);
            if (empty($userCheck)) {
                return 4;
            }

            $settings = $this->db->select("SELECT `settings` FROM `ucp_users` WHERE `Name` = '{$array['name']}'", false, 1);

            if (!empty($settings)) {
                $settings = json_decode(urldecode($settings['settings']), true);
            }

            if (!$settings['message']) {
                return 5;
            }
        }

        if ($system === FALSE) {
            $from = $_SESSION['Name'];
            $delete = 0;
        } else {
            $from = 'Good Rp UCP';
            $delete = 1;
        }
        $insert = $this->db->query("INSERT INTO `ucp_message`(`to`, `from`, `title`, `text`, `deleteFROM`) VALUES('{$array['name']}', '$from', '{$array['title']}', '{$array['text']}', '$delete')", 1);
        if ($insert) {
            return 6;
        } else {
            return 7;
        }
    }

    public function getInbox() {
        return $this->db->select("SELECT `id`, `from`, `date`, `title`, `status` FROM `ucp_message` WHERE `to` = '{$_SESSION['Name']}' AND `deleteTO` = '0' ORDER BY `id` DESC", true, 1);
    }
    
    public function getOutbox() {
        return $this->db->select("SELECT `id`, `to`, `date`, `title`, `status` FROM `ucp_message` WHERE `from` = '{$_SESSION['Name']}' AND `deleteFROM` = '0' ORDER BY `id` DESC", true, 1);
    }

    public function getFromID($id) {
        $id = $this->db->filter($id);

        $result = $this->db->select("SELECT * FROM `ucp_message` WHERE `id` = '$id'", false, 1);

        if (empty($result)) {
            return 0;
        }

        $name = $_SESSION['Name'];
        if ($result['to'] == $name) {
            $result['type'] = 0;
            $result['name'] = $result['from'];
            if ($result['deleteTO'] == 1) {
                return 1;
            }

            if ($result['status'] == 0) {
                $this->db->query("UPDATE `ucp_message` SET `status` = '1' WHERE `id` = '$id'", 1);
            }
        } else if ($result['from'] == $name) {
            $result['type'] = 1;
            $result['name'] = $result['to'];
            if ($result['deleteFROM'] == 1) {
                return 2;
            }
        } else {
            return 3;
        }

        return $result;
    }

    public function delete($type, $id) {
        $id = $this->db->filter($id);

        $typeField = ($type == 0) ? 'to' : 'from';
        $result = $this->db->select("SELECT `deleteTO`, `deleteFROM`  FROM `ucp_message` WHERE `id` = '$id' AND `$typeField` = '{$_SESSION['Name']}'", false, 1);

        if (empty($result)) {
            return 0;
        }

        if ($type == 0) {
            if ($result['deleteTO'] == 0) {
                if ($result['deleteFROM'] == 1) {
                    $this->db->query("DELETE FROM `ucp_message` WHERE `id` = '$id'", 1);
                } else {
                    $this->db->query("UPDATE `ucp_message` SET `deleteTO` = '1' WHERE `id` = '$id'", 1);
                }
                return 1;
            } else {
                return 2;
            }
        } else {
            if ($result['deleteFROM'] == 0) {
                if ($result['deleteTO'] == 1) {
                    $this->db->query("DELETE FROM `ucp_message` WHERE `id` = '$id'", 1);
                } else {
                    $this->db->query("UPDATE `ucp_message` SET `deleteFROM` = '1' WHERE `id` = '$id'", 1);
                }
                return 1;
            } else {
                return 2;
            }
        }
    }
    
    public function checkNew() {
        return $this->db->select("SELECT `id`, `title`, `from` FROM `ucp_message` WHERE `to` = '{$_SESSION['Name']}' AND `deleteTO` = '0' AND `status` = '0'", false, 1);
    }
}
