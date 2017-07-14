<?php

namespace MarcW\RssWriter\Extension\Rambler;

use MarcW\RssWriter\WriterRegistererInterface;
use MarcW\RssWriter\RssWriter;

/**
 * RamblerWriter.
 *
 * @author Alexander Kozlovsky <moorsiek@gmail.com>
 */
class RamblerWriter implements WriterRegistererInterface
{
    public function getRegisteredWriters()
    {
        return [
            Fulltext::class => [$this, 'writeFulltext'],
        ];
    }

    public function getRegisteredNamespaces()
    {
        return [
            'rambler' => 'http://news.rambler.ru',
        ];
    }

    public function writeFulltext(RssWriter $rssWriter, Fulltext $fulltext)
    {
        $writer = $rssWriter->getXmlWriter();

        $writer->startElement('rambler:fulltext');
        $writer->writeCdata($fulltext->getFulltext());
        $writer->endElement();
    }
}
