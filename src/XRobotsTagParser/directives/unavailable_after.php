<?php

namespace vipnytt\XRobotsTagParser\directives;


final class unavailable_after implements directiveInterface
{
    const DIRECTIVE = 'unavailable_after';

    const DATE_FORMAT_DEFAULT = 'd M Y H:i:s T';

    private $supportedDateFormats = [
        self::DATE_FORMAT_DEFAULT,
        DATE_RFC850, // from Google specification
        'd M Y H:i:s T' // from Google examples
    ];

    private $value;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function getDirective()
    {
        return self::DIRECTIVE;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getArray()
    {
        foreach (array_unique($this->supportedDateFormats) as $format) {
            $dateTime = date_create_from_format($format, $this->value);
            if ($dateTime === false) continue;
            $result[self::DIRECTIVE] = $dateTime->format(DATE_RFC850);
            if (time() >= $dateTime->getTimestamp()) {
                $noindex = new noindex();
                $result[$noindex->getDirective()] = $noindex->getValue();
            }
            return $result;
        }
        return [];
    }
}

