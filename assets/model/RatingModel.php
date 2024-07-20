<?php
class RatingModel extends BaseObject {
	public function byMoney($server) {
		$server = $this->db->filter($server);
		
		if (!$this->servers->isAvaible($server)) {
			return false;
		}
		
		return $this->db->select("SELECT `name`, `cash`, `bank_cash` FROM `account` ORDER BY `bank_cash` DESC, `cash` + `bank_cash` DESC LIMIT 10", true, 2, $server);
	}
	
	public function byLevel($server) {
		$server = $this->db->filter($server);
		
		if (!$this->servers->isAvaible($server)) {
			return false;
		}
		
		return $this->db->select("SELECT `name`, `lvl` FROM `account` ORDER BY `lvl` DESC LIMIT 10", true, 2, $server);
	}
	
	public function byOnline($server) {
		$server = $this->db->filter($server);
		
		if (!$this->servers->isAvaible($server)) {
			return false;
		}
		
		return $this->db->select("SELECT `name`, `day_online`, `played_time` FROM `account` ORDER BY `day_online` + `played_time` DESC LIMIT 10", true, 2, $server);
	}
}