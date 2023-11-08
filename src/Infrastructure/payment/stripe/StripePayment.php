<?php

namespace App\Infrastructure\payment\stripe;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartData;
use App\Infrastructure\payment\PaymentInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripePayment implements PaymentInterface
{
    public function __construct(
        private readonly string $stripeSecretKey,
        private readonly string $webhookSecretKey,
        private readonly UrlGeneratorInterface $generator
    )
    {
        Stripe::setApiKey($this->stripeSecretKey);
        Stripe::setApiVersion('2023-10-16');
    }

    public function startPayment(Cart $cart)
    {
        $session = Session::create([
            'line_items' => [
                $this->getListItems($cart->getCartArticles())
            ],
            'mode' => 'payment',
            'success_url' => $this->generator->generate('payment.success',[],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generator->generate('cart.show',[],UrlGeneratorInterface::ABSOLUTE_URL),
            'billing_address_collection' => 'required',
            'shipping_address_collection' => [
                'allowed_countries' => ['FR']
            ],
            'metadata' => [
                'cart_id' => $cart->getId()
            ]
        ]);
        return new RedirectResponse($session->url, Response::HTTP_SEE_OTHER);
    }

    private function getListItems(PersistentCollection $value): array
    {
        return $value->map(fn($cartArticle) => [
            'quantity' => $cartArticle->getQuantity(),
            'price_data' => [
                'currency' => 'EUR',
                'product_data' => [
                    'name' => $cartArticle->getArticle()->getName(),
                    'description' => $cartArticle->getArticle()->getDescription(),
                ],
                'unit_amount' => $cartArticle->getArticle()->getPrice()
            ]
        ])->toArray();
    }

    public function handle(Request $request)
    {
        $signature = $request->headers->get('stripe-signature');
        $body = (string)$request->getContent();
        file_put_contents('/public/checkout.signature',serialize($body));
        $event = Webhook::constructEvent(
            $body,
            $signature,
            $this->webhookSecretKey
        );
        if($event->type === 'checkout.session.completed')
        {
        }

    }

}