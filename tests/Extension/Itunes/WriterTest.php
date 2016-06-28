<?php

namespace Tests\MarcW\RssWriter\Extension\Itunes;

use Symfony\Component\Debug\Debug;
use MarcW\RssWriter\Extension\Itunes\Writer;
use MarcW\RssWriter\Extension\Itunes\Channel;
use MarcW\RssWriter\Extension\Itunes\Item;
use MarcW\RssWriter\RssWriter;
use MarcW\RssWriter\Extension\Itunes\Owner;

class WriterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriterNamespaces()
    {
        $writer = new Writer();
        $this->assertEquals(['itunes' => 'http://www.itunes.com/dtds/podcast-1.0.dtd'], $writer->getRegisteredNamespaces());

    }
    public function testWriteChannel()
    {
        $rssWriter = new RssWriter();
        $rssWriter->getXmlWriter()->setIndent(false);
        $writer = new Writer();

        $channel = new Channel();
        $channel->setAuthor('John Doe')
                ->setBlock(true)
                ->setImage('https://link.to/my_image.jpg')
                ->setExplicit(true)
                ->setSubtitle('The Subtitle')
                ->setSummary('The Summary')
                ->setComplete(true)
                ->setOwner((new Owner())->setEmail('john.doe@example.com')->setName('John Doe'))
                ->addCategory('Comedy')
                ->addCategory('Management & Marketing')
        ;

        $writer->writeChannel($rssWriter, $channel);
        $xml = $rssWriter->getXmlWriter()->flush();

        $expected = '<itunes:author><![CDATA[John Doe]]></itunes:author><itunes:summary><![CDATA[The Summary]]></itunes:summary><itunes:block>Yes</itunes:block><itunes:image href="https://link.to/my_image.jpg"/><itunes:explicit>Yes</itunes:explicit><itunes:subtitle><![CDATA[The Subtitle]]></itunes:subtitle><itunes:complete>Yes</itunes:complete><itunes:owner><itunes:name><![CDATA[John Doe]]></itunes:name><itunes:email>john.doe@example.com</itunes:email></itunes:owner><itunes:category text="Comedy"/><itunes:category text="Business"><itunes:category text="Management &amp; Marketing"/></itunes:category>';
        $this->assertEquals($expected, $xml);
    }

    public function testWriteItem()
    {
        $rssWriter = new RssWriter();
        $writer = new Writer();

        $item = new Item();

        $item->setAuthor('John Doe')
             ->setBlock(true)
             ->setImage('https://link.to/my_image.jpg')
             ->setExplicit(true)
             ->setSubtitle('The Subtitle')
             ->setSummary('The Summary')
             ->setDuration('03:04:34')
             ->setIsClosedCaptionned(true)
             ->setOrder(4)
        ;

        $writer->writeItem($rssWriter, $item);
        $xml = $rssWriter->getXmlWriter()->flush();
        $expected = '<itunes:author><![CDATA[John Doe]]></itunes:author><itunes:summary><![CDATA[The Summary]]></itunes:summary><itunes:block>Yes</itunes:block><itunes:image href="https://link.to/my_image.jpg"/><itunes:explicit>Yes</itunes:explicit><itunes:subtitle><![CDATA[The Subtitle]]></itunes:subtitle><itunes:duration>03:04:34</itunes:duration><itunes:isClosedCaption>Yes</itunes:isClosedCaption><itunes:order>4</itunes:order>';
        $this->assertEquals($expected, $xml);
    }
}
