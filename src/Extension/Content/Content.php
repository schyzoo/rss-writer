<?php

namespace MarcW\RssWriter\Extension\Content;

/**
 * @author Alexander Kozlovsky <moorsiek@gmail.com>
 * @see http://web.resource.org/rss/1.0/modules/content/
 *
 * This is incomplete implementation of RSS 1.0 Content Module -
 * only content:encoded element is supported
 */
class Content
{
    private $content = '';

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }
}
