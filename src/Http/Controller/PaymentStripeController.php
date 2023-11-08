<?php

namespace App\Http\Controller;

use App\Domain\Auth\User;
use App\Domain\Cart\service\CartArticleService;
use App\Domain\Cart\service\CartService;
use App\Infrastructure\payment\PaymentInterface;
use App\Infrastructure\payment\stripe\StripePayment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class PaymentStripeController extends AbstractController
{
    public function __construct(
        private readonly CartArticleService $service,
        private readonly StripePayment $payment,
        private readonly CartService $cartService
    )
    {

    }

    #[Route(path: '/payment', name: 'cart.pay')]
    public function payCart()
    {
        /** @var User $user */
        $user = $this->getUser();
        $carts = $this->service->getCurrentUserCart($user);
        return $this->payment->startPayment($carts);
    }

    #[Route(path: '/payment/success', name: 'payment.success')]
    public function paymentSuccess(): RedirectResponse
    {
        $this->cartService->submitCartUser($this->getUser());
        $this->cartService->deleteCartFromSession();
        $this->addFlash('success','Votre paiement a bien ete pris en compte');
        return $this->redirectToRoute('home.index');
    }

    #[Route(path: '/webhook', name: 'webhook')]
    public function webhook(Request $request):void
    {
        $this->payment->handle($request);
        return;
    }

}