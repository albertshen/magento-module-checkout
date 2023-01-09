<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Api;

/**
 * CheckoutManagement Interface
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface CheckoutManagementInterface
{
    /**
     * Get cart totals.
     *
     * @param int $customerId
     * @param int $cartId
     * @param \AlbertMage\Checkout\Api\Data\CartItemInterface[] $cartItems
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCartTotals($customerId, $cartId, $cartItems);
}