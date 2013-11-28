<?php

namespace LuckyBox\Card;

class CardCollection
{

    private $totalRate = 0;

    /** @var Card[] */
    private $cards = array();

    public function add(Card $card)
    {
        $this->cards[] = $card;
        $this->totalRate += $card->getRate();
    }

    public function isEmpty()
    {
        return empty($this->cards);
    }

    public function clear()
    {
        $this->cards = array();
        $this->totalRate = 0;
    }

    public function remove(Card $card)
    {
        $this->cards = array_values(array_filter($this->cards, function($value) use ($card) {
            return $card !== $value;
        }));
        $this->totalRate -= $card->getRate();
    }

    public function find($position)
    {
        $current = 0;

        foreach ($this->cards as $card) {
            $current += $card->getRate();

            if ($position < $current) {
                return $card;
            }
        }

        return null;
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function getTotalRate()
    {
        return $this->totalRate;
    }

    public function getCardCount()
    {
        return count($this->cards);
    }

}
