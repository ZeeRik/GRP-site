<?php

class GaydModel extends BaseObject{
    public function getFromPage($page) {
        $page = $this->db->filter($page);
        $query1 = "SELECT * FROM `ucp_gayd`";
        $count = $this->db->select("SELECT COUNT(`id`) FROM `ucp_gayd`", false);
        return $this->pagination->get($query1, $count['COUNT(`id`)'], 'main/page',(empty($page) OR $page == 0) ? 1 : $page, '`id` DESC', 10);
    }
}
