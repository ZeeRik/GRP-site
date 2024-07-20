<?php

class UserModel extends BaseObject {

    private $_account = array('id', 'Admin', 'Name');

    public function isLogin() {
        if ($_SESSION['Name']) {
            return TRUE;
        } else {
            if (!empty($_COOKIE['brebvix'])) {
                $key = md5($_SERVER['REMOTE_ADDR'] . 'vk.com/brebvix');
                if ($_COOKIE['brebvix'] == $key) {
                    $result = $this->db->select("SELECT `name`, `ip`, `server` FROM `ucp_login` WHERE `key` = '$key'", false);
                    if (!empty($result) AND $result['ip'] == $_SERVER['REMOTE_ADDR']) {
                        $array = $this->table->get('accounts', array('Admin', 'Name', 'Leader'), true);

                        $adminLevel = $this->db->select("SELECT admin.rank, account.pLeader FROM admin, accounts WHERE account.Name = '{$result['name']}'", false, 2, $result['server']);

                        if ($adminLevel[$array['Admin']] > 0) {
                            $_SESSION['Admin'] = $adminLevel[$array['Admin']];
                        }
						
                        if ($adminLevel[$array['Leader']] > 0) {
                            $_SESSION['Leader'] = $adminLevel[$array['Leader']];
                        }

                        $_SESSION += array(
                            'Name' => $result['Name'],
                            'server' => $result['server']
                        );

                        $check = $this->db->select("SELECT `id` FROM `ucp_users` WHERE `Name` = '{$result['name']}'", false, 2, $result['server']);
                        if (empty($check)) {
                            $settings = urlencode(json_encode(array(
                                'message' => TRUE,
                                'profile' => TRUE,
                                'userbar' => TRUE
                            )));
                            $userbar = urlencode(json_encode(array()));
                            $this->db->query("INSERT INTO `ucp_users`(`Name`, `settings`, `userbar`) VALUES('{$result['name']}', '$settings', '$userbar')", 2, $result['server']);
                        }

                        return TRUE;
                    } else {
                        setcookie('brebvix', '', time() - 3600);
                        $this->db->query("DELETE FROM `ucp_login` WHERE `key` = '$key'");
                        return FALSE;
                    }
                } else {
                    setcookie('brebvix', '', time() - 3600);
                    $this->db->query("DELETE FROM `ucp_login` WHERE `key` = '$key'");
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
    }

    public function isOnline($name = NULL, $server = NULL) {
        $array = Config::get('online');
	
		$server = isset($server) ? $server : $_SESSION['server'];
		
        if ($array['enable']) {
            $table = $this->table->get('accounts', array('Name', 'online_status'), true);
            $name = (empty($name)) ? $_SESSION['Name'] : $name;
            $select = $this->db->select("SELECT `{$table['online_status']}` FROM `{$table['table']}` WHERE `{$table['Name']}` = '{$name}'", false, 2, $server);
            if (!empty($select)) {
                return ($select[$table['online_status']] == $array['value']) ? false : true;
            } else {
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function login($array) {
        $captcha = $this->controller->load('captcha');

        if ($captcha->check($array['captcha'])) {
            if (!$this->servers->isAvaible($array['server'])) {
                return 4;
            }

            $array = $this->db->filter($array, false, 2, $array['server']);
            /*if (Config::get('general', 'md5')) {
                $array['password'] = md5($array['password']);
            }*/
            //$array['password']
            $table = $this->table->get('accounts', array('Name', 'Admin', 'Leader'), true);
            $query = "SELECT `{$table['Admin']}`, `{$table['Name']}`, `{$table['Leader']}`, `tempkey`, `pKeyip`, `pSalt`, `pKey` FROM `{$table['table']}` WHERE `{$table['Name']}` = '{$array['name']}'";
            $result = $this->db->select($query, false, 2, $array['server']);

            if (!empty($result)) {
				if ($result['pKey'] != strtoupper(hash('sha256', iconv('utf-8', 'CP1251', $array['pKey']) . $result['pSalt']))) {
                    return 1;
                }
                if ($result['tempkey'] == 1) {
					if ($array['security'] !== $result['pKeyip']) {
						return 5;
					}
				}
                if ($result[$table['Admin']] > 0) {
                    $_SESSION['Admin'] = $result[$table['Admin']];
                }
				
                if ($result[$table['Leader']] > 0) {
                    $_SESSION['Leader'] = $result[$table['Leader']];
                }
				
                if (Config::get('general', 'techWork') == 1) {
                    if ($_SESSION['Admin'] < 1) {
                        return 2;
                    }
                }
                $_SESSION['Name'] = $array['name'];
                $_SESSION['server'] = $array['server'];
                $key = md5($_SERVER['REMOTE_ADDR'] . 'vk.com/brebvix');
                setcookie('brebvix', $key, time() + 172800);
                $select = $this->db->select("SELECT `id` FROM `ucp_login` WHERE `name` = '{$array['name']}' AND `server` = {$array['server']}", false, 0);
                if (!empty($select)) {
                    $this->db->query("DELETE FROM `ucp_login` WHERE `name` = '{$array['name']}'", 0);
                }
                $checkSettings = $this->db->select("SELECT `id` FROM `ucp_users` WHERE `Name` = '{$array['name']}'", false, 2, $array['server']);

                if (empty($checkSettings)) {
                    $settings = urlencode(json_encode(array('message' => TRUE, 'profile' => TRUE, 'userbar' => false)));
                    $userbar = urlencode(json_encode(array()));
                    $this->db->query("INSERT INTO `ucp_users`(`Name`, `settings`, `userbar`) VALUES('{$array['name']}', '$settings', '$userbar')", 2, $array['server']);
                }
                $this->db->query("INSERT INTO `ucp_login`(`name`, `ip`, `server`, `key`) VALUES('{$array['name']}', '{$_SERVER['REMOTE_ADDR']}', '{$array['server']}', '$key')", 0);
                return 3;
            } else {
                return 1;
            }
        } else {
            return 0;
        }
    }

    public function getSettings() {
        $array = $this->db->select("SELECT `settings` FROM `ucp_users` WHERE `Name` = '{$_SESSION['Name']}'", false, 1);
        if (!empty($array)) {
            return json_decode(urldecode($array['settings']), true);
        } else {
            return FALSE;
        }
    }

    public function saveSettings($array) {
        $array = $this->db->filter($array);

        $array = urlencode(json_encode(array(
            'message' => (bool) $array['message'],
            'profile' => (bool) $array['profile'],
            'userbar' => (bool) $array['userbar']
        )));

        return $this->db->query("UPDATE `ucp_users` SET `settings` = '$array' WHERE `Name` = '{$_SESSION['Name']}'", 1);
    }

    public function userBarEnabled() {
        $array = $this->db->select("SELECT `settings` FROM `ucp_users` WHERE `Name` = '{$_SESSION['Name']}'", false, 1);
        $settings = json_decode(urldecode($array['settings']), true);
        return $settings['userbar'];
    }

    public function userBarInfo() {
        $images = scandir($_SERVER['DOCUMENT_ROOT'] . '/assets/view/images/userbar/');

        if ($images !== FALSE) {
            $table = $this->table->get('accounts');
            $uName = $_SESSION['Name'];

            if (empty($table)) {
                return FALSE;
            }

            foreach ($table['value'] AS $value) {
                if ($value[4]) {
                    $newArray[] = $value;
                }
            }

            if (!empty($newArray)) {
                foreach ($newArray AS $value) {
                    $queryArray[] = $value[2];
                }

                $query = $this->table->fromArray($queryArray);
                $accountInfo = $this->db->select("SELECT $query FROM `{$table['name']}` WHERE `{$table['value']['Name'][2]}` = '$uName'", false, 1);
                if (!empty($accountInfo)) {
                    $array['main'] = $accountInfo;
                }
            }
            $format = $this->controller->load('format');

            $array = $format->main('account', $array, true);
            if (!empty($array['main'])) {
                foreach ($array['main'] AS $key => $value) {
                    unset($array['main'][$key]);
                    $name = $this->table->findValue('account', $key);
                    $value = strip_tags($value);
                    $array['main'][$name] = array('name' => $name, 'title' => $table['value'][$name]['3'], 'value' => $value);
                }
            }
            $userbar = $this->db->select("SELECT `userbar` FROM `ucp_users` WHERE `Name` = '$uName'", false, 1);
            $userbar = json_decode(urldecode($userbar['userbar']), true);

            $images = preg_grep('/\\.(?:png)$/', $images);
            foreach ($images as $image) {
                $array['image'][] = array(
                    'name' => $image,
                    'selected' => ($userbar['image'] == $image) ? 'selected' : ''
                );
            }
            $array['data'] = $userbar;
            return $array;
        }
    }

    public function saveUserBar($array) {
        $array = $this->db->filter($array);
        $count = count($array['data']);
        if ($count > 0 AND $count < 9) {
            $array = urlencode(json_encode($array));
            $name = $_SESSION['Name'];

            if ($this->db->query("UPDATE `ucp_users` SET `userbar` = '$array' WHERE `Name` = '$name'", 1)) {
                $file = "{$_SERVER['DOCUMENT_ROOT']}/assets/view/images/cache/$name.png";
                if (is_file($file)) {
                    unlink($file);
                }

                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function getAccountInfo($name, $server, $account = FALSE) {
        $table = $this->table->get('accounts');
        $name = $this->db->filter($name);

        $access = $this->db->select("SELECT `settings` FROM `ucp_users` WHERE `Name` = '$name'", false, 2, $server);

        if (!empty($access['settings'])) {
            $settings = json_decode(urldecode($access['settings']), true);
        }

        if ($settings['profile'] == TRUE OR $account == TRUE) {

            foreach ($table['value'] AS $value) {
                if ($value[4]) {
                    if ($value[6]) {
                        if ($account) {
                            $newArray[] = $value;
                        }
                    } else {
                        $newArray[] = $value;
                    }
                }
            }

            if (!empty($newArray)) {
                foreach ($newArray AS $value) {
                    $queryArray[] = $value[2];
                }

                $query = $this->table->fromArray($queryArray);
                $accountInfo = $this->db->select("SELECT $query FROM `{$table['name']}` WHERE `{$table['value']['Name'][2]}` = '$name'", false, 2, $server);
                if (!empty($accountInfo)) {
                    $array['extra'] = $accountInfo;
                }
            }

            $query = $this->table->fromArray($this->table->get('accounts', array('Name', 'Admin', 'Email', 'Skin','Eat','Geton','LastIP'), true, false));
            $array['main'] = $this->db->select("SELECT $query FROM `{$table['name']}` WHERE `{$table['value']['Name'][2]}` = '$name'", false, 2, $server);
            $format = $this->controller->load('format');

            $array = $format->main('account', $array, true);
            if (!empty($array['extra'])) {
                foreach ($array['extra'] AS $key => $value) {
                    unset($array['extra'][$key]);
                    $name = $this->table->findValue('account', $key);
                    $array['extra'][] = array('title' => $table['value'][$name]['3'], 'value' => $value);
                }
            }
            $array['settings'] = $settings;
            $array['main'] = $this->table->findValue('account', $array['main']);

            return $array;
        } else {
            return FALSE;
        }
    }

    public function changePassword($array) {
        $array = $this->db->filter($array, true);

        if ($array['newPass'] != $array['reNewPass']) {
            return 0;
        }

        if (strlen($array['newPass']) < 6 OR strlen($array['newPass']) > 64) {
            return 1;
        }

        if (Config::get('online', 'enable') == 1) {
            if ($this->isOnline()) {
                return 2;
            }
        }

        $table = $this->table->get('accounts', array('Name', 'pKey'), true);
        $data = $this->db->select("SELECT `pKey`, `pSalt` FROM `{$table['table']}` WHERE `{$table['Name']}` = '{$_SESSION['Name']}'", false, 1);

        $password = $data['pKey'];
        $oldPass = strtoupper(hash('sha256', iconv('utf-8', 'CP1251', $array['oldPass']) . $data['pSalt']));

        if ($password != $oldPass) {
            return 3;
        }

        $salt = $this->_passGenerator(rand(8,10));
        $newPass = strtoupper(hash('sha256', iconv('utf-8', 'CP1251', $array['newPass']) . $salt));
        
        $result = $this->db->query("UPDATE `{$table['table']}` SET `pKey` = '$newPass', `pSalt` = '$salt' WHERE `{$table['Name']}` = '{$_SESSION['Name']}'", 1);
        if ($result) {
            return 4;
        } else {
            return 5;
        }
    }

    public function changeEmail($email) {
        $email = $this->db->filter($email, true);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 0;
        }

        $check = $this->db->select("SELECT `id` FROM `ucp_email` WHERE `Name` = '{$_SESSION['Name']}'", false, 1);

        if (!empty($check)) {
            return 1;
        }
        $table = $this->table->get('accounts', array('Name', 'Email'), true);
        $oldEmail = $this->db->select("SELECT `{$table['Email']}` FROM `{$table['table']}` WHERE `{$table['Name']}` = '{$_SESSION['Name']}'", false, 1);
        $oldEmail = $oldEmail[$table['Email']];

        if ($oldEmail == $email) {
            return 2;
        }
        $key = md5(uniqid(rand(), 1));
        $status = (empty($oldEmail)) ? 1 : 0;
        $result = $this->db->query("INSERT INTO `ucp_email`(`Name`, `oldEmail`, `newEmail`, `key`, `status`) VALUES('{$_SESSION['Name']}', '$oldEmail', '$email', '$key', '$status')", 1);

        if ($result) {
            $array = array(
                'host' => $_SERVER['HTTP_HOST'],
                'name' => $_SESSION['Name'],
                'key' => $key,
            );
            if ($status == 0) {
                $this->mail->send($oldEmail, "Смена E-Mail на аккаунте {$_SESSION['Name']}", $this->view->sub_load('user/email/oldMail', $array));
                return 3;
            } else {
                $this->mail->send($email, "Смена E-Mail на аккаунте {$_SESSION['Name']}", $this->view->sub_load('user/email/newMail', $array));
                return 4;
            }
        } else {
            return 5;
        }
    }

    public function changeEmailDelete() {
        $check = $this->db->select("SELECT `id` FROM `ucp_email` WHERE `Name` = '{$_SESSION['Name']}'", false, 1);
        if (empty($check['id'])) {
            return FALSE;
        }

        return $this->db->query("DELETE FROM `ucp_email` WHERE `Name` = '{$_SESSION['Name']}'", 1);
    }

    public function changeEmailKey($server, $key) {
        $key = $this->db->filter($key);
        $check = $this->db->select("SELECT `id`, `newEmail`, `status` FROM `ucp_email` WHERE `Name` = '{$_SESSION['Name']}' AND `key` = '{$key}'", false, 2, $server);

        if (empty($check)) {
            return 0;
        }

        if ($check['status'] == 0) {
            $key = md5(uniqid(rand(), 1));
            $array = array(
                'host' => $_SERVER['HTTP_HOST'],
                'name' => $_SESSION['Name'],
                'key' => $key,
            );
            $this->db->query("UPDATE `ucp_email` SET `status` = '1', `key` = '{$key}' WHERE `id` = '{$check['id']}'", 2, $server);
            $this->mail->send($check['newEmail'], "Смена E-Mail на аккаунте {$_SESSION['Name']}", $this->view->sub_load('user/email/newMail', $array));

            return 1;
        } else {
            if (Config::get('online', 'enable') == 1) {
                if (!$this->isOnline()) {
                    return 2;
                }
            }
            $table = $this->table->get('accounts', array('Name', 'Email'), true);
            $this->db->query("DELETE FROM `ucp_email` WHERE `id` = '{$check['id']}'", 2, $server);
            $result = $this->db->query("UPDATE `{$table['table']}` SET `{$table['Email']}` = '{$check['newEmail']}' WHERE `{$table['Name']}` = '{$_SESSION['Name']}'", 2, $server);

            if ($result) {
                return 3;
            } else {
                return 4;
            }
        }
    }

    public function recovery($name, $email, $server, $captcha) {
        $captchaController = $this->controller->load('captcha');
        if (!$captchaController->check($captcha)) {
            return 0;
        }

        $name = $this->db->filter($name);
        $email = $this->db->filter($email);
        $table = $this->table->get('accounts', array('Name', 'Email'), true);

        $checkUser = $this->db->select("SELECT `{$table['Name']}` FROM `{$table['table']}` WHERE `{$table['Name']}` = '$name' AND `{$table['Email']}` = '$email'", false, 2, $server);

        if (empty($checkUser[$table['Name']])) {
            return 1;
        }

        $array = $this->db->select("SELECT `email` FROM `ucp_recovery` WHERE `Name` = '{$name}'", false, 2, $server);

        if (!empty($array['email'])) {
            if ($email == $array['email']) {
                $data = array(
                    'host' => $_SERVER['HTTP_HOST'],
                    'name' => $name,
                    'server' => (int) $server,
                    'key' => md5(uniqid(rand(), 1)),
                );
				$this->mail->send($array['email'], 'Восстановление пароля ' . $data['name'], $this->view->sub_load('user/recovery/mail', $data));
                $this->db->query("UPDATE `ucp_recovery` SET `key` = '{$data['key']}' WHERE `Name` = '{$name}'", 2, $server);
                return 2;
            } else {
                $this->db->query("DELETE FROM `ucp_email` WHERE `Name` = '$name'", 2, $server);
                return 3;
            }
        } else {
            $data = array(
                'host' => $_SERVER['HTTP_HOST'],
                'name' => $name,
				'server' => $server,
                'key' => md5(uniqid(rand(), 1)),
            );
            $this->db->query("INSERT INTO `ucp_recovery`(`Name`, `email`, `key`) VALUES('$name', '$email', '{$data['key']}')", 2, $server);
            $this->mail->send($email, 'Восстановление пароля ' . $data['name'], $this->view->sub_load('user/recovery/mail', $data));
            return 4;
        }
    }

    public function recoveryKey($server, $key) {
        $key = $this->db->filter($key, false, 2, $server);
        $check = $this->db->select("SELECT `id`, `Name`, `email` FROM `ucp_recovery` WHERE `key` = '$key'", false, 2, $server);
        if (empty($check)) {
            return 0;
        }

        if (Config::get('online', 'enable') == 1) {
            if ($this->isOnline($check['Name'], $server)) {
                return 1;
            }
        }

        $password = $this->_passGenerator(rand(6, 12), false);
        $salt = $this->_passGenerator(rand(8,10));
        $dbPass = strtoupper(hash('sha256', iconv('utf-8', 'CP1251', 
            $salt . $password)));

        $table = $this->table->get('accounts', array('Name'), true);
        $result = $this->db->query("UPDATE `{$table['table']}` SET `pKey` = '$dbPass', `pSalt` = '$salt' WHERE `{$table['Name']}` = '{$check['Name']}'", 2, $server);
        if ($result) {
            $data = array(
                'host' => $_SERVER['HTTP_HOST'],
                'name' => $check['Name'],
                'server' => (int) $server,
                'pKey' => $password
            );
			
            $this->mail->send($check['email'], 'Новый пароль ' . $check['Name'], $this->view->sub_load('user/recovery/newPass', $data));
			$this->db->query("DELETE FROM `ucp_recovery` WHERE `id` = '{$check['id']}'", 2, $server);
            return 2;
        } else {
            return 3;
        }
    }

    public function _passGenerator($length, $symbols = true) {
        $chars = $symbols ? 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP@![](#$%^&?*)' : 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
        $size = strlen($chars) - 1;
        while (--$length) {
            $password .= $chars[rand(0, $size)];
        }

        return $password;
    }

    public function logout() {
        $this->db->query("DELETE FROM `ucp_login` WHERE `name` = '{$_SESSION['Name']}'", 0);
        setcookie('brebvix', '', time() - 3600);
        session_destroy();
    }

    public function serverInfo($id = NULL) {
        return $this->servers->getInfo($id);
    }

    public function search($name) {
        $name = $this->db->filter($name);
        $servers = $this->serverInfo();
        $table = $this->table->get('accounts', array($id, 'Name'), true);
        $i = 0;
        foreach ($servers AS $value) {
            $data = $this->db->select("SELECT `{$table['Name']}` FROM `{$table['table']}` WHERE `{$table['Name']}` = '$name'", false, 2, $value['id']);

            if (!empty($data)) {
                $i++;
                $array[] = array('num' => $i, 'name' => $name, 'server_id' => $value['id'], 'server_name' => $value['name']);
            }
        }
        
        return $array;
    }

	public function getSecurityActive() {
		$data = $this->db->select("SELECT `checkip` FROM `account` WHERE `Name` = '{$_SESSION['Name']}'", false, 1);
		return (bool) $data['checkip'];
	}
	
	public function updateSecurity($array) {
		$array = $this->db->filter($array, 1);
		
		if ($array['active'] == 0) {
			return $this->db->query("UPDATE `account` SET `checkip` = 0 WHERE `name` = '{$_SESSION['Name']}'", 1);
		} else if ($array['active'] == 1) {
			if (strlen($array['securityKey']) > 3 && strlen($array['securityKey']) < 33) { 
				return $this->db->query("UPDATE `account` SET `checkip` = 1, `keyip` = '{$array['securityKey']}' WHERE `name` = '{$_SESSION['Name']}'", 1);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function getGamerInfo() {
		return $this->db->select("SELECT `bank_cash` FROM `account` WHERE `name` = '{$_SESSION['Name']}'", false, 1);
	}
	
	public function lotteryCheck($summ) {
		$check = $this->getGamerInfo();
		
		if ($summ > 0 AND $check['bank_cash'] >= $summ) {
			if ($summ < 100000) $winn = 2;
			if ($summ > 100000) $winn = 3;
			if ($summ > 20000000) $winn = 4;
			if ($summ > 100000000) $winn = 5;
			
			$heNumber = rand(1, $winn);
			$winnerNumber = rand(1, $winn);
			
			if ($heNumber == $winnerNumber) {
				$bank_cash = $check['bank_cash'] + ($summ * $winn);
				$this->db->query("UPDATE `account` SET `bank_cash` = '$bank_cash' WHERE `name` = '{$_SESSION['Name']}'", 1);
				return true;
			} else {
				$bank_cash = $check['bank_cash'] - $summ;
				$this->db->query("UPDATE `account` SET `bank_cash` = '$bank_cash' WHERE `name` = '{$_SESSION['Name']}'", 1);
				return false;
			}
		}
	}
	
	public function propertyDeposit($array) {
		$array = $this->db->filter($array);
		
		$transfer = ($array['transfer'] == 1) ? 'bank_cash' : 'cash';
		$user = $this->db->select("SELECT `$transfer` FROM `account` WHERE `name` = '{$_SESSION['Name']}'", false, 1);
		
		if ($array['amount'] <= $user[$transfer]) {
			if ($array['property'] == 0) {
				$house = $this->db->select("SELECT `hMoney` FROM `house` WHERE `hOwner` = '{$_SESSION['Name']}'", false, 1);
				
				if (!empty($house)) {
					$house['hMoney'] += $array['amount'];
					if ($house['hMoney'] <= 50000) {
						$this->db->query("UPDATE `house` SET `hMoney` = '{$house['hMoney']}' WHERE `hOwner` = '{$_SESSION['Name']}'", 1);
					} else {
						return false;
					}
				} else {
					return false;
				}
			} elseif ($array['property'] == 1) {
				$bizz = $this->db->select("SELECT `bMoney` FROM `bizz` WHERE `bOwner` = '{$_SESSION['Name']}'", false, 1);
				
				if (!empty($bizz)) {
					$bizz['bMoney'] += $array['amount'];
					if ($bizz['bMoney'] <= 50000) {
						$this->db->query("UPDATE `bizz` SET `bMoney` = '{$bizz['bMoney']}' WHERE `bOwner` = '{$_SESSION['Name']}'");
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
			
			$userMoney = $user[$transfer] - $array['amount'];
			$this->db->query("UPDATE `account` SET `$transfer` = '$userMoney' WHERE `name` = '{$_SESSION['Name']}'", 1);
			return true;
		} else {
			return false;
		}
	}
}
