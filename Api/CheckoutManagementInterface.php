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

    const DEFAULT_COUNTRY_ID = 'CN';

    const DEFAULT_DILIVERY_METHOD = 'flatrate';

    const DEFAULT_POST_CODE = '000000';

    /**
     * Get cart totals.
     *
     * @param int $customerId
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCartTotals($customerId);

    /**
     * Update cart totals.
     *
     * @param int $customerId
     * @param \AlbertMage\Checkout\Api\Data\CartItemInterface[] $cartItems
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateCartTotals($customerId, $cartItems);

    /**
     * Get quote totals.
     *
     * @param int $cartId
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getQuoteTotals($cartId);

    /**
     * One step checkout
     *
     * @param int $cartId
     * @param \AlbertMage\Checkout\Api\Data\ShippingAddressInterface $address
     * @param string paymentMethod
     * @return int Order ID.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function placeOrder($cartId, ShippingAddressInterface $address, $paymentMethod);

    /**
     * One Step Checkout
     *
     * @param int $customerId
     * @param string $sku
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function oneStepCheckout($customerId, $sku);
}