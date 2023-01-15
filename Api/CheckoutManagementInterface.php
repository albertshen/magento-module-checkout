<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Api;

use AlbertMage\Checkout\Api\Data\ShippingAddressInterface;

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
     * @param \AlbertMage\Checkout\Api\Data\CartItemInterface[] $cartItems
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCartTotals($customerId, $cartItems);

    /**
     * Get quote totals.
     *
     * @param int $cartId
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getQuoteTotals($cartId);

    /**
     * Save PaymentInformation And PlaceOrder
     *
     * @param int $cartId
     * @param \AlbertMage\Checkout\Api\Data\ShippingAddressInterface $address
     * @param string paymentMethod
     * @return int Order ID.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function placeOrder($cartId, ShippingAddressInterface $address, $paymentMethod);
}