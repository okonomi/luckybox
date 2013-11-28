<?php

namespace LuckyBox\Card;

/**
 * CardCollection
 */
class CardCollection
{

    /** @var integer */
    private $totalRate = 0;

    /** @var Card[] */
    private $cards = array();

    /**
     * Adds the specified card to this collection.
     *
     * @param Card $card
     */
    public function add(Card $card)
    {
        $this->cards[] = $card;
        $this->totalRate += $card->getRate();
    }

    /**
     * Returns true if this collection contains no cards.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->cards);
    }

    /**
     * Removes all of the cards.
     */
    public function clear()
    {
        $this->cards = array();
        $this->totalRate = 0;
    }

    /**
     * Removes the specified card from this collection if it is present.
     *
     * @param Card $card
     */
    public function remove(Card $card)
    {
        $this->cards = array_values(array_filter($this->cards, function($value) use ($card) {
            return $card !== $value;
        }));
        $this->totalRate -= $card->getRate();
    }

    /**
     * Looking for a card from the rate.
     *
     * @param integer $position
     * @return Card|null
     */
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

    /**
     * Returns cards.
     *
     * @return Card[]
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Returns total value of rate of cards.
     *
     * @return integer
     */
    public function getTotalRate()
    {
        return $this->totalRate;
    }

    /**
     * Returns count of cards.
     *
     * @return integer
     */
    public function getCardCount()
    {
        return count($this->cards);
    }

}
