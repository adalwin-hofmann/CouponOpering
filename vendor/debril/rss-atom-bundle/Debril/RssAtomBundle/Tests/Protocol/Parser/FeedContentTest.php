<?php

namespace Debril\RssAtomBundle\Protocol\Parser;

use \Debril\RssAtomBundle\Protocol\Parser\Item;
use \Debril\RssAtomBundle\Protocol\Parser\FeedContent;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-26 at 23:10:14.
 */
class FeedContentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FeedContent
     */
    protected $object;

    const title = 'feed title';
    const subtitle = 'feed subtitle';
    const id = 'feed id';
    const link = 'http://example.com';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new FeedContent;

        $this->object->setPublicId(self::id);
        $this->object->setLink(self::link);
        $this->object->setTitle(self::title);
        $this->object->setDescription(self::subtitle);
        $this->object->setLastModified(new \DateTime);

        for ($i = 0; $i < 5; $i++)
        {
            $item = new Item();
            $item->setPublicId($i);
            $this->object->addItem($item);
        }

        $lastModified = new \DateTime();

        $this->object->setLastModified($lastModified);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::getLastModified
     * @todo   Implement testGetLastModified().
     */
    public function testGetLastModified()
    {
        $this->assertInstanceOf("\DateTime", $this->object->getLastModified());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::setLastModified
     * @todo   Implement testSetLastModified().
     */
    public function testSetLastModified()
    {
        $lastModified = \DateTime::createFromFormat('j-M-Y', '15-Feb-2013');

        $this->object->setLastModified($lastModified);

        $this->assertEquals($lastModified, $this->object->getLastModified());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::getTitle
     * @todo   Implement testGetTitle().
     */
    public function testGetTitle()
    {
        $this->assertEquals(self::title, $this->object->getTitle());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::setTitle
     * @todo   Implement testSetTitle().
     */
    public function testSetTitle()
    {
        $newTitle = 'new Feed Title';

        $this->object->setTitle($newTitle);

        $this->assertEquals($newTitle, $this->object->getTitle());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::getDescription
     * @todo   Implement testgetDescription().
     */
    public function testgetDescription()
    {
        $this->assertEquals(self::subtitle, $this->object->getDescription());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::setDescription
     * @todo   Implement testsetDescription().
     */
    public function testsetDescription()
    {
        $newSubTitle = 'new subtitle';

        $this->object->setDescription($newSubTitle);

        $this->assertEquals($newSubTitle, $this->object->getDescription());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::getLink
     * @todo   Implement testGetLink().
     */
    public function testGetLink()
    {
        $this->assertEquals(self::link, $this->object->getLink());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::setLink
     * @todo   Implement testSetLink().
     */
    public function testSetLink()
    {
        $newLink = 'http://newlink.com';

        $this->object->setLink($newLink);

        $this->assertEquals($newLink, $this->object->getLink());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::getPublicId
     */
    public function testGetPublicId()
    {
        $this->assertEquals(self::id, $this->object->getPublicId());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::setPublicId
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::getPublicId
     */
    public function testSetPublicId()
    {
        $newId = '5';

        $this->object->setPublicId($newId);

        $this->assertEquals($newId, $this->object->getPublicId());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::getItems
     * @todo   Implement testGetItemsCount().
     */
    public function testGetItems()
    {
        $items = $this->object->getItems();
        $item = current($items);

        $this->assertInternalType('array', $items);
        $this->assertInstanceOf('Debril\RssAtomBundle\Protocol\ItemIn', $item);
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::getItemsCount
     * @todo   Implement testGetItemsCount().
     */
    public function testGetItemsCount()
    {
        $count = count($this->object->getItems());

        $this->assertEquals($count, $this->object->getItemsCount());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser\FeedContent::addItem
     */
    public function testAddItem()
    {
        $count = $this->object->getItemsCount();

        $ret = $this->object->addItem(new Item());

        $this->assertInstanceOf("Debril\RssAtomBundle\Protocol\Parser\FeedContent", $ret);
        $this->assertEquals($count + 1, $this->object->getItemsCount());
    }

}
