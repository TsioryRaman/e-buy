<?php

namespace App\Infrastructure\payment;

use App\Domain\Cart\Cart;

interface PaymentInterface
{
    public function startPayment(Cart $cart);
}