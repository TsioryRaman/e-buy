<?php

namespace App\Domain\Cart;

class CartData
{
    public int $id;

    public int $quantity;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CartData
     */
    public function setId(int $id): CartData
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return CartData
     */
    public function setQuantity(int $quantity): CartData
    {
        $this->quantity = $quantity;
        return $this;
    }


}