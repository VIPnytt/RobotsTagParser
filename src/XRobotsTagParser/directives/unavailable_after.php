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
     * @return string|null
     */
    public function getValue()
    {
        $parts = explode(',', trim(substr($this->value, mb_stripos($this->value, self::DIRECTIVE) + mb_strlen(self::DIRECTIVE) + 1)));
        for ($i = 1; $i <= count($parts); $i++) {
            foreach ($this->supportedDateFormats as $format) {
                $dateTime = date_create_from_format($format, trim(implode(',', array_slice($parts, 0, $i))));
                if ($dateTime !== false) {
                    return $dateTime->format(DATE_RFC850);
                }
            }
        }
        return null;
    }
}

