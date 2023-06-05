<?php

interface PaymentProcessor
{
    public function processPayment(float $amount): bool;
}

class CreditCardProcessor implements PaymentProcessor
{
    public function processPayment(float $amount): bool
    {
        echo "Processing payment of $ $amount with credit card.";
        return true;
    }
}

class BilletProcessor implements PaymentProcessor
{
    public function processPayment(float $amount): bool
    {
        echo "Processing payment of $ $amount with billet.";
        return true;
    }
}

abstract class PaymentManager
{
    public abstract function createPaymentProcessor(): PaymentProcessor;

    public function processPayment(float $amount): bool
    {
        return $this->createPaymentProcessor()->processPayment($amount);
    }
}

class CreditCardPaymentManager extends PaymentManager
{
    public function createPaymentProcessor(): PaymentProcessor
    {
        return new CreditCardProcessor();
    }
}

class BilletPaymentManager extends PaymentManager
{
    public function createPaymentProcessor(): PaymentProcessor
    {
        return new BilletProcessor();
    }
}

class Client
{
    public function __construct(private PaymentManager $paymentManager) {}

    public function processPayment(float $amount): void
    {
        $this->paymentManager->processPayment($amount);
    }
}

echo PHP_EOL;
$ccClient = new Client(new CreditCardPaymentManager());
$ccClient->processPayment(146.00);
echo PHP_EOL . PHP_EOL;

$billetClient = new Client(new BilletPaymentManager());
$billetClient->processPayment(213.40);
echo PHP_EOL . PHP_EOL;
