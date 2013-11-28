<?php

namespace LuckyBox;

use LuckyBox\Card\Card;
use LuckyBox\Card\CardCollection;

/**
 * LuckyBox
 */
class LuckyBox
{

    /** @var CardCollection */
    private $cardCollection = null;

    private $consumable = false;

    public function __construct(CardCollection $cardCollection = null)
    {
        if (is_null($cardCollection)) {
            $cardCollection = new CardCollection();
        }
        $this->cardCollection = $cardCollection;
    }

    /**
     * @param CardCollection $cardCollection
     */
    public function setCardCollection(CardCollection $cardCollection)
    {
        $this->cardCollection = $cardCollection;
    }

    /**
     * @return CardCollection
     */
    public function getCardCollection()
    {
        return $this->cardCollection;
    }

    /**
     * Returns the card with the specified rate of cards, or null if LuckyBox contains no cards.
     *
     * @return Card
     */
    public function draw()
    {
        $position = $this->getRandomPosition();

        if ($position === null) {
            return null;
        }

        $card = $this->cardCollection->find($position);

        if ($card !== null && $this->consumable) {
            $this->remove($card);
        }

        return $card;
    }

    /**
     * Adds the specified card to this LuckyBox.
     *
     * @param Card $card
     */
    public function add(Card $card)
    {
        $this->cardCollection->add($card);
    }

    /**
     * Removes the specified card from this LuckyBox if it is present.
     *
     * @param Card $card
     */
    public function remove(Card $card)
    {
        $this->cardCollection->remove($card);
    }

    /**
     * Removes all of the cards from this LuckyBox.
     */
    public function clear()
    {
        $this->cardCollection->clear();
    }

    /**
     * Returns true if this LuckyBox contains no cards.
     * @return bool
     */
    public function isEmpty()
    {
        return $this->cardCollection->isEmpty();
    }

    /**
     * @param bool $consumable
     */
    public function setConsumable($consumable)
    {
        $this->consumable = $consumable;
    }

    /**
     * @return bool
     */
    public function isConsumable()
    {
        return $this->consumable;
    }

    /**
     * @return integer
     */
    protected function getRandomPosition()
    {
        $totalRate = $this->cardCollection->getTotalRate();

        if ($totalRate < 1) {
            return null;
        } else {
            return mt_rand(0, $totalRate - 1);
        }
    }

}
