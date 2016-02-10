<?php

namespace vipnytt\XRobotsTagParser\directives;


final class unavailable_after implements directiveInterface
{
    const DIRECTIVE = 'unavailable_after';

    const DATE_DEFAULT = 'd M Y H:i:s T';
    const DATE_RFC850 = DATE_RFC850;
    const DATE_GOOGLE = 'd M Y H:i:s T';

    private $supportedDateFormats = [
        self::DATE_DEFAULT,
        self::DATE_RFC850,
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
        foreach (array_unique($this->supportedDateFormats) as $format) {
            $dateTime = date_create_from_format($format, $this->value);
            if ($dateTime === false) continue;
            $result[self::DIRECTIVE] = $dateTime->format(DATE_RFC850);
            if (time() >= $dateTime->getTimestamp()) {
                $noindex = new noindex(str_ireplace(self::DIRECTIVE, 'noindex', $this->value));
                $result[$noindex->getDirective()] = $noindex->getValue();
            }
            return $result;
        }
        return [];
    }
}

