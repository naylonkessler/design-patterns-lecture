<?php

interface CartItem
{
    public function accept(ShoppingCartVisitor $visitor): float;
}

class Product implements CartItem
{
    public function __construct(private float $price) {}

    public function __get(string $key): float
    {
        return $this->{$key};
    }

    public function accept(ShoppingCartVisitor $visitor): float
    {
        return $visitor->visitProduct($this);
    }
}

class DiscountedProduct implements CartItem
{
    public function __construct(private float $price, private float $discount) {}

    public function __get(string $key): float
    {
        return $this->{$key};
    }

    public function accept(ShoppingCartVisitor $visitor): float
    {
        return $visitor->visitDiscountedProduct($this);
    }
}

interface ShoppingCartVisitor
{
    public function visitProduct(Product $product): float;
    public function visitDiscountedProduct(DiscountedProduct $product): float;
}

class ShoppingCart implements ShoppingCartVisitor
{
    public function visitProduct(Product $product): float
    {
        return $product->price;
    }

    public function visitDiscountedProduct(DiscountedProduct $product): float
    {
        return $product->price * (1 - $product->discount);
    }
}

$products = [
    new Product(100),
    new DiscountedProduct(150, 0.2),
];

$cart = new ShoppingCart();
$total = array_reduce($products, fn ($sum, $one) => $sum + $one->accept($cart));

echo PHP_EOL;
echo ":: The total price of cart is $ {$total}" . PHP_EOL;
echo PHP_EOL;
