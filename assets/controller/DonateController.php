<?php
define('secret', '334696211f3fa4644fbd91f8deb50137');

class DonateController extends BaseObject {
	private function _checkAccess() {
		    if ($this->model->user->isLogin()) {
                return TRUE;
            } else {
                $this->view->refresh('/user/login/', true);
                return die();
            }
	}
	
	public function index() {
		$this->_checkAccess();
		if (!$this->model->user->isOnline()) {
			$server = $this->servers->getInfo($_SESSION['server']);

			$this->view->set('name', $_SESSION['Name']);
			$this->view->set('serverID', $server['id']);
			$this->view->set('server', $server['name']);
			$this->view->load('donate/main', false, true);
		} else {
			$this->view->message(array('type' => 'danger', 'text' => 'Вы должны выйти с сервера, прежде чем пополнить донат счёт.'));
		}
	}
	
	public function get() {
		$get = $_GET;
		
		if ($get['method'] === 'check') {
			echo json_encode(array(
				"jsonrpc" => "2.0",
				"result" => array(
					"message" => 'Вы можете продолжить оплату...'
				),
				'id' => 1,
			));
		}
		
		if ($get['method'] === 'pay') {
			if ($get['params']['sign'] == $this->_getMd5Sign($get['params'], secret)) {
				$data = explode('|', $get['params']['account']);
				$tSum = $get['params']['orderSum'];
				
				if ($tSum >= 1 && $tSum < 50) { 
$donate = $tSum * 1; 
} else if ($tSum >= 50 && $tSum < 300) { 
$donate = $tSum * 2; 
} else if ($tSum >= 300) { 
$donate = $tSum * 3; 
}
				$this->model->donate->success($data[0], $data[1], $donate);
				
				echo json_encode(array(
					"jsonrpc" => "2.0",
					"result" => array(
						"message" => 'На ваш счёт успешно зачислено <b>'.$donate.'</b> доната.'
					),
					'id' => 1,
				));
			}
		}
	}
	
	private function _getMd5Sign($params, $secretKey)
    {
        ksort($params);
        unset($params['sign']);
        return md5(join(null, $params).$secretKey);
    }
	
	public function magazine() {
		$this->_checkAccess();
		$this->view->load('donate/magazine', false, true);
	}
	
	public function unban() {
		$this->_checkAccess();
		
		switch ($this->model->donate->unban()) {
			case 0: $this->view->message(array('type' => 'danger', 'text' => 'Вы не заблокированы.')); break;
			case 1: $this->view->message(array('type' => 'danger', 'text' => 'У вас недостаточно денег для разблокировки.')); break;
			case 2: $this->view->message(array('type' => 'success', 'text' => 'Ваш аккаунт успешно разблокирован.')); break;
		}
		
		$this->magazine();
	}
	
	public function buycar() {
		$this->_checkAccess();
		if (!$this->model->user->isOnline()) {
			if (is_numeric($_POST['carID'])) {
				switch ($this->model->donate->buycar((int) $_POST['carID'])) {
					case 0: $this->view->message(array('type' => 'danger', 'text' => 'У вас нету места для нового транспорта.')); break;
					case 1: $this->view->message(array('type' => 'danger', 'text' => 'Данный транспорт нельзя купить.')); break;
					case 2: $this->view->message(array('type' => 'danger', 'text' => 'У вас недостаточно денег для покупки этого транспорта.')); break;
					case 3: $this->view->message(array('type' => 'success', 'text' => 'Вы успешно купили транспорт..')); break;
				}
			}
		} else {
			$this->view->message(array('type' => 'danger', 'text' => 'Вы должны выйти с сервера, прежде чем пополнить донат счёт.'));
		}
		
		$this->magazine();
	}
}