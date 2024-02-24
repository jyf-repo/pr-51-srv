<?php

namespace App\Service;

use OnlinePayments\Sdk\Client;
use OnlinePayments\Sdk\Communicator;
use OnlinePayments\Sdk\CommunicatorConfiguration;
use OnlinePayments\Sdk\DeclinedPaymentException;
use OnlinePayments\Sdk\DefaultConnection;
use OnlinePayments\Sdk\Domain\Address;
use OnlinePayments\Sdk\Domain\AmountOfMoney;
use OnlinePayments\Sdk\Domain\Card;
use OnlinePayments\Sdk\Domain\CardPaymentMethodSpecificInput;
use OnlinePayments\Sdk\Domain\CreatePaymentRequest;
use OnlinePayments\Sdk\Domain\Customer;
use OnlinePayments\Sdk\Domain\Order;
use OnlinePayments\Sdk\Domain\OrderReferences;
use OnlinePayments\Sdk\Domain\ThreeDSecure;
use PhpParser\Error;

class IngenicoSDK_Bundle
{
    private $uriResource;
    private $merchantId;
    private $apiKey;
    private $apiSecret;


    public function __construct( $uriResource, $merchantId, $apiKey, $apiSecret){

        $this->uriResource = $uriResource;

        $this->merchantId = $merchantId;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function merchantClient(){

        $connection = new DefaultConnection();

        // Your PSPID in either our test or live environment
        $merchantId = $this->merchantId;

        // Put the value of the API Key which you can find on the Back Office page
        // https://secure.ogone.com/Ncol/Test/Backoffice/login/
        $apiKey = $this->apiKey;

        // Put the value of the API Secret which you can find on the Back Office page
        // https://secure.ogone.com/Ncol/Prod/BackOffice/login/
        $apiSecret = $this->apiSecret;
        // This endpoint is pointing to the TEST server
        // Note: Use the endpoint without the /v2/ part here
        $apiEndpoint = $this->uriResource;

        // Additional settings to easily identify your company in our logs.
        $integrator = 'Granpharma';

        $proxyConfiguration = null;
        /*
        * To use proxy, you should uncomment the section below
        * and replace proper settings with your settings of the proxy.
        * (additionally, you can comment on the previous setting).
        */
        /*
        $proxyConfiguration = new ProxyConfiguration(
            'proxyHost',
            'proxyPort',
            'proxyUserName',
            'proxyPassword'
        );
        */
        $communicatorConfiguration = new CommunicatorConfiguration(
            $apiKey,
            $apiSecret,
            $apiEndpoint,
            $integrator,
            $proxyConfiguration
        );

        $communicator = new Communicator($connection, $communicatorConfiguration);

        $client = new Client($communicator);

        $merchantClient = $client->merchant($merchantId);
        //dd($merchantClient);
        return $merchantClient;
    }


    public function paymentClient($price, $paymentProductId, $cardHolderName, $country, $cardNumber, $expiryDate, $cvv){
        /*
         * The PaymentsClient object based on the MerchantClient
         * object created in initialisation
         */
        $paymentsClient = $this->merchantClient()->payments();
        //dump($paymentsClient);
        $createPaymentRequest = new CreatePaymentRequest();

        $order = new Order();

        // Example object of the AmountOfMoney
        $amountOfMoney = new AmountOfMoney();
        $amountOfMoney->setCurrencyCode("EUR");
        $amountOfMoney->setAmount($price);
        $order->setAmountOfMoney($amountOfMoney);

        //customer
        $customer = new Customer();

        $billingAddress = new Address();
        $billingAddress->setCountryCode($country);

        $customer->setBillingAddress($billingAddress);
        $order->setCustomer($customer);


        $cardPaymentMethodSpecificInput =
            new CardPaymentMethodSpecificInput();

        //references
        $references =new OrderReferences();
        $references->setMerchantReference('Piluliers');
        $order->setReferences($references);

        $cardPaymentMethodSpecificInput->setAuthorizationMode('FINAL_AUTHORIZATION');
        $cardPaymentMethodSpecificInput->setIsRecurring(false);
        $cardPaymentMethodSpecificInput->setTransactionChannel('ECOMMERCE');

        // 3D secure

        $threeDsecure = new ThreeDSecure();
        $threeDsecure->setSkipAuthentication(true);
        $cardPaymentMethodSpecificInput->setThreeDSecure($threeDsecure);

        // Example object of the Card – you can find test cards on
        // https://support.direct.ingenico.com/documentation/test-cases/
        $card = new Card();
        $card->setCvv($cvv);
        $card->setCardNumber($cardNumber); // // Find more test data here
        $card->setExpiryDate($expiryDate);
        $card->setCardholderName($cardHolderName);

        $cardPaymentMethodSpecificInput->setCard($card);
        //dump($card);
        $cardPaymentMethodSpecificInput->setPaymentProductId($paymentProductId);
        $createPaymentRequest->setCardPaymentMethodSpecificInput(
            $cardPaymentMethodSpecificInput
        );
        $createPaymentRequest->setOrder(
            $order
        );
        //dd($createPaymentRequest);
try{
        // Get the response for the PaymentsClient
        $createPaymentResponse =
            $paymentsClient->createPayment($createPaymentRequest);
        // Here you get $paymentId that you can use in further code
        $paymentID = $createPaymentResponse->getPayment()->getId();
        //dump($paymentID);
        // Get the PaymentResponse object with status information
        // concerning payment of the given ID


        $paymentResponse = $this->merchantClient()
            ->payments()
            ->getPayment($paymentID);
        //dd($paymentResponse);

        return $paymentResponse;

    } catch (DeclinedPaymentException $e) {
            //dd($e);
            return $e->getHttpStatusCode();
        }
    }
}
