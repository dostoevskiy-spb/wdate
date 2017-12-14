<?php

namespace dostoevskiy\wdate;

class WDate {

    const PATTERNS = [
        'H:i:s d.m.Y' => self::CONVERT_FORMAT,
        'H:i d.m.Y'   => 'H:i:00 d.m.Y',
        'H: d.m.Y'    => 'H:00:00 d.m.Y',
        'd.m.Y'       => '00:00:00 d.m.Y',
        'm.Y'         => '00:00:00 01.m.Y',
        'Y'           => '00:00:00 01.01.Y',
        'H:'          => 'H:00:00 01.01.1970',
        'H:i'         => 'H:i:00 01.01.1970',
        'H:i:s'       => 'H:i:s 01.01.1970',
    ];

    const CONVERT_FORMAT = 'H:i:s d.m.Y';

    const DEFAULT_FORMATTER_PATTERN = 'dd MMMM';
    const DEFAULT_FORMATTER_LOCALE  = 'ru_RU';
    protected $haveDayAndMonth = FALSE;

    /** @var bool|\DateTime */
    private $dateObject;

    /** @var  \IntlDateFormatter */
    private $formatter;

    /**
     * WDate constructor.
     *
     * @param string $date
     *
     * @throws WDateException
     */
    private function __construct($date) {
        $this->processInput($date);

        if (!$this->dateObject) {
            throw  new WDateException('Wrong date format');
        }
    }

    public static function factory($date) {
        return new self($date);
    }

    /**
     * @return bool|\DateTime
     */
    public function getDateObject() {
        return $this->dateObject;
    }


    /**
     * @param string $locale
     * @param string $pattern
     *
     * @return string
     * @throws WDateException
     */
    public function getFormattedDayAndMonth(string $locale = self::DEFAULT_FORMATTER_LOCALE, string $pattern = self::DEFAULT_FORMATTER_PATTERN) {
        if (!$this->haveDayAndMonth) {
            throw new WDateException('Day or month is missing');
        }
        $this->configurateFormatter($locale, $pattern);

        return $this->formatter->format($this->dateObject->getTimestamp());
    }


    /**
     * @return int
     */
    public function getValueForCompare(): int {
        return $this->getDateObject()->getTimestamp();
    }

    /**
     * @param $inputPattern
     */
    protected function checkDayAndMonthWasSetted($inputPattern): void {
        if (strpos($inputPattern, 'd.m') !== FALSE) {
            $this->haveDayAndMonth = TRUE;
        }
    }

    /** Convert input date to datetime object
     *
     * @param $date
     */
    protected function processInput($date): void {
        foreach (self::PATTERNS as $inputPattern => $outputFormat) {
            if ($this->dateObject = \DateTime::createFromFormat($inputPattern, $date)) {
                $this->dateObject = \DateTime::createFromFormat(self::CONVERT_FORMAT, $this->dateObject->format($outputFormat));
                $this->checkDayAndMonthWasSetted($inputPattern);
                $this->configurateFormatter(self::DEFAULT_FORMATTER_LOCALE, self::DEFAULT_FORMATTER_PATTERN);
                break;
            }
        }
    }

    /** Configure intl formatter with pattern and locale
     *
     * @param string $locale
     * @param string $pattern
     */
    public function configurateFormatter($locale, $pattern) {
        $this->formatter = new \IntlDateFormatter(
            $locale,
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            'Europe/Moscow',
            \IntlDateFormatter::GREGORIAN,
            $pattern);
    }

    /** Check day and month have been setted
     *
     * @return bool
     */
    public function haveDayAndMonth(): bool {
        return $this->haveDayAndMonth;
    }
}