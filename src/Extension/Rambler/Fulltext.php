<?php

namespace MarcW\RssWriter\Extension\Rambler;

/**
 * @author Alexander Kozlovsky <moorsiek@gmail.com>
 *
 * This is an extension that implements non-standard element
 * `rambler:fulltext` required by Weekend Rambler import format
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
