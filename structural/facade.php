<?php

class PurchaseSummarizer
{
    public function summarize(array $items): array
    {
        echo ':: Summarizing items of purchase' . PHP_EOL;

        return [
            'total' => array_sum(array_column($items, 'value')),
            'items' => array_sum(array_column($items, 'amount')),
        ];
    }
}

class PaymentProcessor
{
    public function processPayment(float $amount): void
    {
        echo ":: Processing payment of $ {$amount}" . PHP_EOL;
    }
}

class InventoryManager
{
    public function checkStock(string $product, int $amount): bool
    {
        echo ":: Checking stock of product: {$product} [{$amount}]" . PHP_EOL;

        return true;
    }
}

class InvoiceGenerator
{
    public function generateInvoice(array $items, array $summary): void
    {
        echo ':: Generating invoice ...'. PHP_EOL;
        echo '-----------------------------------------------' . PHP_EOL;
        echo "Total value: {$summary['total']}" . PHP_EOL;
        echo "Items count: {$summary['items']}" . PHP_EOL;
        echo '-----------------------------------------------' . PHP_EOL;
        echo implode(PHP_EOL, array_column($items, 'product')) . PHP_EOL;
        echo '-----------------------------------------------' . PHP_EOL;
    }
}

class Purchases
{
    public function __construct(
        private PurchaseSummarizer $purchaseSummarizer = new PurchaseSummarizer(),
        private PaymentProcessor $paymentProcessor = new PaymentProcessor(),
        private InventoryManager $inventoryManager = new InventoryManager(),
        private InvoiceGenerator $invoiceGenerator = new InvoiceGenerator()
    ) {}

    public function confirmPurchase(array $items): void
    {
        $summary = $this->purchaseSummarizer->summarize($items);
        array_walk($items, [$this, 'checkItemStock']);
        $this->paymentProcessor->processPayment($summary['total']);
        $this->invoiceGenerator->generateInvoice($items, $summary);

        echo ":: Purchased confirmed with success." . PHP_EOL;
    }

    protected function checkItemStock(array $item)
    {
        $inStock = $this->inventoryManager->checkStock($item['product'], $item['amount']);

        if (!$inStock) {
            throw new Exception(
                "No stock available for '{$item['amount']}' item(s) of '{$item['product']}']"
            );
        }
    }
}

$items = [
    ['product' => 'Desktop Gamer Alienware Aurora', 'amount' => 1, 'value' => 2399.96],
    ['product' => 'Monitor Gamer Samsung Odyssey 49 QLED', 'amount' => 1, 'value' => 1063.50],
    ['product' => 'Joystick Thrustmaster Hotas Warthog', 'amount' => 1, 'value' => 679.98],
    ['product' => 'Projector BenQ TH585P, 3800 Full-HD', 'amount' => 3, 'value' => 999],
];

$purchases = new Purchases();
echo PHP_EOL;
$purchases->confirmPurchase($items);
echo PHP_EOL;
