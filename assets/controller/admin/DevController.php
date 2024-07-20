<?php

class DevController extends BaseObject {
   
    public function index() {
        if ($_SESSION['devLogin'] != TRUE) {
            $this->view->refresh('/admin/dev/login/');
            return die();
        }
    }

    private function _formatView($array, $level) {
        foreach ($array['modules'] AS $key => $value) {
            $count = count($array['privileges'][$key]);
            for ($i = 0; $i < $count; $i++) {
                if ($array['privileges'][$key][$i]['active']) {
                    $array['privileges'][$key][$i]['selected'] = 'selected';
                }
            }
            $newArray[] = array(
                'name' => $key,
                'title' => $value,
                'value' => $this->view->fromArray($array['privileges'][$key], $this->view->sub_load('admin/dev/privileges/edit/oneMod')),
                'active' => ($key == 'news') ? 'active' : ''
            );
            unset($array['modules'][$key]);
            $array['modules'][] = array(
                'name' => $key,
                'title' => $value,
                'active' => ($key == 'news') ? 'active' : ''
            );
        }
        $this->view->set('level', $level);
        $this->view->set('links', $this->view->fromArray($array['modules'], $this->view->sub_load('admin/dev/privileges/edit/linkOne')));
        $this->view->set('body', $this->view->fromArray($newArray, $this->view->sub_load('admin/dev/privileges/edit/mainBody')));
        $this->view->load('admin/dev/privileges/edit/main', false, true);
    }

    public function ratings($args, $get) {
        switch ($args[0]) {
            case 'add':
                if ($_POST['name']) {
                    if ($this->model->dev->ratingAdd($_POST)) {
                        $this->view->message(array('type' => 'success', 'text' => 'Рейтинг успешно добавлен'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка добавления рейтинга.'));
                    }
                } else {
                    $data = $this->model->dev->getTables();
                    if (!empty($data)) {
                        $group = $this->model->dev->ratingGroupList();
                        if (!empty($group)) {
                            $this->view->set('groupList', $this->view->fromArray($group, $this->view->sub_load('admin/dev/ratings/groupOne')));
                            $this->view->set('tableList', $this->view->fromArray($data, $this->view->sub_load('admin/dev/ratings/tableOne')));
                            $this->view->load('admin/dev/ratings/add', false, true);
                        } else {
                            $this->view->message(array('type' => 'warning', 'text' => 'Нету доступных групп, добавьте их с помощью кнопки "Добавить группу".'));
                        }
                    } else {
                        $this->view->message(array('type' => 'warning', 'text' => 'Нету доступных таблиц, добавьте их <a href="/admin/dev/table/">/admin/dev/table/</a>'));
                    }

                    $continue = FALSE;
                }
                break;
            case 'group':
                switch ($get['action']) {
                    case 'add':
                        if ($_POST['name']) {
                            if ($this->model->dev->ratingGroupAdd($_POST['name'])) {
                                $this->view->message(array('type' => 'success', 'text' => 'Группа успешно добавлена'));
                            } else {
                                $this->view->message(array('type' => 'danger', 'text' => 'Ошибка добавления группы'));
                            }
                        } else {
                            $this->view->load('admin/dev/ratings/group/add', false, true);
                        }
                        break;
                    case 'edit':
                        if ($_POST['name'] && isset($_POST['id'])) {
                            if ($this->model->dev->ratingGroupUpdate($_POST['id'], $_POST['name'])) {
                                $this->view->message(array('type' => 'success', 'text' => 'Изменения успешно сохранены.'));
                            } else {
                                $this->view->message(array('type' => 'danger', 'text' => 'Ошибка, изменения не сохранены.'));
                            }
                        } else {
                            $data = $this->model->dev->ratingGroupList($get['id']);

                            if (!empty($data)) {
                                $this->view->addArray($data);
                                $this->view->load('admin/dev/ratings/group/edit', false, true);
                                $continue = FALSE;
                            } else {
                                $this->view->message(array('type' => 'danger', 'text' => 'Неверный ID.'));
                            }
                        }
                        break;
                    case 'delete':
                        if ($this->model->dev->ratingGroupDelete($get['id'])) {
                            $this->view->message(array('type' => 'success', 'text' => 'Группа (и все входящие в неё рейтинги) успешно удалена.'));
                        } else {
                            $this->view->message(array('type' => 'danger', 'text' => 'Ошибка удаления группы.'));
                        }
                        break;
                }
                break;
            case 'edit':
                if (isset($get['group']) && isset($get['id'])) {
                    if ($_POST['name']) {
                        
                    } else {
                        $data = $this->model->dev->ratingFullInfo($get['id'], $get['group']);
                        
                        if (!empty($data)) {
                            $continue = FALSE;
                        } else {
                            $this->view->message(array('type' => 'danger', 'text' => 'Рейтинг не найден.'));
                        }
                    }
                }
                break;
        }

        if ($continue !== FALSE) {
            $ratings = $this->model->dev->ratingInfo();
            $group = $this->model->dev->ratingGroupList();
            $this->view->set('ratingBody', (empty($ratings) ? null : $this->view->fromArray($ratings, $this->view->sub_load('admin/dev/ratings/ratingOne'))));
            $this->view->set('groupBody', (empty($group) ? null : $this->view->fromArray($group, $this->view->sub_load('admin/dev/ratings/group/one'))));
            $this->view->load('admin/dev/ratings/main', false, true);
        }
    }

    public function servers($args) {
        $this->index();

        switch ($args[0]) {
            case 'add':
                if ($_POST['name']) {
                    if ($this->model->dev->addServer($_POST)) {
                        $this->view->message(array('type' => 'success', 'text' => 'Сервер успешно добавлен.'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при добавлении сервера.'));
                    }
                } else {
                    $this->view->load('admin/dev/servers/add', false, true);
                    $continue = FALSE;
                }
                break;
            case 'up':
                if ($this->model->dev->serverUp((int) $args[1])) {
                    $this->view->message(array('type' => 'success', 'text' => 'Позиция сервера успешно увеличена.'));
                } else {
                    $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при увеличении позиции сервера.'));
                }
                break;
            case 'down':
                if ($this->model->dev->serverDown((int) $args[1])) {
                    $this->view->message(array('type' => 'success', 'text' => 'Позиция сервера успешно уменьшена.'));
                } else {
                    $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при уменьшении позиции сервера.'));
                }
                break;
            case 'edit':
                if ($_POST['name']) {
                    if ($this->model->dev->serverUpdate($args[1], $_POST)) {
                        $this->view->message(array('type' => 'success', 'text' => 'Изменения успешно сохранены.'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка сохранения изменений.'));
                    }
                } else {
                    $data = $this->model->dev->getServer((int) $args[1]);
                    if (!empty($data)) {
                        $this->view->addArray($data);
                        $this->view->load('admin/dev/servers/edit', false, true);
                        $continue = FALSE;
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Сервер не найден.'));
                    }
                }
                break;
            case 'delete':
                if ($this->model->dev->serverDelete((int) $args[1])) {
                    $this->view->message(array('type' => 'success', 'text' => 'Сервер успешно удалён.'));
                } else {
                    $this->view->message(array('type' => 'danger', 'text' => 'Ошибка удаления сервера.'));
                }
                break;
        }
        if ($continue !== FALSE) {
            $servers = $this->model->dev->getServers();
            if (!empty($servers)) {
                $this->view->set('body', $this->view->fromArray($servers, $this->view->sub_load('admin/dev/servers/one')));
            } else {
                $this->view->set('body', '');
            }
            $this->view->load('admin/dev/servers/main', false, true);
        }
    }

    public function privileges($args) {
        $this->index();

        switch ($args[0]) {
            case 'add':
                if ($_POST['level']) {
                    if ($this->model->dev->addPrivileges($_POST['level'])) {
                        $this->view->message(array('type' => 'success', 'text' => 'Права доступа успешно добавлены.'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка добавления прав доступа, для этого уровня администратора уже есть права доступа.'));
                    }
                } else {
                    $this->view->load('admin/dev/privileges/add', false, true);
                    $continue = FALSE;
                }
                break;
            case 'delete':
                if ($this->model->dev->deletePrivileges($args[1])) {
                    $this->view->message(array('type' => 'success', 'text' => 'Права доступа успешно удалены.'));
                } else {
                    $this->view->message(array('type' => 'danger', 'text' => 'Ошибка удаления прав доступа.'));
                }
                break;
            case 'edit':
                if (isset($_POST['news_create'])) {
                    if ($this->model->dev->savePrivileges($args[1], $_POST)) {
                        $this->view->message(array('type' => 'success', 'text' => 'Изменения успешно сохранены.'));
                    } else {
                        $this->view->message(array('type' => 'success', 'text' => 'Ошибка при сохранении изменений.'));
                    }
                } else {
                    $array = $this->model->dev->getPrivileges($args[1]);
                    if (!empty($array)) {
                        $this->_formatView($array, $args[1]);
                        $continue = FALSE;
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Не могу загрузить права доступа (удалите и создайте их заново).'));
                    }
                }
				
        }

        if ($continue !== FALSE) {
            $this->view->set('body', $this->view->fromArray($this->model->dev->getLevels(), $this->view->sub_load('admin/dev/privileges/one')));
            $this->view->load('admin/dev/privileges/main', false, true);
        }
    }

    public function table($args = NULL) {
        $this->index();

        switch ($args[0]) {
            case 'add':
                if ($_POST['title']) {
                    if ($this->model->dev->addTable($_POST)) {
                        $this->view->message(array('type' => 'success', 'text' => 'Таблица успешно добавлена.'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при добавлении таблицы, возможно таблица с данным типом уже добавлена.'));
                    }
                } else {
                    $array = $this->model->dev->allTables();
                    if (empty($array)) {
                        $this->view->message(array('type' => 'danger', 'text' => 'Нету таблиц доступных для добавления.'));
                    } else {
                        $this->view->set('body', $this->view->fromArray($array, $this->view->sub_load('admin/dev/table/addOne')));
                        $this->view->load('admin/dev/table/add', false, true);
                    }
                    $continue = FALSE;
                }
                break;
            case 'delete':
                if ($this->model->dev->deleteTable($args[1])) {
                    $this->view->message(array('type' => 'success', 'text' => 'Таблица успешно удалена.'));
                } else {
                    $this->view->message(array('type' => 'danger', 'text' => 'Невозможно удалить таблицу.'));
                }
                break;
            case 'edit':
                $array = $this->model->dev->tableInfo($args[1]);

                if (!empty($array['title'])) {
                    $this->view->set('table', $array['type']);
                    if (!empty($array['value'])) {
                        $this->view->set('body', $this->view->foreachArray($array['value'], $this->view->sub_load('admin/dev/table/editOne')));
                    } else {
                        $this->view->set('body', '');
                    }
                    $this->view->load('admin/dev/table/edit', false, true);
                } else {
                    $this->view->message(array('type' => 'danger', 'text' => 'Невозможно загрузить таблицу.'));
                }
                $continue = FALSE;
                break;
            case 'editField':
                if ($_POST[1]) {
                    if ($this->model->dev->saveField($args[1], $args[2], $_POST)) {
                        $this->view->message(array('type' => 'success', 'text' => 'Изменения успешно сохранены.'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Невозможно сохранить изменения.'));
                    }

                    $this->table(array('edit', $args[1]));
                } else {
                    $array = $this->model->dev->getField($args[1], $args[2]);

                    $array['selected'] = ($array[4]) ? 'selected' : '';
                    $array['account_selected'] = ($array[6]) ? 'selected' : '';
                    $array['form_selected'] = ($array[5]) ? 'selected' : '';

                    $this->view->addArray($array);
                    $this->view->load('admin/dev/table/editField', false, true);
                }
                $continue = FALSE;
                break;
            case 'deleteField':
                if ($this->model->dev->deleteField($args[1], $args[2])) {
                    $this->view->message(array('type' => 'success', 'text' => 'Поле успешно удалено.'));
                } else {
                    $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при удалении.'));
                }

                $this->table(array('edit', $args[1]));
                $continue = FALSE;
                break;
            case 'addField':
                if ($_POST[0]) {
                    if ($this->model->dev->addField($_POST)) {
                        $this->view->message(array('type' => 'success', 'text' => 'Поле успешно добавлено.'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при добавлении поля, возможно такой уникальный идентификатор уже существует.'));
                    }
                    $this->table(array('edit', $_POST[0]));
                    $continue = FALSE;
                } else {
                    $array = $this->model->dev->getColumns($args[1]);
                    if ($array) {
                        $this->view->set('table', $args[1]);
                        $this->view->set('body', $this->view->fromArray($array, $this->view->sub_load('admin/dev/table/addFieldOne')));

                        $this->view->load('admin/dev/table/addField', false, true);
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при загрузке полей таблицы.'));
                    }
                }
                break;
        }

        if ($continue !== FALSE) {
            $array = $this->model->dev->getTable();
            if (!empty($array)) {
                $menu = $this->view->foreachArray($array, $this->view->sub_load('admin/dev/table/one'));
                $this->view->set('body', (empty($menu) ? '' : $menu));
            } else {
                $this->view->message(array('type' => 'warning', 'text' => 'Добавьте таблицы для корректной работы UCP.'));
                $this->view->set('body', '');
            }
            $this->view->load('admin/dev/table/main', false, true);
        }
    }

    public function settings() {
        $this->index();

        if ($_POST['tpl_name']) {
            if ($this->model->dev->saveSettings($_POST)) {
                $this->view->message(array('type' => 'success', 'text' => 'Настройки успешно сохранены.'));
            } else {
                $this->view->message(array('type' => 'danger', 'text' => 'Ошибка сохранения. Добавьте поле с уникальным идентификатором <b>Online</b> в таблицу с аккаунтами.'));
            }
        }

        $array = $this->model->dev->getSettings();
        if ($array['general']['ajax'] == 1) {
            $array['ajax_selected'] = 'selected';
        }
        $array = $this->_selectedSettings($array);
        $this->view->addArray($array);
        $this->view->load('/admin/dev/settings', false, true);
    }

    private function _selectedSettings($array) {
        if ($array['general']['techWork'] == 1) {
            $array['techWork_selected'] = 'selected';
        }
        if ($array['general']['ajax'] == 1) {
            $array['ajax_selected'] = 'selected';
        }
        if ($array['general']['md5'] == 1) {
            $array['md5_selected'] = 'selected';
        }
        if ($array['general']['changePass'] == 1) {
            $array['changePass_selected'] = 'selected';
        }
        if ($array['general']['changeEmail'] == 1) {
            $array['changeEmail_selected'] = 'selected';
        }
        if ($array['general']['recovery'] == 1) {
            $array['recovery_selected'] = 'selected';
        }

        if ($array['online']['enable'] == 1) {
            $array['online_selected'] = 'selected';
        }

        return $array;
    }

    public function menu($args) {
        $this->index();

        $continue = TRUE;
        if (isset($args)) {
            switch ($args[0]) {
                case 'add':
                    if ($_POST['title']) {
                        if ($this->model->dev->addMenu($_POST)) {
                            $this->view->message(array('type' => 'success', 'text' => 'Пункт меню успешно добавлен.'));
                        } else {
                            $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при добавлении, попробуйте еще раз.'));
                        }
                    }
                    break;
                case 'delete':
                    if ($this->model->dev->deleteMenu($args[1])) {
                        $this->view->message(array('type' => 'success', 'text' => 'Пункт меню успешно удалён.'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при удалении, попробуйте еще раз.'));
                    }
                    break;
                case 'edit':
                    if ($_POST['title']) {
                        if ($this->model->dev->saveMenu($_POST)) {
                            $this->view->message(array('type' => 'success', 'text' => 'Изменения успешно сохранены.'));
                        } else {
                            $this->view->message(array('type' => 'danger', 'text' => 'Ошибка сохранения, попробуйте еще раз.'));
                        }
                    } else {
                        $menu = $this->_filterMenu($this->model->dev->getOneMenu($args[1]));
                        $this->view->addArray($menu);
                        $this->view->load('admin/dev/menu/edit', false, true);
                        $continue = FALSE;
                    }
                    break;
                case 'up':
                    if ($this->model->dev->priorityUp($args[1])) {
                        $this->view->message(array('type' => 'success', 'text' => 'Позиция успешно увеличена.'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при изменении позиции.'));
                    }
                    break;
                case 'down':
                    if ($this->model->dev->priorityDown($args[1])) {
                        $this->view->message(array('type' => 'success', 'text' => 'Позиция успешно понижена.'));
                    } else {
                        $this->view->message(array('type' => 'danger', 'text' => 'Ошибка при изменении позиции.'));
                    }
                    break;
            }
        }

        if ($continue != FALSE) {
            $array = $this->model->dev->getMenu();
            if (!empty($array)) {
                $array = $this->_filterMenu($array);
            } else {
                $array = array(array('title' => 'Пусто', 'href' => 'Пусто', 'id' => null));
            }
            $menu = $this->view->fromArray($array, $this->view->sub_load('admin/dev/menu/one'));
            $this->view->set('body', $menu);
            $this->view->load('admin/dev/menu/main', false, true);
        }
    }

    private function _filterMenu($array) {
        if (!empty($array[0])) {
            $count = count($array);
            for ($i = 0; $i < $count; $i++) {
                $array[$i] = $this->_filterMenu($array[$i]);
            }
        } else {
            switch ($array['visible']) {
                case 0:
                    $array['visibleName'] = 'Всем';
                    break;
                case 1:
                    $array['visibleName'] = 'Только гостям';
                    break;
                case 2:
                    $array['visibleName'] = 'Только пользователям';
                    break;
                case 3:
                    $array['visibleName'] = 'Только администраторам';
                    break;
                default:
                    $array['visibleName'] = 'Недоступно';
                    break;
            }
            $array["type_{$array['type']}"] = 'selected';
            $array["visible_{$array['visible']}"] = 'selected';
        }

        return $array;
    }

    public function menuAdd() {
        $this->index();

        $this->view->load('/admin/dev/menu/add', false, true);
    }

    public function login($args) {
        if (!$_SESSION['devLogin']) {
            if ($_POST['name']) {
                $access = Config::get('access');
                $captcha = $this->controller->load('captcha');

                if (!$captcha->check($_POST['captcha'])) {
                    $array[] = array('type' => 'danger', 'text' => 'Вы ввели неверную капчу.');
                }

                if ($_POST['name'] != $access['login'] OR $_POST['password'] != $access['pass']) {
                    $array[] = array('type' => 'danger', 'text' => 'Вы ввели неверные данные для входа.');
                }

                if (count($array) == 0) {
                    $_SESSION['devLogin'] = TRUE;
                    $this->view->refresh('/admin/dev/');
                } else {
                    $this->view->message($array);
                    echo $this->view->sub_load('admin/dev/login');
                }
            } else {
                echo $this->view->sub_load('admin/dev/login');
            }
        } else {
            $this->view->refresh('/admin/dev/');
        }
    }

    public function logout() {
        $this->index();

        unset($_SESSION['devLogin']);
        $this->view->refresh('/admin/dev/login/');
    }

}
