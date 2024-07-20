<?php

class LeaderModel extends BaseObject {
	public function getMembers() {
		$data = $this->db->select("SELECT `name`, `rank`, `day_online` FROM `account` WHERE `member` = '{$_SESSION['Leader']}'", true, 1);
		
		if (!empty($data)) {
			$format = $this->controller->load('format');
			$count = count($data);
			for ($i = 0; $i < $count; $i++) {
				foreach ($data[$i] AS $key => $value) {
					$data[$i][$key] = $format->account($this->table->findValue('account', $key), $value, array('extra' => array('member' => $_SESSION['Leader'])));
				}
			}
		}

		return $data;
	}

	public function up($name) {
		$name = $this->db->filter($name, 1);
		
		$data = $this->db->select("SELECT `rank`, `member` FROM `account` WHERE `name` = '$name'", false, 1);
		if ($data['member'] == $_SESSION['Leader']) {
			$newRank = $data['rank'] + 1;
			if ($data['rank'] != count($this->model->fractions->rankControl($_SESSION['Leader']))) {
				$this->db->query("UPDATE `account` SET `rank` = '$newRank' WHERE `name` = '$name'", 1);
				$org = $this->model->fractions->getOrg($_SESSION['Leader']);
				$rank = $this->model->fractions->getRank($_SESSION['Leader'], $newRank);
				$msgData = array(
					'Name' => $name,
					'orgName' => $org['name'],
					'rankName' => $rank['name']
				);
				$this->model->message->add(array('name' => $name, 'title' => 'Вы получили повышение!', 'text' => $this->view->sub_load('notification/leaderUp', $msgData)), true);
				return 2;
			} else {
				return 1;
			}
		} else {
			return 0;
		}
	}
	
	public function down($name) {
		$name = $this->db->filter($name, 1);
		
		$data = $this->db->select("SELECT `rank`, `member` FROM `account` WHERE `name` = '$name'", false, 1);
		if ($data['member'] == $_SESSION['Leader']) {
			$newRank = $data['rank'] - 1;

			if ($data['rank'] != 1) {
				$this->db->query("UPDATE `account` SET `rank` = '$newRank' WHERE `name` = '$name'", 1);
				$org = $this->model->fractions->getOrg($_SESSION['Leader']);
				$rank = $this->model->fractions->getRank($_SESSION['Leader'], $newRank);
				$msgData = array(
					'Name' => $name,
					'orgName' => $org['name'],
					'rankName' => $rank['name']
				);
				$this->model->message->add(array('name' => $name, 'title' => 'Ваша должность была понижена!', 'text' => $this->view->sub_load('notification/leaderDown', $msgData)), true);
				return 2;
			} else {
				return 1;
			}
		} else {
			return 0;
		}
	}
	
	public function remove($name) {
		$name = $this->db->filter($name, 1);
		
		$data = $this->db->select("SELECT `rank`, `member` FROM `account` WHERE `name` = '$name'", false, 1);
		if ($data['member'] == $_SESSION['Leader']) {
			$this->db->query("UPDATE `account` SET `rank` = '0', `member` = '0' WHERE `name` = '$name'", 1);
				$org = $this->model->fractions->getOrg($_SESSION['Leader']);
				$rank = $this->model->fractions->getRank($_SESSION['Leader'], $data['rank']);
				$msgData = array(
					'Name' => $name,
					'orgName' => $org['name'],
					'rankName' => $rank['name'],
					'leader' => $_SESSION['Name']
				);
				$this->model->message->add(array('name' => $name, 'title' => 'Вы были уволены из '.$org['name'].'!', 'text' => $this->view->sub_load('notification/leaderRemove', $msgData)), true);
			return 1;
		} else {
			return 0;
		}
	}
}