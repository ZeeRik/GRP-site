<?php

class Menu extends BaseObject {
    public function get() {
		$this->db->query('SET NAMES utf8');
        $menu = $this->db->select("SELECT * FROM `ucp_menu` ORDER BY `priority`");
        $array = $this->_checkAccess($menu);

		if ($_SESSION['Leader'] > 0) {
			$array['menu_0'][] = array(
				'title' => 'Панель Лидера',
				'href' => '/leader/',
			);
		}
        $menu['menu_0'] = $this->view->fromArray($array['menu_0'], $this->view->sub_load('menu/one'));
		if (!$_SESSION['Name']) {
			$menu['menu_1'] = $this->view->sub_load('menu/no-login');
		} else {
			$menu['menu_1'] = $this->view->sub_load('menu/login');
		}
		
		$menu['menu_2'] = $this->view->fromArray($array['menu_2'], $this->view->sub_load('menu/listOne'));
		$menu['menu_3'] = $this->view->fromArray($array['menu_3'], $this->view->sub_load('menu/listOne'));
        return $menu;
    }
    
    private function _checkAccess($menu) {
        $count = count($menu);
        
        for ($i = 0; $i < $count; $i++) {
            $access = $menu[$i]['visible'];
            switch ($access) {
                case 0:
                    $newArray['menu_'.$menu[$i]['type']][] = $menu[$i];
                    break;
                case 1:
                    if (!$_SESSION['Name']) {
                        $newArray['menu_'.$menu[$i]['type']][] = $menu[$i];
                    }
                    break;
                case 2:
                    if ($_SESSION['Name']) {
                        $newArray['menu_'.$menu[$i]['type']][] = $menu[$i];
                    }
                    break;
                case 3:
                    if ($_SESSION['Admin'] > 0) {
                        $newArray['menu_'.$menu[$i]['type']][] = $menu[$i];
                    }
                    break;
            }
        }
        
        return $newArray;
    }
}