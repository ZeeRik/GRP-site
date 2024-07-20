<?php

class Servers {

    private $_servers = NULL;

    private function _load() {
        if (empty($this->_servers)) {
            $this->_servers = json_decode(file_get_contents('assets/config/servers.json'), true);
        }
    }

    private function _save() {
        $this->_load();

        return file_put_contents('assets/config/servers.json', json_encode($this->_servers));
    }

    public function isAvaible($id) {
        $this->_load();

        return (!empty($this->_servers[$id])) ? TRUE : FALSE;
    }

    public function add($array) {
        $this->_load();

        $this->_servers[] = $array;

        return $this->_save();
    }

    public function get($id) {
        $this->_load();

        return $this->_servers[$id];
    }

    public function getInfo($id = NULL) {
        $this->_load();
        if (isset($id)) {
            return array('id' => $this->_servers[$id]['id'], 'name' => $this->_servers[$id]['name']);
        } else {
            foreach ($this->_servers AS $key => $value) {
                $array[] = array('id' => $value['id'], 'name' => $value['name']);
            }

            return $array;
        }
    }

    public function getAll() {
        $this->_load();

        return $this->_servers;
    }

    public function update($id, $array) {
        $this->_load();

        if (!empty($this->_servers[$id])) {
            $this->_servers[$id] = $array;

            return $this->_save();
        } else {
            return FALSE;
        }
    }

    public function delete($id) {
        $this->_load();

        if (!empty($this->_servers[$id])) {
            unset($this->_servers[$id]);
            $this->_servers = array_merge(array_slice($this->_servers, 0, 1), array_slice($this->_servers, 1));

            foreach ($this->_servers AS $key => $value) {
                if ($key !== $value['id']) {
                    $this->_servers[$key]['id'] = $key;
                }
            }

            return $this->_save();
        } else {
            return FALSE;
        }
    }

    public function up($id) {
        $this->_load();

        return $this->_serverSwap($id, $id - 1);
    }

    public function down($id) {
        $this->_load();
        return $this->_serverSwap($id, $id + 1);
    }

    private function _serverSwap($old, $new) {
        if (isset($this->_servers[$old]) && isset($this->_servers[$new])) {
            $tmpOld = $this->_servers[$old];
            $tmpNew = $this->_servers[$new];

            $tmpOld['id'] = $new;
            $tmpNew['id'] = $old;

            $this->_servers[$new] = $tmpOld;
            $this->_servers[$old] = $tmpNew;

            $this->_save();
            return true;
        } else {
            return false;
        }
    }

}
