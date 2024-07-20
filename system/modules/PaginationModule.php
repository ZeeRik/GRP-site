<?php

class Pagination extends BaseObject {

    public function get($query, $count, $uri, $page, $order, $limit, $server = 0, $endUri = '') {
        if ($count < 1) {
            return FALSE;
        }

        if ($page == 1) {
            $limitPage = 0;
        } else {
            $limitPage = ($page - 1) * 10;
        }

        $array = $this->db->select("$query ORDER BY $order LIMIT $limitPage,$limit", true, 2, $server);
        if (empty($array)) {
            return FALSE;
        }

        return array(
            'data' => $array,
            'pagination' => $this->getTemplate(array(
                'maxPage' => ceil($count / $limit) + 1,
                'currentPage' => $page,
                'host' => $_SERVER['HTTP_HOST']
                    ), $uri, $endUri)
        );
    }

    public function getTemplate($array, $uri, $endUri) {
        if ($array['currentPage'] == 1) {
            $data['backAction'] = 'disabled';
        } else {
            $data['backHref'] = 'href="/' . $uri . '/' . ($array['currentPage'] - 1) . '/'.$endUri.'"';
        }

        if ($array['currentPage'] == ($array['maxPage'] - 1)) {
            $data['nextAction'] = 'disabled';
        } else {
            $data['nextHref'] = 'href="/' . $uri . '/' . ($array['currentPage'] + 1) . '/'.$endUri.'"';
        }

        for ($i = 1; $i < $array['maxPage']; $i++) {

            if ($i == $array['currentPage']) {
                $data['body'] .= $this->view->sub_load('pagination/oneCurrent', array('page' => $i));
            } else {
                $pageData = array(
                    'host' => $array['host'],
                    'pageName' => $uri,
                    'page' => $i,
					'endUri' => $endUri
                );
                $data['body'] .= $this->view->sub_load('pagination/one', $pageData);
            }
        }

        return $this->view->sub_load('pagination/main', $data);
    }

}
