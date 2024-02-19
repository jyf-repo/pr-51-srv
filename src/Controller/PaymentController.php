<?php

namespace App\Controller;

use App\Service\IngenicoSDK_Bundle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/api/payment', name: 'api_payment')]
    public function index(Request $request, IngenicoSDK_Bundle $ingenicoSDK_Bundle): Response
    {

        $data = $request->getContent();
        $data = json_decode($data);

        $price = 100*($data->amount);
        $paymentProductId = $data->cardType;
        $cardHolderName = $data->cardHolderName;
        $country = $data->country;
        $cardNumber = $data->cardNumber;
        $expiryDate = $data->expiryDate;
        $cvv = $data->cvv;

        $payment = $ingenicoSDK_Bundle->paymentClient($price, $paymentProductId, $cardHolderName, $country, $cardNumber, $expiryDate, $cvv);
        $result = $payment->toJson();

        return new JsonResponse($result);

    }
}
