<?php

class GaydController extends BaseObject {
    
    public function index($args = NULL) {
        $this->page($args[0]);
    }
    public function page($args = NULL) {
        $data = $this->model->gayd->getFromPage((int) $args[0]);
        
        if (empty($data)) {
            $this->view->message(array('type' => 'warning', 'text' => 'На сайт еще не добавляли гайдов. <div id="title">Гайды</div>'));
            return FALSE;
        }
        
        $count = count($data['data']);
            
        for ($i = 0; $i < $count; $i++) {
            $data['data'][$i]['adminPanel'] = ($_SESSION['Admin'] > 0) ? $this->view->sub_load('gayd/adminPanel', $data['data'][$i]) : ''; 
        }
        
        $this->view->set('body', $this->view->fromArray($data['data'], $this->view->sub_load('gayd/one')));
        $this->view->set('page', ($args[0] == 0) ? (int) $args[0] + 1 : (int) $args[0]);
        $this->view->set('pagination', $data['pagination']);
        $this->view->load('/gayd/main', false, true);
		
    }
}