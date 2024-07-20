<?php

class NewsModel extends BaseObject{
    public function getFromPage($page) {
        $page = $this->db->filter($page);
        $query1 = "SELECT * FROM `ucp_news`";
        $count = $this->db->select("SELECT COUNT(`id`) FROM `ucp_news`", false);
        return $this->pagination->get($query1, $count['COUNT(`id`)'], 'main/page',(empty($page) OR $page == 0) ? 1 : $page, '`id` DESC', 10);
    }
	
	public function ratingUp($id) {
		$check = $this->db->select("SELECT `likes` FROM `ucp_news` WHERE `id` = '$id'", false);
		if (!empty($check)) {
			$check2 = $this->db->select("SELECT `id` FROM `platinum_news_likes` WHERE `Name` = '{$_SESSION['Name']}' AND `News` = '$id'", false, 1);
			
			if (empty($check2)) {
				$this->db->query("INSERT INTO `platinum_news_likes` VALUES('', '{$_SESSION['Name']}', '$id')");
				$likes = $check['likes'] + 1;
				$this->db->query("UPDATE `ucp_news` SET `likes` = '$likes' WHERE `id` = '$id'");
				return 2;
			} else {
				return 1;
			}
		} else {
			return 0;
		}
	}
}
