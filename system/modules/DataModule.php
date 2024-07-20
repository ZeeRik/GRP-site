<?php

/*
 * Yii2 Date Widget
 * Developed by brebvix ©2015
 */
 
class Data {

    public function ago($datetime) {
        $date2 = new \DateTime($datetime);
        $date1 = new \DateTime();
		if ($date1 > $date2) {
			return '<span class="label label-success">Разбанен</span>';
		}
        $interval = $date1->diff($date2);

        if ($interval->y > 0) {
            return $this->_format($interval->y, ['год', 'года', 'год']);
        } else if ($interval->m > 0) {
            return $this->_format($interval->m, ['месяц', 'месяца', 'месяцев']);
        } else if ($interval->d > 0) {
            return $this->_format($interval->d, ['день', 'дня', 'дней']);
        } else if ($interval->h > 0) {
            return $this->_format($interval->h, ['час', 'часа', 'часов']);
        } else if ($interval->i > 0) {
            return $this->_format($interval->i, ['минуту', 'минуты', 'минут']);
        } else if ($interval->s >= 0) {
            if ($interval->s < 16) {
                return '<span class="label label-success">Разбанен</span>';
            } else {
                return $this->_format($interval->s, ['секунду', 'секунды', 'секунд']);
            }
        } else {
            return false;
        }
    }

    private function _format($num, $array) {
        $last = $num % 10;
        $numeric = $num % 100;

        if ($last === 1 && $num % 100 !== 11) {
            $result = $array[0];
        } else if (($last === 2 || $last === 3 || $last == 4) && ($numeric !== 12 && $numeric !== 13 && $numeric !== 14)) {
            $result = $array[1];
        } else {
            $result = $array[2];
        }

        return "<b>$num</b> $result";
    }

}
