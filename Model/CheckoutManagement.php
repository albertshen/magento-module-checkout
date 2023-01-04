<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Model;

use AlbertMage\Checkout\Api\CheckoutManagementInterface;

/**
 * Class CheckoutManagement
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class CheckoutManagement implements CheckoutManagementInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCartTotals($customerId, $cartItems)
    {
        //var_dump($customerId, $cart->getCartItems());exit;
        foreach($cartItems as $a) {
            var_dump($a->getItemId());
        }
        exit;
    }
}