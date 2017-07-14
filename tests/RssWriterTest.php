<?php

namespace Tests\MarcW\RssWriter;

use MarcW\RssWriter\Extension\Atom\AtomLink;
use MarcW\RssWriter\Extension\Atom\AtomWriter;
use MarcW\RssWriter\Extension\Core\Channel;
use MarcW\RssWriter\Extension\Core\Cloud;
use MarcW\RssWriter\Extension\Core\CoreWriter;
use MarcW\RssWriter\Extension\Core\Enclosure;
use MarcW\RssWriter\Extension\Core\Guid;
use MarcW\RssWriter\Extension\Core\Image;
use MarcW\RssWriter\Extension\Core\Item;
use MarcW\RssWriter\Extension\Core\Source;
use MarcW\RssWriter\Extension\DublinCore\DublinCore;
use MarcW\RssWriter\Extension\DublinCore\DublinCoreWriter;
use MarcW\RssWriter\Extension\Itunes\ItunesChannel;
use MarcW\RssWriter\Extension\Itunes\ItunesItem;
use MarcW\RssWriter\Extension\Itunes\ItunesWriter;
use MarcW\RssWriter\Extension\Slash\Slash;
use MarcW\RssWriter\Extension\Slash\SlashWriter;
use MarcW\RssWriter\Extension\Sy\Sy;
use MarcW\RssWriter\Extension\Sy\SyWriter;
use MarcW\RssWriter\Extension\Content\Content;
use MarcW\RssWriter\Extension\Content\ContentWriter;
use MarcW\RssWriter\Extension\Mrss\Mrss;
use MarcW\RssWriter\Extension\Mrss\Rating;
use MarcW\RssWriter\Extension\Mrss\MrssWriter;
use MarcW\RssWriter\Extension\Fulltext\Fulltext;
use MarcW\RssWriter\Extension\Fulltext\FulltextWriter;
use MarcW\RssWriter\Extension\Rambler\Fulltext as RamblerFulltext;
use MarcW\RssWriter\Extension\Rambler\RamblerWriter;
use MarcW\RssWriter\RssWriter;

class RssWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testRssWriter()
    {
        $rssWriter = new RssWriter(null, [], true);
        $rssWriter->registerWriter(new CoreWriter());
        $rssWriter->registerWriter(new ItunesWriter());
        $rssWriter->registerWriter(new SyWriter());
        $rssWriter->registerWriter(new SlashWriter());
        $rssWriter->registerWriter(new AtomWriter());
        $rssWriter->registerWriter(new DublinCoreWriter());
        $rssWriter->registerWriter(new ContentWriter());
        $rssWriter->registerWriter(new MrssWriter());
        $rssWriter->registerWriter(new FulltextWriter());
        $rssWriter->registerWriter(new RamblerWriter());

        $source = new Source();
        $source->setUrl('https://example.com')
               ->setTitle('Example Title');

        $channel = new Channel();
        $item = new Item();
        $cloud = new Cloud();
        $cloud->setDomain('example.com')
            ->setPort(80)
            ->setPath('/')
            ->setRegisterProcedure('myProcedure')
            ->setProtocol('http')
            ->setProtocol('soap');

        $enclosures = [];
        $enclosure = new Enclosure();
        $enclosure->setUrl('http://www.example.com/audio.mp3')
                  ->setLength(123)
                  ->setType('audio/wave')
                  ->setType('audio/mp3')
        ;
        $enclosures[] = $enclosure;

        $enclosure = new Enclosure();
        $enclosure->setUrl('http://www.example.com/pic.jpg')
            ->setLength(235)
            ->setType('image/jpeg')
        ;
        $enclosures[] = $enclosure;

        $image = new Image();
        $image->setUrl('https://example.com/img.jpg')
              ->setTitle('Example Image')
              ->setLink('https://example.com')
              ->setWidth(133)
              ->setHeight(133)
              ->setHeight(146)
        ;

        $pubDate = new \DateTime('2001-01-01', new \DateTimeZone('UTC'));
        $lastBuildDate = new \DateTime('2001-01-01', new \DateTimeZone('UTC'));

        $channel->setTitle('My Title')
                ->setLink('https://www.example.com')
                ->setDescription('My Description')
                ->setLanguage('en')
                ->setCopyright('(c) 2016 Acme')
                ->setManagingEditor('john.doe@example.com (John Doe)')
                ->setWebMaster('jane.doe@example.com (Jane Doe)')
                ->setPubDate($pubDate)
                ->setLastBuildDate($lastBuildDate)
                ->setDocs('http://example.com/rss2-spec')
                ->setGenerator('Generator v1')
                ->setCloud($cloud)
                ->setTtl(60)
                ->setImage($image)
                ->setRating('R')
                ->setWebMaster('jane.doe@example.com (Jane Doe)')
        ;

        $channel->addExtension((new Sy())->setUpdatePeriod(Sy::PERIOD_HOURLY));
        $channel->addExtension((new AtomLink())->setRel('self')->setHref('http://www.example.com/feed.xml')->setType('application/rss+xml'));

        $item->setTitle('My Title')
            ->setLink('https://example.com/my-title')
            ->setDescription('My Description')
            ->setAuthor('john.doe@example.com (John Doe)')
            ->setComments('https://example.com/my-title#comments')
            ->setEnclosures($enclosures)
            ->addEnclosure($enclosures[0])
            ->setPubDate($pubDate)
            ->setSource($source)
            ->setCategories(['cat1', 'cat2'])
            ->addCategory('cat3')
            ->setGuid((new Guid())->setIsPermaLink(false)->setGuid(14))
        ;

        $channel->addItem($item);
        $item->addExtension((new Slash())->setComments(140));
        $item->addExtension((new DublinCore())->setCreator('John Doe'));
        $item->addExtension((new Content)->setContent('<p>What\'s up, doc?</p>'));
        $item->addExtension((new Mrss)->setRating((new Rating)->setValue(Rating::RATING_ADULT)));
        $item->addExtension((new Fulltext)->setFulltext('full-text content'));
        $item->addExtension((new RamblerFulltext)->setFulltext('rambler full-text content'));
        $xml = $rssWriter->writeChannel($channel);

        $expected = <<<EOF
<?xml version="1.0"?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:media="http://search.yahoo.com/mrss/" xmlns:rambler="http://news.rambler.ru" version="2.0">
 <channel>
  <title><![CDATA[My Title]]></title>
  <link>https://www.example.com</link>
  <description><![CDATA[My Description]]></description>
  <language>en</language>
  <copyright><![CDATA[(c) 2016 Acme]]></copyright>
  <managingEditor><![CDATA[john.doe@example.com (John Doe)]]></managingEditor>
  <webMaster><![CDATA[jane.doe@example.com (Jane Doe)]]></webMaster>
  <pubDate>Mon, 01 Jan 2001 00:00:00 +0000</pubDate>
  <lastBuildDate>Mon, 01 Jan 2001 00:00:00 +0000</lastBuildDate>
  <generator><![CDATA[Generator v1]]></generator>
  <docs>http://example.com/rss2-spec</docs>
  <cloud domain="example.com" port="80" path="/" registerProcedure="myProcedure" protocol="soap"/>
  <ttl>60</ttl>
  <image>
   <url>https://example.com/img.jpg</url>
   <link>https://example.com</link>
   <title><![CDATA[Example Image]]></title>
  </image>
  <rating>R</rating>
  <sy:updatePeriod>hourly</sy:updatePeriod>
  <atom:link href="http://www.example.com/feed.xml" rel="self" type="application/rss+xml"/>
  <item>
   <title><![CDATA[My Title]]></title>
   <link>https://example.com/my-title</link>
   <description><![CDATA[My Description]]></description>
   <author><![CDATA[john.doe@example.com (John Doe)]]></author>
   <comments>https://example.com/my-title#comments</comments>
   <enclosure url="http://www.example.com/audio.mp3" length="123" type="audio/mp3"/>
   <enclosure url="http://www.example.com/pic.jpg" length="235" type="image/jpeg"/>
   <enclosure url="http://www.example.com/audio.mp3" length="123" type="audio/mp3"/>
   <guid isPermaLink="false"><![CDATA[14]]></guid>
   <pubDate>Mon, 01 Jan 2001 00:00:00 +0000</pubDate>
   <source url="https://example.com"><![CDATA[Example Title]]></source>
   <slash:comments>140</slash:comments>
   <dc:creator><![CDATA[John Doe]]></dc:creator>
   <content:encoded><![CDATA[<p>What's up, doc?</p>]]></content:encoded>
   <media:rating scheme="urn:simple">adult</media:rating>
   <full-text><![CDATA[full-text content]]></full-text>
   <rambler:fulltext><![CDATA[rambler full-text content]]></rambler:fulltext>
  </item>
 </channel>
</rss>

EOF;
        $this->assertEquals($expected, $xml);
    }
}
