<?php
class MapModel extends BaseObject {
	public function getHouses($server) {
		$server = $this->db->filter($server);
		
		if (!$this->servers->isAvaible($server)) {
			return false;
		}
		
		return $this->db->select("SELECT `hID`, `hOwned`, `hEntrancex`, `hEntrancey`, `hOwner`, `hValue` FROM `house`", true, 2, $server);
	}
	
	public function getBusiness($server) {
		$server = $this->db->filter($server);
		
		if (!$this->servers->isAvaible($server)) {
			return false;
		}
		
		return $this->db->select("SELECT `bID`, `bOwner`, `bBuyPrice`, `bEntrx`, `bEntry`, `bProduct`, `bName` FROM `bizz`", true, 2, $server);
	}
}