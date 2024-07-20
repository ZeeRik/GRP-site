<?php

class DonateModel extends Object {
	public function success($nickname, $server, $summ) {
		$nickname = $this->db->filter($nickname, $server);
		$data = $this->db->select("SELECT `pDonatemoney2` FROM `account` WHERE `Name` = '$nickname'", false, 2, $server);
		$donate = $data['pDonatemoney2'] + $summ;
		$this->db->query("UPDATE `account` SET `pDonatemoney2` = $donate WHERE `Name` = '$nickname'", 2, $server);
		$this->db->query("INSERT INTO `platinum_payments` VALUES('', '$nickname', '$summ', NOW())", 2, $server);
	}
	
	public function unban() {
		$check = $this->db->select("SELECT `id` FROM `banlog` WHERE `name` = '{$_SESSION['Name']}'", false, 1);

		if (!empty($check)) {
			$donateMoney = $this->db->select("SELECT `pDonatemoney2` FROM `account` WHERE `Name` = '{$_SESSION['Name']}'", false, 1);
			$donate = $donateMoney['pDonatemoney2'];
			
			if ($donate >= 800) {
				$donate = $donate - 800;
				$this->db->query("UPDATE `account` SET `pDonatemoney2` = $donate WHERE `Name` = '{$_SESSION['Name']}'", 1);
				$this->db->query("DELETE FROM `banlog` WHERE `name` = '{$_SESSION['Name']}'", 1);
				$msgData = array(
					'Name' => $_SESSION['Name'],
					'price' => $donateMoney['pDonatemoney2'] - $donate,
					'balance' => $donate
				);
				
				$this->model->message->add(array('name' => $msgData['Name'], 'title' => '[DONATE] Ваш аккаунт разблокирован!', 'text' => $this->view->sub_load('notification/donateUnban', $msgData)), true);
				return 2;
			} else {
				return 1;
			}
		} else {
			return 0;
		}
	}
	
	public function buycar($id) {
		$user = $this->db->select("SELECT `pDonatemoney2`, `pCar`, `pCar2` FROM `account` WHERE `Name` = '{$_SESSION['Name']}'", false, 1);
		
		if ($user['pCar'] == '462' || $user['pCar2'] == '462') {
			$price = include('assets/config/donateCar.php');
			$price = $price[$id];
			if (!empty($price)) {
				if ($user['pDonatemoney2'] >= $price) {
					$donate = $user['pDonatemoney2'] - $price;
					$slot = ($user['pCar'] == '462') ? 'pCar' : 'pCar2';
					$this->db->query("UPDATE `account` SET `pDonatemoney2` = $donate, `$slot` = $id WHERE `Name` = '{$_SESSION['Name']}'", 1);
					
					$msgData = array(
						'Name' => $_SESSION['Name'],
						'slot' => $user['pCar'] == '462' ? 1 : 2,
						'price' => $price,
						'balance' => $donate,
						'vehicleID' => $id
					);
					
					$this->model->message->add(array('name' => $msgData['Name'], 'title' => '[DONATE] Вы приобрели транспорт!', 'text' => $this->view->sub_load('notification/donateCar', $msgData)), true);
					return 3;
				} else {
					return 2;
				}
			} else {
				return 1;
			}
		} else {
			return 0;
		}
	}
}