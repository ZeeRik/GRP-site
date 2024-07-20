<?php


class GaydController extends BaseObject {

    public function create() {
        if ($_POST['title'] AND $_POST['text']) {
            if ($this->model->admin_gayd->create(array('title' => $_POST['title'], 'text' => $_POST['text']))) {
                $this->view->refresh('/');
            } else {
                $this->view->refresh('/admin/main/get/gayd/create/', true);
            }
        } else {
            if ($_POST['ajaxLoad'] == true) {
                $this->view->refresh('/admin/main/get/gayd/create/', true);
                return FALSE;
            }

            $this->view->load('/admin/main/gayd/create', false, true);
        }
    }

    public function edit($args) {
        if (!is_numeric($args[0])) {
            $this->view->message(array('type' => 'warning', 'text' => '<div id="title">Редактирование гайда</div>Для редактирование выберите необходимый гайд на главной странице и нажмите кнопку "Редактировать", под заголовком.'));
            return FALSE;
        }

        if ($_POST['ajaxLoad'] == true) {
            $this->view->refresh('/admin/main/get/gayd/edit/' . (int) $args[0] . '/', true);
            return FALSE;
        }

        if ($_POST['title'] AND $_POST['text']) {
            if ($this->model->admin_gayd->saveEdit((int) $args[0], $_POST['title'], $_POST['text'])) {
                $this->view->refresh('/');
            } else {
                $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при сохранении гайда, попробуйте позже.'));
            }
        } else {
            $data = $this->model->admin_gayd->getEdit((int) $args[0]);

            if (is_array($data)) {
                $data['id'] = (int) $args[0];
                $this->view->addArray($data);
                $this->view->load('/admin/main/gayd/edit', false, true);
            } else {
                $this->view->message(array('type' => 'danger', 'text' => 'Гайд с таким ID не найдена, вернитесь на главную и выберите нужный гайд.'));
            }
        }
    }

    public function delete($args) {
        if (!is_numeric($args[0])) {
            $this->view->message(array('type' => 'warning', 'text' => '<div id="title">Удаление Гайда</div> Для удаления выберите необходимый гайд на главной странице и нажмите кнопку "Удалить", под заголовком.'));
            return FALSE;
        }
        
        if ($this->model->admin_gayd->delete((int) $args[0])) {
            $this->view->refresh('/');
        } else {
            $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при удалении гайда, попробуйте позже.'));
        }
    }

}
