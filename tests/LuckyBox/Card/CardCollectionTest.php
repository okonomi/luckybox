<?php

namespace LuckyBox\Tests\Card;

use LuckyBox\Card\CardCollection;

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
        $collection = new CardCollection();
        $this->assertTrue($collection->isEmpty());

        $card = $this->getMock('LuckyBox\\Card\\Card');
        $card->expects($this->any())
            ->method('getRate')
            ->will($this->returnValue(1));

        $collection->add($card);
        $this->assertFalse($collection->isEmpty());

        $collection->clear();
        $this->assertTrue($collection->isEmpty());
    }

    public function testRemove()
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

        $this->assertEquals(2, $collection->getCardCount());
        $this->assertEquals(3, $collection->getTotalRate());

        $collection->remove($card2);
        $this->assertEquals(1, $collection->getCardCount());
        $this->assertEquals(1, $collection->getTotalRate());
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
}
