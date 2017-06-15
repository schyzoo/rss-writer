<?php

namespace MarcW\RssWriter\Extension\Mrss;

use MarcW\RssWriter\WriterRegistererInterface;
use MarcW\RssWriter\RssWriter;

/**
 * @author Alexander Kozlovsky <moorsiek@gmail.com>
 */
class MrssWriter implements WriterRegistererInterface
{
    public function getRegisteredWriters()
    {
        return [
            Mrss::class => [$this, 'writeMrss'],
        ];
    }

    public function getRegisteredNamespaces()
    {
        return [
            'mrss' => 'http://search.yahoo.com/mrss/',
        ];
    }

    public function writeMrss(RssWriter $rssWriter, Mrss $mrss)
    {
        $writer = $rssWriter->getXmlWriter();

        $writer->startElement('media:rating');
        $writer->writeAttribute('scheme', 'urn:simple');
        $writer->writeRaw($mrss->getRating()->getValue());
        $writer->endElement();
    }
}