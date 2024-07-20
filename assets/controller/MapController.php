<?php
class MapController extends BaseObject {
	public function index() {
		$server = isset($_GET['server']) ? $_GET['server'] : 0;
		$this->view->set('servers', $this->view->fromArray($this->model->user->serverInfo(), $this->view->sub_load('user/serverList')));
		$this->view->set('houses', $this->view->fromArray($this->_getHouses($server), $this->view->sub_load('map/houseOne')));
		$this->view->set('business', $this->view->fromArray($this->_getBusiness($server), $this->view->sub_load('map/businessOne')));
		$this->view->load('/map/main', false, true);
	}
	
	private function _getHouses($server) {
		$data = $this->model->map->getHouses($server);
		if (empty($data)) {
			$this->view->message(array('type' => 'danger', 'text' => 'Нету информации о карте.'));
			return false;
		}
		$count = count($data);
		for ($i = 0; $i < $count; $i++) {
			if ($data[$i]['hEntrancex'] > 0) {
				$data[$i]['left'] = (3000 + abs($data[$i]['hEntrancex']))/6;
			} else {
				$data[$i]['left'] = (3000 - abs($data[$i]['hEntrancex']))/6;
			}
			
			if ($data[$i]['hEntrancey'] > 0) {
				$data[$i]['top'] = (3000 - abs($data[$i]['hEntrancey']))/6;
			} else {
				$data[$i]['top'] = (3000 + abs($data[$i]['hEntrancey']))/6;
			}
			$data[$i]['icon'] = ($data[$i]['hOwned'] == 0) ? '/assets/view/images/Icon_31.gif' : '/assets/view/images/Icon_32.gif';
		}
		
		return $data;
	}
	
	private function _getBusiness($server) {
		$data = $this->model->map->getBusiness($server);
		if (empty($data)) {
			$this->view->message(array('type' => 'danger', 'text' => 'Нету информации о карте.'));
			return false;
		}
		$count = count($data);
		for ($i = 0; $i < $count; $i++) {
			if ($data[$i]['bEntrx'] > 0) {
				$data[$i]['left'] = (3000 + abs($data[$i]['bEntrx']))/6;
			} else {
				$data[$i]['left'] = (3000 - abs($data[$i]['bEntrx']))/6;
			}
			
			if ($data[$i]['bEntry'] > 0) {
				$data[$i]['top'] = (3000 - abs($data[$i]['bEntry']))/6;
			} else {
				$data[$i]['top'] = (3000 + abs($data[$i]['bEntry']))/6;
			}
			$data[$i]['icon'] = ($data[$i]['hOwner'] == 'None') ? '/assets/view/images/Icon_52.gif' : '/assets/view/images/Icon_36.gif';
		}
		
		return $data;
	}
}