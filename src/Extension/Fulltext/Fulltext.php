<?php

namespace MarcW\RssWriter\Extension\Fulltext;

/**
 * @author Alexander Kozlovsky <moorsiek@gmail.com>
 *
 * This is an extension that implements non-standard element
 * `full-text` required by one russian media site import
 * format
 */
class Fulltext
{
    private $fulltext = '';

    public function setFulltext($content)
    {
        $this->fulltext = $content;

        return $this;
    }

    public function getFulltext()
    {
        return $this->fulltext;
    }
}
