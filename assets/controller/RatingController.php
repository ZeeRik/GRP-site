<?php
class RatingController extends BaseObject {

    public function Money() {
		$data = $this->model->rating->byMoney(isset($_GET['server']) ? $_GET['server'] : 0);
		if (empty($data)) {
			$this->view->message(array('type' => 'danger', 'text' => 'Нету информации о рейтинге.'));
			return false;
		}

		$this->view->set('servers', $this->view->fromArray($this->model->user->serverInfo(), $this->view->sub_load('user/serverList')));
		$this->view->set('body', $this->view->fromArray($data, $this->view->sub_load('rating/moneyOne')));
		$this->view->load('rating/money', false, true);
	}
	
    public function Level() {
		$data = $this->model->rating->byLevel(isset($_GET['server']) ? $_GET['server'] : 0);
		if (empty($data)) {
			$this->view->message(array('type' => 'danger', 'text' => 'Нету информации о рейтинге.'));
			return false;
		}

		$this->view->set('servers', $this->view->fromArray($this->model->user->serverInfo(), $this->view->sub_load('user/serverList')));
		$this->view->set('body', $this->view->fromArray($data, $this->view->sub_load('rating/levelOne')));
		$this->view->load('rating/level', false, true);
	}

	public function Online() {
		$data = $this->model->rating->byOnline(isset($_GET['server']) ? $_GET['server'] : 0);
		if (empty($data)) {
			$this->view->message(array('type' => 'danger', 'text' => 'Нету информации о рейтинге.'));
			return false;
		}

		$this->view->set('servers', $this->view->fromArray($this->model->user->serverInfo(), $this->view->sub_load('user/serverList')));
		$this->view->set('body', $this->view->fromArray($data, $this->view->sub_load('rating/onlineOne')));
		$this->view->load('rating/online', false, true);
	}
}