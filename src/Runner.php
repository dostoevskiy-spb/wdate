<?php

namespace dostoevskiy\wdate;

use Composer\Script\Event;

class Runner {
    public static function getFormattedDate(Event $event) {
        $args = $event->getArguments();
        try {
            $date = array_shift($args);
            if(!$date) {
                die('Укажите дату' . PHP_EOL);
            }
            $wdate  = WDate::factory($date);
            $result = $wdate->getFormattedDayAndMonth();
        } catch (WDateException $e) {
            $result = $e->getMessage();
        } catch (\Exception $e) {
            $result = 'internal error';
        }

        die($result . PHP_EOL);
    }

    public static function compare(Event $event) {
        $args = $event->getArguments();
        try {
            $date1  = array_shift($args);
            $date2  = array_shift($args);
            if(!$date1 || !$date2) {
                die('Необходимо указать две даты' . PHP_EOL);
            }
            $wdate1 = WDate::factory($date1);
            $wdate2 = WDate::factory($date2);
            switch($wdate1->getValueForCompare() <=> $wdate2->getValueForCompare()) {
                case 0:
                    $result = 'Даты равны';
                    break;
                case -1:
                    $result = 'Вторая дата больше первой';
                    break;
                case 1:
                    $result = 'Первая дата больше второй';
                    break;
            }
        } catch (WDateException $e) {
            $result = $e->getMessage();
        } catch (\Exception $e) {
            $result = 'internal error';
        }

        die($result . PHP_EOL);
    }
}
