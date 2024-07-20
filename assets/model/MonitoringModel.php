<?php
class MonitoringModel extends BaseObject {
	public function getAdmin($server) {
		$server = $this->db->filter($server);
		
		if (!$this->servers->isAvaible($server)) {
			return false;
		}
		
		return $this->db->select("SELECT ac.name as name, ad.level as level, ad.vigs as vigs, ac.geton_date as geton_date, IFNULL((SELECT SEC_TO_TIME(o.online_sec) FROM online o WHERE o.account_id = ac.id AND o.date = CURDATE() GROUP BY o.account_id), '-') as online_sec FROM admin ad LEFT JOIN account ac ON ac.id = ad.admin ORDER BY level DESC, name", true, 2, $server);
	}
	public function getHelper($server) {
		$server = $this->db->filter($server);
		
		if (!$this->servers->isAvaible($server)) {
			return false;
		}
		
		return $this->db->select("SELECT ac.name as name, ac.helper as helper, ac.geton_date as geton_date, IFNULL((SELECT SEC_TO_TIME(o.online_sec) FROM online o WHERE o.account_id = ac.id AND o.date = CURDATE() GROUP BY o.account_id), '-') as online_sec FROM account ac WHERE ac.helper > 0 ORDER BY helper DESC, name", true, 2, $server);
	}
	public function getLeader($server) {
		$table = $this->table->get('account');
		$server = $this->db->filter($server);
		
		if (!$this->servers->isAvaible($server)) {
			return false;
		}
		
		
		$data = $this->db->select("SELECT ac.name as name, ac.leader as leader, ac.geton_date as geton_date, IFNULL((SELECT SEC_TO_TIME(o.online_sec) FROM online o WHERE o.account_id = ac.id AND o.date = CURDATE() GROUP BY o.account_id), '-') as online_sec, ac.reprimands as reprimands FROM account ac WHERE ac.leader > 0 AND NOT EXISTS(SELECT 1 FROM admin ad WHERE ad.admin = ac.id) ORDER BY leader ASC, name", true, 2, $server);
		
		if (!empty($data)) {
			$format = $this->controller->load('format');
			$count = count($data);
			for ($i = 0; $i < $count; $i++) {
				foreach ($data[$i] AS $key => $value) {
					$data[$i][$key] = $format->account($this->table->findValue('account', $key), $value);
				}
			}
		}

		return $data;
	}
	
	public function getBans($page, $server, $search) {
		$page = (int) $page;
		if (!$this->servers->isAvaible($server)) {
			return false;
		}

		if (!empty($search)) {
			switch($search['type']) {
				case 0: $field = 'name'; break;
				case 1: $field = 'admin'; break;
				case 2: $field = 'date'; break;
			}
			if (!empty($field)) {
				$searchText = $this->db->filter($search['text'], true, 2, $server);
				$query = "SELECT * FROM `banlog` WHERE `$field` = '{$searchText}'";
				$url = "?server=$server&searchText=$searchText&searchType={$search['type']}";
			}
		}
		$query1 = (empty($query)) ? "SELECT * FROM `banlog`" : $query;
		if (empty($query)) {
			$count = $this->db->numRows("SELECT `id` FROM `banlog`", 2, $server);
        } else {
			$count = $this->db->numRows("SELECT `id` FROM `banlog` WHERE `$field` = '{$searchText}'", 2, $server);
		}
		return $this->pagination->get($query1, $count, 'monitoring/bans',(empty($page) OR $page == 0) ? 1 : $page, '`id` DESC', 100, $server, empty($query) ? '?server='.$server : $url);
	}
	
	public function getFromFraction($server, $fraction) {
		$table = $this->table->get('account');
		$server = $this->db->filter($server);
                $fraction = $this->db->filter($fraction);
		
		if (!$this->servers->isAvaible($server)) {
			return false;
		}

		$data = $this->db->select("SELECT ac.name as name, ac.rank as rank, ac.lvl as lvl, ac.reprimands as reprimands, IFNULL((SELECT SEC_TO_TIME(o.online_sec) FROM online o WHERE o.account_id = ac.id AND o.date = CURDATE() GROUP BY o.account_id), '-') as online_sec FROM account ac WHERE ac.member = '$fraction' AND NOT EXISTS(SELECT 1 FROM admin ad WHERE ad.admin = ac.id) ORDER BY rank DESC, name", true, 2, $server);
		
		if (!empty($data)) {
			$format = $this->controller->load('format');
			$count = count($data);
			for ($i = 0; $i < $count; $i++) {
				foreach ($data[$i] AS $key => $value) {
					$data[$i][$key] = $format->account($this->table->findValue('account', $key), $value, array('extra' => array('member' => $fraction)));
				}
			}
		}

		return $data;
	}
}