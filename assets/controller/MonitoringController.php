<?php
class MonitoringController extends BaseObject {

    public function Admin() {
		$data = $this->model->monitoring->getAdmin(isset($_GET['server']) ? $_GET['server'] : 0);
		if (empty($data)) {
			$this->view->message(array('type' => 'danger', 'text' => 'Нету информации о мониторинге.'));
			return false;
		}

		$this->view->set('servers', $this->view->fromArray($this->model->user->serverInfo(), $this->view->sub_load('user/serverList')));
		$this->view->set('body', $this->view->fromArray($data, $this->view->sub_load('monitoring/adminOne')));
		$this->view->load('monitoring/admin', false, true);
	}
	public function Helper() {
		$data = $this->model->monitoring->getHelper(isset($_GET['server']) ? $_GET['server'] : 0);
		if (empty($data)) {
			$this->view->message(array('type' => 'danger', 'text' => 'Нету информации о мониторинге.'));
			return false;
		}

		$this->view->set('servers', $this->view->fromArray($this->model->user->serverInfo(), $this->view->sub_load('user/serverList')));
		$this->view->set('body', $this->view->fromArray($data, $this->view->sub_load('monitoring/helperOne')));
		$this->view->load('monitoring/helper', false, true);
	}
	public function Leader() {
		$data = $this->model->monitoring->getLeader(isset($_GET['server']) ? $_GET['server'] : 0);
		if (empty($data)) {
			$this->view->message(array('type' => 'danger', 'text' => 'Нету информации о мониторинге.'));
			return false;
		}

		$this->view->set('servers', $this->view->fromArray($this->model->user->serverInfo(), $this->view->sub_load('user/serverList')));
		$this->view->set('body', $this->view->fromArray($data, $this->view->sub_load('monitoring/LeaderOne')));
		$this->view->load('monitoring/Leader', false, true);
	}
	public function stats() {
		$this->view->load('monitoring/stats', false, true);
	}
	
	public function bans($args = null) {
		if (!empty($_GET['searchText'])) {
			$search = array(
				'text' => $_GET['searchText'],
				'type' => $_GET['searchType']
			);
		}
		
		$data = $this->model->monitoring->getBans($args[0], isset($_GET['server']) ? $_GET['server'] : 0, $search);
		
		if (empty($data)) {
			$this->view->message(array('type' => 'danger', 'text' => 'Нету информации о мониторинге.'));
			return false;
		}

		$count = count($data['data']);
		for ($i = 0; $i < $count; $i++) {
			#$data['data'][$i]['unbandate'] = $this->data->ago(date('Y-m-d h:i:s',$data['data'][$i]['unbandate']));
			$data['data'][$i]['unbandate'] = date('Y-m-d h:i:s',$data['data'][$i]['unbandate']);
                        $data['data'][$i]['date'] = date('Y-m-d h:i:s',$data['data'][$i]['date']);
		}
		$this->view->set('body', $this->view->fromArray($data['data'], $this->view->sub_load('monitoring/BansOne')));
		$this->view->set('servers', $this->view->fromArray($this->model->user->serverInfo(), $this->view->sub_load('user/serverList')));
        $this->view->set('page', ($args[0] == 0) ? (int) $args[0] + 1 : (int) $args[0]);
        $this->view->set('pagination', $data['pagination']);
		$this->view->load('monitoring/Bans', false, true);
	}

	public function fractions() {
		if ($_GET['fraction']) { 
			$data = $this->model->monitoring->getFromFraction($_GET['server'], $_GET['fraction']);
		}

		$this->view->set('body', (empty($data)) ? '' : $this->view->fromArray($data, $this->view->sub_load('monitoring/fractionsOne')));
		$this->view->set('servers', $this->view->fromArray($this->model->user->serverInfo(), $this->view->sub_load('user/serverList')));
		$this->view->set('fractions', $this->view->fromArray($this->model->fractions->orgControl(), $this->view->sub_load('monitoring/fractionsList')));
		$this->view->load('monitoring/fractions', false, true);
	}
}