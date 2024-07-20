<?php
class Admin_userModel extends BaseObject {

    public function getInfo($name, $edit = FALSE) {
        $name = $this->db->filter($name);

        $table = $this->table->get('account');
        if (!empty($table['value']['Password'])) {
            unset($table['value']['Password']);
        }

        foreach ($table['value'] AS $value) {
            $selectData[] = $value[2];
            $selectDataInfo[$value[2]] = $value[3];
        }
        if (empty($selectData)) {
            return FALSE;
        }

        $query = $this->table->fromArray($selectData);

        $result = $this->db->select("SELECT $query FROM {$table['name']} WHERE `{$table['value']['Name'][2]}` = '$name'", false, 1);

        if (!is_array($result)) {
            return FALSE;
        }

        if ($edit) { 
            $_SESSION['mail'] = $result['mail'];
            foreach ($selectDataInfo AS $key => $value) {
                $newArray[] = array(
                    'title' => $value,
                    'name' => $key,
                    'value' => $result[$key],
                    'type' => (is_numeric($result[$key])) ? 'number' : 'text'
                );
            }
        } else {
            $format = $this->controller->load('format');
            $array = $format->main('account', $result, true);
            foreach ($selectDataInfo AS $key => $value) {
                $newArray[] = array('title' => $value, 'value' => $array[$key]);
            }
        }
        return $newArray;
    }

    public function saveStats($name, $array) {
        $name = $this->db->filter($name);
        $array = $this->db->filter($array, true);

        if (Config::get('online', 'enable') == 1) {
            if (!$this->model->user->isOnline($name)) {
                return FALSE;
            }
        }

        if ($array['mail'] != $_SESSION['mail']) {
            $this->mail->send($array['mail'], 'Смена E-Mail для ' . $name, $this->view->sub_load('admin/main/user/edit/newEmail', array(
                    'name' => $array['Name'],
                    'admin' => $_SESSION['Name'],
                    'host' => $_SERVER['HTTP_HOST'],
                    'server' => $_SESSION['server']
                )));
        }

        foreach ($array AS $key => $value) {
            if ($value == NULL || empty($key)) {
                unset($array[$key]);
            } else {
                $check = $this->table->findValue('account', $key);

                if (empty($check)) {
                    return FALSE;
                } else {
                    $newArray[] = $key;
                }
            }
        }
        $count = count($newArray);

        for ($i = 0; $i < $count; $i++) {
            if (!empty($newArray[$i])) {
              if ($i != ($count - 1)) {
                    $query .= "`{$newArray[$i]}` = '{$array[$newArray[$i]]}', ";
                } else {
                    $query .= "`{$newArray[$i]}` = '{$array[$newArray[$i]]}'";
                }
            }
        }

        $table = $this->table->get('account', array('Name'), true);
        return $this->db->query("UPDATE `{$table['table']}` SET $query WHERE `{$table['Name']}` = '$name'", 1);
    }

    public function delete($name) {
        $name = $this->db->filter($name);

        $table = $this->table->get('account', array('Name'), true);
        $check1 = $this->db->select("SELECT `{$table['Name']}` FROM `{$table['table']}` WHERE `{$table['Name']}` = '$name'", false, 1);

        if (empty($check1[$table['Name']])) {
            return FALSE;
        }

        if (Config::get('online', 'enable') == 1) {
            if (!$this->model->user->isOnline($name)) {
                return FALSE;
            }
        }

        $check2 = $this->db->select("SELECT `id` FROM `ucp_users` WHERE `Name` = '$name'", false, 1);

        $result = $this->db->query("DELETE FROM `{$table['table']}` WHERE `{$table['Name']}` = '$name'", 1);
        if ($result) {
            if (!empty($check2['id'])) {
                return $this->db->query("DELETE FROM `ucp_users` WHERE `Name` = '$name'", 1);
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function changePass($name) {
        if (empty($name)) {
            return false;
        }
        
        $name = $this->db->filter($name);

        $salt = $this->model->user->_passGenerator(rand(8,10));
        $password = $this->model->user->_passGenerator(rand(6,12), false);
        $dbPass = strtoupper(hash('sha256', iconv('utf-8', 'CP1251', $password) . $salt));

        $data = $this->db->select("SELECT `mail` FROM `account` WHERE `name` = '$name'", false);
        $this->db->query("UPDATE `account` SET `password` = '$dbPass', `salt` = '$salt' WHERE `name` = '$name'");

        $this->mail->send($data['mail'], 'Новый пароль ' . $name, $this->view->sub_load('admin/main/user/edit/newPass', array(
                'name' => $name,
                'admin' => $_SESSION['Name'],
                'password' => $password,
                'host' => $_SERVER['HTTP_HOST'],
                'server' => $_SESSION['server']
            )));

        return TRUE;
    }

}
