<?php

namespace LuckyBox\Tests\Card;

use LuckyBox\Card\CardCollection;
use LuckyBox\Card\IdCard;

class CardCollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testGetTotalRate_empty()
    {
        $collection = new CardCollection();
        $this->assertEquals(0, $collection->getTotalRate());
    }

    public function testGetTotalRate_oneCard()
    {
        $card = $this->getMock('LuckyBox\\Card\\Card');
        $card->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(1));

        $collection = new CardCollection();
        $collection->add($card);
        $this->assertEquals(1, $collection->getTotalRate());
    }

    public function testGetCardCount()
    {
        $collection = new CardCollection();
        $this->assertEquals(0, $collection->getCardCount());

        $card = $this->getMock('LuckyBox\\Card\\Card');
        $card->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(1));

        $collection->add($card);
        $this->assertEquals(1, $collection->getCardCount());
    }

    public function testIsEmpty()
    {
        $collection = new CardCollection();
        $this->assertTrue($collection->isEmpty());

        $card = $this->getMock('LuckyBox\\Card\\Card');
        $card->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(1));

        $collection->add($card);
        $this->assertFalse($collection->isEmpty());
    }

    public function testClear()
    {
        $card = new IdCard();
        $card->setRate(10);
        $collection = new CardCollection();
        $collection->add($card);

        $rp1 = new \ReflectionProperty($collection, 'cards');
        $rp1->setAccessible(true);
        $rp2 = new \ReflectionProperty($collection, 'totalRate');
        $rp2->setAccessible(true);

        $this->assertCount(1, $collection->getCards());
        $this->assertEquals(10, $collection->getTotalRate());

        $collection->clear();

        $this->assertCount(0, $collection->getCards());
        $this->assertEquals(0, $collection->getTotalRate());
    }

    public function testRemove()
    {
        $card1 = new IdCard();
        $card1->setRate(10);
        $card2 = new IdCard();
        $card2->setRate(90);
        $collection = new CardCollection();
        $collection->add($card1);
        $collection->add($card2);
        $collection->remove($card1);

        $this->assertEquals(array($card2), $collection->getCards());
        $this->assertEquals(90, $collection->getTotalRate());
    }

    public function testFind()
    {
        $collection = new CardCollection();

        $card1 = $this->getMock('LuckyBox\\Card\\Card');
        $card1->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(1));
        $collection->add($card1);

        $card2 = $this->getMock('LuckyBox\\Card\\Card');
        $card2->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(2));
        $collection->add($card2);

        $this->assertSame($card1, $collection->find(0));
    }

    public function testFind_CardIsFound()
    {
        $card = new IdCard();
        $card->setRate(1);
        $collection = new CardCollection();
        $collection->add($card);
        $this->assertEquals($card, $collection->find(0));
    }

    public function testFind_CardIsFoundWithMultipleCards()
    {
        $card1 = new IdCard();
        $card1->setRate(1);
        $card2 = new IdCard();
        $card2->setRate(1);
        $collection = new CardCollection();
        $collection->add($card1);
        $collection->add($card2);
        $this->assertEquals($card1, $collection->find(0));
        $this->assertEquals($card2, $collection->find(1));
        $this->assertNull($collection->find(2));
    }

    public function testFind_CardIsNotFound()
    {
        $card = new IdCard();
        $card->setRate(0);
        $collection = new CardCollection();
        $collection->add($card);
        $this->assertNull($collection->find(0));
    }

    public function testAdd()
    {
        $card1 = new IdCard();
        $card1->setRate(10);
        $card2 = new IdCard();
        $card2->setRate(90);

        $collection = new CardCollection();

        $this->assertEquals(array(), $collection->getCards());
        $this->assertEquals(0, $collection->getTotalRate());

        $collection->add($card1);
        $this->assertEquals(array($card1), $collection->getCards());
        $this->assertEquals(10, $collection->getTotalRate());

        $collection->add($card2);
        $this->assertEquals(array($card1, $card2), $collection->getCards());
        $this->assertEquals(100, $collection->getTotalRate());
    }

}
