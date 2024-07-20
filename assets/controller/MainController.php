<?php

class MainController extends BaseObject {
    
    public function index($args = NULL) {
        $this->page($args[0]);
    }
    public function start() {
       $this->view->load('start', false, true);
    }
    public function page($args = NULL) {
        $data = $this->model->news->getFromPage((int) $args[0]);
        
        if (empty($data)) {
            $this->view->message(array('type' => 'warning', 'text' => 'На сайт еще не добавляли новости. <div id="title">Новости</div>'));
            return FALSE;
        }
        
        $count = count($data['data']);
            
        for ($i = 0; $i < $count; $i++) {
            $data['data'][$i]['adminPanel'] = ($_SESSION['Admin'] > 0) ? $this->view->sub_load('news/adminPanel', $data['data'][$i]) : ''; 
        }
        
        $this->view->set('body', $this->view->fromArray($data['data'], $this->view->sub_load('news/one')));
        $this->view->set('page', ($args[0] == 0) ? (int) $args[0] + 1 : (int) $args[0]);
        $this->view->set('pagination', $data['pagination']);
        $this->view->load('/news/main', false, true);
		
    }
	
	public function rating($args) {
		if ($this->model->user->isLogin()) {
			if (is_numeric($args[0])) {
				switch ($this->model->news->ratingUp((int) $args[0])) {
					case 0: $this->view->message(array('type' => 'error', 'text' => 'Новость не найдена.')); break;
					case 1: $this->view->message(array('type' => 'error', 'text' => 'Вы уже поднимали рейтинг этой новости.')); break;
					case 2: $this->view->message(array('type' => 'success', 'text' => 'Вы успешно подняли рейтинг новости.')); break;
				}
			}
		} else {
			$this->view->message(array('type' => 'warning', 'text' => 'Вы должны быть авторизованы.'));
		}
		
		$this->page();
	}
}