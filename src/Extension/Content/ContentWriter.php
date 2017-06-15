<?php

namespace MarcW\RssWriter\Extension\Content;

use MarcW\RssWriter\WriterRegistererInterface;
use MarcW\RssWriter\RssWriter;

/**
 * ContentWriter.
 *
 * @author Alexander Kozlovsky <moorsiek@gmail.com>
 */
class ContentWriter implements WriterRegistererInterface
{
    public function getRegisteredWriters()
    {
        return [
            Content::class => [$this, 'writeContent'],
        ];
    }

    public function getRegisteredNamespaces()
    {
        return [
            'content' => 'http://purl.org/rss/1.0/modules/content/',
        ];
    }

    public function writeContent(RssWriter $rssWriter, Content $content)
    {
        $writer = $rssWriter->getXmlWriter();

        $writer->startElement('content:encoded');
        $writer->writeCdata($content->getContent());
        $writer->endElement();
    }
}
