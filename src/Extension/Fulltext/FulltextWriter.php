<?php

namespace MarcW\RssWriter\Extension\Fulltext;

use MarcW\RssWriter\WriterRegistererInterface;
use MarcW\RssWriter\RssWriter;

/**
 * FulltextWriter.
 *
 * @author Alexander Kozlovsky <moorsiek@gmail.com>
 */
class FulltextWriter implements WriterRegistererInterface
{
    public function getRegisteredWriters()
    {
        return [
            Fulltext::class => [$this, 'writeFulltext'],
        ];
    }

    public function getRegisteredNamespaces()
    {
        return [];
    }

    public function writeFulltext(RssWriter $rssWriter, Fulltext $fulltext)
    {
        $writer = $rssWriter->getXmlWriter();

        $writer->startElement('full-text');
        $writer->writeCdata($fulltext->getFulltext());
        $writer->endElement();
    }
}
