<?php

use dostoevskiy\wdate\WDate;
use PHPUnit\Framework\TestCase;

class WDateTest extends TestCase {

    public function testWrongFormat() {
        $this->expectException(\dostoevskiy\wdate\WDateException::class);
        $this->expectExceptionMessage('Wrong date');
        WDate::factory('12312312');
    }

    /**
     * @dataProvider dayOrMonthMissingProvider
     *
     * @param $datetime
     */
    public function testDayOrMonthMissing($datetime) {
        $wdate = WDate::factory($datetime);
        $this->expectException(\dostoevskiy\wdate\WDateException::class);
        $this->expectExceptionMessage('Day or month is missing');
        $this->assertFalse($wdate->haveDayAndMonth());

        $wdate->getFormattedDayAndMonth();
    }

    /**
     * @dataProvider acceptedFormatsProvider
     *
     * @param $datetime
     * @param $output
     */
    public function testFormatter($datetime, $output) {
        $wdate = WDate::factory($datetime);
        if ($wdate->haveDayAndMonth()) {
            $this->assertTrue(is_string($wdate->getFormattedDayAndMonth()));
        } else {
            $this->expectException(\dostoevskiy\wdate\WDateException::class);
            $this->expectExceptionMessage('Day or month is missing');
            $wdate->getFormattedDayAndMonth();
        }
    }

    /**
     * @dataProvider acceptedFormatsProvider
     *
     * @param $datetime
     */
    public function testAcceptedFormats($datetime) {
        $date = WDate::factory($datetime);
        $this->assertInstanceOf(DateTime::class, $date->getDateObject());
    }

    /**
     * @dataProvider acceptedFormatsProvider
     *
     * @param $datetime
     * @param $timestamp
     */
    public function testTimestampIsCorrect($datetime, $timestamp) {
        $date = WDate::factory($datetime);
        $this->assertEquals($timestamp, $date->getDateObject()->getTimestamp());
    }

    /**
     * @dataProvider compareNotEqualsProvider
     *
     * @param $first
     * @param $second
     */
    public function testCompareNotEquals($first, $second) {
        $wdate1        = WDate::factory($first);
        $wdate2        = WDate::factory($second);
        $compareResult = $wdate1->getValueForCompare() > $wdate2->getValueForCompare();
        $this->assertTrue($compareResult);
    }

    /**
     * @dataProvider compareEqualsProvider
     *
     * @param $first
     * @param $second
     */
    public function testCompareEquals($first, $second) {
        $wdate1        = WDate::factory($first);
        $wdate2        = WDate::factory($second);
        $compareResult = $wdate1->getValueForCompare() == $wdate2->getValueForCompare();
        $this->assertTrue($compareResult);
    }

    public function acceptedFormatsProvider() {
        return [
            ['01:00:05 21.07.2017', 1500598805],
            ['01:05 21.07.2017', 1500599100],
            ['01: 21.07.2017', 1500598800],
            ['21.07.2017', 1500595200],
            ['07.2017', 1498867200],
            ['2017', 1483228800],
            ['01:', 3600],
            ['01:05', 3900],
            ['01:05:17', 3917],
        ];
    }

    public function compareNotEqualsProvider() {
        return [
            ['20.05.2010', '30.09'],
            ['20.05.2010', '01: 20.05.2009'],
            ['01: 20.05.2010', '20.05.2009'],
            ['30.09', '29.09'],
        ];
    }

    public function compareEqualsProvider() {
        return [
            ['30.09', '30.09'],
            ['01:05', '01:05'],
            ['2017', '2017'],
            ['16.10.1990', '16.10.1990'],
        ];
    }

    public function dayOrMonthMissingProvider() {
        return [
            ['2017'],
            ['07.2017'],
            ['01:'],
            ['01:05'],
            ['01:05:17'],
        ];
    }
}
