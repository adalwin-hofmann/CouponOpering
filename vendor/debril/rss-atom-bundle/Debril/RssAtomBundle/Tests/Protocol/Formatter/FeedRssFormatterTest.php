<?php

namespace Debril\RssAtomBundle\Protocol\Formatter;

use \Debril\RssAtomBundle\Protocol\Parser\FeedContent;
use \Debril\RssAtomBundle\Protocol\Parser\Item;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-02-12 at 21:51:18.
 */
class FeedRssFormatterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FeedRssFormatter
     */
    protected $object;

    /**
     * @var FeedContent
     */
    protected $feed;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new FeedRssFormatter;

        $this->feed = new FeedContent();

        $this->feed->setPublicId('feed id');
        $this->feed->setLink('http://example.com');
        $this->feed->setTitle('feed title');
        $this->feed->setDescription('feed subtitle');
        $this->feed->setLastModified(new \DateTime);

        $item = new Item;
        $item->setPublicId('item id');
        $item->setLink('http://example.com/1');
        $item->setSummary('lorem ipsum');
        $item->setTitle('title 1');
        $item->setUpdated(new \DateTime);
        $item->setComment('http://linktothecomments.com');
        $item->setAuthor('Contributor');

        $this->feed->addItem($item);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\FeedFormatter::toString
     */
    public function testToString()
    {
        $string = $this->object->toString($this->feed);

        $this->assertInternalType('string', $string);
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Formatter\FeedRssFormatter::toDom
     */
    public function testToDom()
    {
        $element = $this->object->toDom($this->feed);

        $this->assertInstanceOf("\DomDocument", $element);
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Formatter\FeedRssFormatter::getRootElement
     */
    public function testGetRootElement()
    {
        $element = $this->object->getRootElement();

        $this->assertInstanceOf("\DomDocument", $element);
        $this->assertEquals('rss', $element->firstChild->nodeName);
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Formatter\FeedRssFormatter::setMetas
     */
    public function testSetMetas()
    {
        $element = $this->object->getRootElement();

        $this->object->setMetas($element, $this->feed);
        $this->assertInstanceOf("\DomDocument", $element);
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Formatter\FeedRssFormatter::setEntries
     * @covers Debril\RssAtomBundle\Protocol\Formatter\FeedRssFormatter::addEntry
     */
    public function testSetEntries()
    {
        $element = $this->object->getRootElement();

        $this->object->setEntries($element, $this->feed);

        foreach ($element->childNodes as $entry)
        {
            $this->assertInstanceOf("\DomNode", $entry);
        }
    }

}