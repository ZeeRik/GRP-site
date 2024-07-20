<?php

class DonateModel extends BaseObject {
	public function success($nickname, $server, $summ) {
		$nickname = $this->db->filter($nickname, $server);
		$data = $this->db->select("SELECT `donate` FROM `account` WHERE `name` = '$nickname'", false, 2, $server);
		$donate = $data['donate'] + $summ;
		$this->db->query("UPDATE `account` SET `donate` = $donate WHERE `Name` = '$nickname'", 2, $server);
		$this->db->query("INSERT INTO `platinum_payments` VALUES('', '$nickname', '$summ', NOW())", 2, $server);
	}
	
	public function unban() {
		$check = $this->db->select("SELECT `id` FROM `banlog` WHERE `name` = '{$_SESSION['Name']}'", false, 1);

		if (!empty($check)) {
			$donateMoney = $this->db->select("SELECT `donate` FROM `account` WHERE `name` = '{$_SESSION['Name']}'", false, 1);
			$donate = $donateMoney['donate'];
			
			if ($donate >= 800) {
				$donate = $donate - 800;
				$this->db->query("UPDATE `account` SET `donate` = $donate WHERE `name` = '{$_SESSION['Name']}'", 1);
				$this->db->query("DELETE FROM `banlog` WHERE `name` = '{$_SESSION['Name']}'", 1);
				$msgData = array(
					'Name' => $_SESSION['Name'],
					'price' => $donateMoney['donate'] - $donate,
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
		$user = $this->db->select("SELECT `donate`, `car_one`, `car_two` FROM `account` WHERE `name` = '{$_SESSION['Name']}'", false, 1);
		
		if ($user['car_one'] == '462' || $user['car_two'] == '462') {
			$price = include('assets/config/donateCar.php');
			$price = $price[$id];
			if (!empty($price)) {
				if ($user['donate'] >= $price) {
					$donate = $user['donate'] - $price;
					$slot = ($user['car_one'] == '462') ? 'car_one' : 'car_two';
					$this->db->query("UPDATE `account` SET `donate` = $donate, `$slot` = $id WHERE `name` = '{$_SESSION['Name']}'", 1);
					
					$msgData = array(
						'Name' => $_SESSION['Name'],
						'slot' => $user['car_one'] == '462' ? 1 : 2,
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