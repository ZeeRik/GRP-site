<?php

class Rating {

    private $_rating = array();

    private function _load() {
        if (empty($this->_rating)) {
            $this->_rating = json_decode(file_get_contents('assets/config/rating.json'), true);
        }
    }

    private function _save() {
        if (!empty($this->_rating)) {
            $status = file_put_contents('assets/config/rating.json', json_encode($this->_rating));
        }

        return $status;
    }

    public function addGroup($name) {
        $this->_load();

        $this->_rating[] = array('id' => count($this->_rating), 'name' => $name, 'list' => array());
        return $this->_save();
    }

    public function getGroup($id = NULL) {
        $this->_load();

        if (!isset($id)) {
            foreach ($this->_rating AS $value) {
                $array[] = array('name' => $value['name'], 'id' => $value['id']);
            }

            return $array;
        } else {
            if (isset($this->_rating[$id])) {
                return array('name' => $this->_rating[$id]['name'], 'id' => $id);
            } else {
                return FALSE;
            }
        }
    }
    
    public function updateGroup($id, $name) {
        $this->_load();

        if (isset($this->_rating[$id])) {
            $this->_rating[$id]['name'] = $name;
            
            return $this->_save();
        } else {
            return FALSE;
        }
    }
    
    public function deleteGroup($id) {
        $this->_load();
        
        if (isset($this->_rating[$id])) {
            unset($this->_rating[$id]);
            $this->_rating = array_values($this->_rating);
            
            foreach ($this->_rating AS $key => $value) {
                if ($key != $value['id']) {
                    $this->_rating[$id]['id'] = $key;
                }
            }
            
            return $this->_save();
        } else {
            return FALSE;
        }
    }
    
    public function add($id, $array) {
        $this->_load();
        
        if (!empty($this->_rating[$id])) {
            $this->_rating[$id]['list'][] = $array;
            
            return $this->_save();
        } else {
            return FALSE;
        }
    }
    
    public function info() {
        $this->_load();
        
        foreach ($this->_rating AS $key => $value) {
            if (!empty($value['list'])) {
                foreach ($value['list'] AS $kkey => $vvalue) {
                    switch ($vvalue['type']) {
                        case 0:
                            $type = 'Стандартный';
                            break;
                        case 1:
                            $type = 'Мониторинг';
                            break;
                        case 2:
                            $type = 'Карта';
                            break;
                    }
                    
                    $array[] = array(
                        'group' => $key,
                        'groupName' => $value['name'],
                        'id' => $kkey,
                        'type' => $type,
                        'name' => $vvalue['name']
                    );
                }
            }
        }
        
        return $array;
    }
    
    public function fullInfo($id, $group) {
        $this->_load();
        
        if (!empty($this->_rating[$group]['list'][$id])) {
            $data = $this->_rating[$group]['list'][$id];
            unset($data['list']);
            
            return $data;
        } else {
            return FALSE;
        }
    }
}
