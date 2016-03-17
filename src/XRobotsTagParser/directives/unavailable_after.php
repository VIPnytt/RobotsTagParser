<?php

namespace vipnytt\XRobotsTagParser\directives;


final class unavailable_after implements directiveInterface
{
    const DIRECTIVE = 'unavailable_after';

    const DATE_GOOGLE = 'd M Y H:i:s T';

    private $supportedDateFormats = [
        DATE_RFC850,
        self::DATE_GOOGLE
    ];

    private $value;

    /**
     * Constructor
     *
     * @param string $rule
     * @param array $options
     */
    public function __construct($rule, $options = [])
    {
        foreach ($options as $key => $value) {
            if (isset($this->$key)) {
                $this->$key = $value;
            }
        }
        $this->value = $rule;
    }

    /**
     * Get directive name
     *
     * @return string
     */
    public function getDirective()
    {
        return self::DIRECTIVE;
    }

    /**
     * Get value
     *
     * @return bool|string|null
     */
    public function getValue()
    {
        return $this->getArray()[self::DIRECTIVE];
    }

    /**
     * Get rule array
     *
     * @return array
     */
    public function getArray()
    {
        $result = [];
        $string = trim(substr($this->value, mb_stripos($this->value, self::DIRECTIVE) + mb_strlen(self::DIRECTIVE) + 1));
        while (mb_strlen($string) > 0) {
            foreach ($this->supportedDateFormats as $format) {
                $dateTime = date_create_from_format($format, $string);
                if ($dateTime === false) continue;
                $result[self::DIRECTIVE] = $dateTime->format(DATE_RFC850);
                return $result;
            }
            $string = trim(mb_substr($string, 0, -1));
        }
        return [];
    }
}

