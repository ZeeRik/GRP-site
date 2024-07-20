<?php

class DB extends BaseObject {

    private static $_mysqli = NULL;

    private function _getDB($type = 0, $server = NULL) {
        switch ($type) {
            case 0:
                $id = 0;
                break;
            case 1:
                $id = $_SESSION['server'];
                break;
            case 2:
                $id = $server;
                break;
        }

        if (!is_object(self::$_mysqli[$id])) {
            $db = $this->servers->get($id);
            if (empty($db)) {
                exit("Не могу установить соединение с сервером <b>$id</b>.");
            }

            mysqli_report(MYSQLI_REPORT_STRICT);

            try {
                self::$_mysqli[$id] = new mysqli($db['db_host'], $db['db_user'], $db['db_pass'], $db['db_name']);
				$this->db->query('SET NAMES utf8', 2, $id);
            } catch (Exception $e) {
                exit("Сервер <b>{$db['name']}</b> недоступен.");
            }
        }
        return self::$_mysqli[$id];
    }

    public function select($query, $array = TRUE, $type = NULL, $server = NULL) {
        $mysqli = $this->_getDB($type, $server);
        if ($result = $mysqli->query($query)) {
            if ($result->num_rows == 1) {
                if ($array == TRUE) {
                    return array($result->fetch_assoc());
                } else {
                    return $result->fetch_assoc();
                }
            } else {
                while ($row = $result->fetch_assoc()) {
                    $resultArray[] = $row;
                }
                return $resultArray;
            }
            $result->free();
        } else {
            die('Ошибка запроса: ' . $mysqli->error);
        }
    }

    public function filter($array, $html = FALSE, $type = NULL, $server = NULL) {
        $mysqli = $this->_getDB($type, $server);

        if (is_array($array)) {
            foreach ($array AS $key => $value) {
                if (is_array($value)) {
                    $array[$key] = $this->filter($value);
                } else {
                    if ($html == TRUE) {
                        $array[$key] = htmlspecialchars($value);
                    }
                    $array[$key] = $mysqli->real_escape_string($value);
                }
            }
            return $array;
        } else {
            if ($html == TRUE) {
                $array = htmlspecialchars($array);
            }
            $array = $mysqli->real_escape_string($array);
            return $array;
        }
    }

    public function query($query, $type = NULL, $server = NULL) {
        $mysqli = $this->_getDB($type, $server);

        if ($result = $mysqli->query($query)) {
            return TRUE;
            $result->free();
        } else {
            die('Ошибка запроса: ' . $mysqli->error);
        }
    }

    public function numRows($query, $type = NULL, $server = NULL) {
        $mysqli = $this->_getDB($type, $server);

        if ($result = $mysqli->query($query)) {
            return $result->num_rows;

            $result->free();
        } else {
            die('Ошибка запроса: ' . $mysqli->error);
        }
    }

    public static function close($id = NULL) {
        if (!empty(self::$_mysqli)) {
            if (isset($id)) {
                self::$_mysqli[$id]->close();
                unset(self::$_mysqli[$id]);
            } else {
                foreach (self::$_mysqli AS $value) {
                    $value->close();
                }
            }
        }
    }

}
