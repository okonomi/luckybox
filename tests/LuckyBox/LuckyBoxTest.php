<?php

namespace LuckyBox\Tests;

use LuckyBox\LuckyBox;
use LuckyBox\Card\IdCard;

class LuckyBoxTest extends \PHPUnit_Framework_TestCase
{

    public function testDraw_Functional()
    {
        $card1 = new IdCard();
        $card1->setId(1);
        $card1->setRate(55);

        $card2 = new IdCard();
        $card2->setId(2);
        $card2->setRate(45);

        $box = new LuckyBox();
        $box->add($card1);
        $box->add($card2);

        for ($i = 0 ; $i < 3; $i++) {
            $result = $box->draw();
            $this->assertTrue($result === $card1 || $result === $card2);
        }

        $this->assertFalse($box->isEmpty());
        $box->clear();
        $this->assertTrue($box->isEmpty());

        $box->add($card1);
        $box->add($card2);
        $box->setConsumable(true);

        while (!$box->isEmpty()) {
            $result = $box->draw();
            $this->assertTrue($result === $card1 || $result === $card2);
        }

        $box->clear();
        $this->assertTrue($box->isEmpty());
        $box->add($card1);
        $this->assertFalse($box->isEmpty());
        $box->remove($card1);
        $this->assertTrue($box->isEmpty());
    }

    public function testDraw_RandomPositionIsNull()
    {
        $box = $this->getMockBuilder('LuckyBox\LuckyBox')
            ->disableOriginalConstructor()
            ->setMethods(array('getRandomPosition'))
            ->getMock();
        $box->expects($this->once())
            ->method('getRandomPosition')
            ->will($this->returnValue(null));
        $this->assertNull($box->draw());
    }

    public function testDraw_CardIsNull()
    {
        $collection = $this->getMockBuilder('LuckyBox\Card\CardCollection')
            ->setMethods(array('find'))
            ->getMock();
        $collection->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null));

        $box = $this->getMockBuilder('LuckyBox\LuckyBox')
            ->disableOriginalConstructor()
            ->setMethods(array('getRandomPosition'))
            ->getMock();
        $box->expects($this->once())
            ->method('getRandomPosition')
            ->will($this->returnValue(1));
        $box->setCardCollection($collection);
        $this->assertNull($box->draw());
    }

    public function testDraw_ReturnsCard()
    {
        $card = new IdCard();

        $collection = $this->getMockBuilder('LuckyBox\Card\CardCollection')
            ->setMethods(array('find'))
            ->getMock();
        $collection->expects($this->once())
            ->method('find')
            ->will($this->returnValue($card));

        $box = $this->getMockBuilder('LuckyBox\LuckyBox')
            ->disableOriginalConstructor()
            ->setMethods(array('getRandomPosition'))
            ->getMock();
        $box->expects($this->once())
            ->method('getRandomPosition')
            ->will($this->returnValue(1));
        $box->setCardCollection($collection);
        $this->assertEquals($card, $box->draw());
    }

    public function testDraw_ReturnsCardAndNotConsumable()
    {
        $card = new IdCard();

        $collection = $this->getMockBuilder('LuckyBox\Card\CardCollection')
            ->setMethods(array('find', 'remove'))
            ->getMock();
        $collection->expects($this->once())
            ->method('find')
            ->will($this->returnValue($card));
        $collection->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($card));

        $box = $this->getMockBuilder('LuckyBox\LuckyBox')
            ->disableOriginalConstructor()
            ->setMethods(array('getRandomPosition'))
            ->getMock();
        $box->expects($this->once())
            ->method('getRandomPosition')
            ->will($this->returnValue(1));
        $box->setConsumable(true);
        $box->setCardCollection($collection);
        $this->assertEquals($card, $box->draw());
    }

    public function testIsEmpty()
    {
        $box = new LuckyBox();
        $this->assertTrue($box->isEmpty());
        $box->add(new IdCard());
        $this->assertFalse($box->isEmpty());
    }

    public function testSetConsumable()
    {
        $box = new LuckyBox();
        $rp = new \ReflectionProperty($box, 'consumable');
        $rp->setAccessible(true);
        $box->setConsumable(false);
        $this->assertFalse($rp->getValue($box));
        $box->setConsumable(true);
        $this->assertTrue($rp->getValue($box));
    }

    public function testIsConsumable()
    {
        $box = new LuckyBox();
        $rp = new \ReflectionProperty($box, 'consumable');
        $rp->setAccessible(true);
        $rp->setValue($box, true);
        $this->assertTrue($box->isConsumable());
        $rp->setValue($box, false);
        $this->assertFalse($box->isConsumable());
    }

    public function testGetRandomPosition_TotalRateIsLessThan1()
    {
        $collection = $this->getMockBuilder('LuckyBox\Card\CardCollection')
            ->setMethods(array('getTotalRate'))
            ->getMock();
        $collection->expects($this->once())
            ->method('getTotalRate')
            ->will($this->returnValue(0));

        $box = new LuckyBox();
        $box->setCardCollection($collection);
        $rm = new \ReflectionMethod($box, 'getRandomPosition');
        $rm->setAccessible(true);
        $this->assertNull($rm->invoke($box));
    }

    public function testGetRandomPosition_ReturnsRandomValueBetween0AndTotalRate()
    {
        $collection = $this->getMockBuilder('LuckyBox\Card\CardCollection')
            ->setMethods(array('getTotalRate'))
            ->getMock();
        $collection->expects($this->once())
            ->method('getTotalRate')
            ->will($this->returnValue(1));

        $box = new LuckyBox();
        $box->setCardCollection($collection);
        $rm = new \ReflectionMethod($box, 'getRandomPosition');
        $rm->setAccessible(true);
        $this->assertEquals(0, $rm->invoke($box));
    }

}
