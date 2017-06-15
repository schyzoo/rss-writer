<?php

namespace MarcW\RssWriter\Extension\Mrss;

/**
 * @author Alexander Kozlovsky <moorsiek@gmail.com>
 * @see http://web.resource.org/rss/1.0/modules/content/
 *
 * This is incomplete implementation of RSS Media Module -
 * only item media:rating element is supported
 */
class Mrss
{
    private $rating;

    public function setRating(Rating $rating)
    {
        $this->rating = $rating;
        return $this;
    }

    public function getRating()
    {
        return $this->rating;
    }
}