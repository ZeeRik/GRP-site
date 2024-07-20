<?php

class LeaderController extends BaseObject {
	private function _checkAccess() {
		if ($_SESSION['Leader'] <= 0 || !$this->model->user->isLogin()) {
			$this->view->refresh('/user/');
			return die();
		}
	}
	
	public function index() {
		$this->_checkAccess();
		
		$array = $this->model->leader->getMembers();
		if (!empty($array)) {
			$org = $this->model->fractions->getOrg($_SESSION['Leader']);
			$this->view->set('orgName', $org['name']);
			$this->view->set('body', $this->view->fromArray($array, $this->view->sub_load('leader/one')));
			$this->view->load('leader/main', false, true);
		} else {
			$this->view->message(array('type' => 'warning', 'text' => 'Состав фракции пустой.'));
		}
	}
	
	public function up($args) {
		$this->_checkAccess();
		
		switch ($this->model->leader->up($args[0])) {
			case 0:
				$this->view->message(array('type' => 'danger', 'text' => 'Игрок не состоит в вашей организации.'));
				break;
			case 1:
				$this->view->message(array('type' => 'warning', 'text' => 'Игрок достиг максимального ранга.'));
				break;
			case 2:
				$this->view->message(array('type' => 'success', 'text' => 'Ранг игрока успешно повышен.'));
				break;
		}
		
		$this->index();
	}
	
	public function down($args) {
		$this->_checkAccess();
		
		switch ($this->model->leader->down($args[0])) {
			case 0:
				$this->view->message(array('type' => 'danger', 'text' => 'Игрок не состоит в вашей организации.'));
				break;
			case 1:
				$this->view->message(array('type' => 'warning', 'text' => 'Игрок достиг минимального ранга.'));
				break;
			case 2:
				$this->view->message(array('type' => 'success', 'text' => 'Ранг игрока успешно понижен.'));
				break;
		}
		
		$this->index();
	}
	
	public function remove($args) {
		$this->_checkAccess();
		
		switch ($this->model->leader->remove($args[0])) {
			case 0:
				$this->view->message(array('type' => 'danger', 'text' => 'Игрок не состоит в вашей организации.'));
				break;
			case 1:
				$this->view->message(array('type' => 'success', 'text' => 'Игрок успешно выгнан с вашей организации.'));
				break;
		}
		
		$this->index();
	}
}