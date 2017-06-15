<?php

namespace MarcW\RssWriter\Extension\Mrss;

/**
 * @author Alexander Kozlovsky <moorsiek@gmail.com>
 */
class Rating
{
    const RATING_ADULT = 'adult';
    const RATING_NONADULT = 'nonadult';

    private $value = self::RATING_NONADULT;

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
}
