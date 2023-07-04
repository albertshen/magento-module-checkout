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

    const DEFAULT_DILIVERY_METHOD = 'sfshipping';

    const DEFAULT_POST_CODE = '000000';

    /**
     * Get mine cart totals.
     *
     * @param int $customerId
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMineCartTotals($customerId);

    /**
     * Get guest cart totals.
     *
     * @param string $guestToken
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getGuestCartTotals($guestToken);

    /**
     * Update mine cart totals.
     *
     * @param int $customerId
     * @param \AlbertMage\Checkout\Api\Data\CartItemInterface[] $cartItems
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateMineCartTotals($customerId, $cartItems);

    /**
     * Update guest cart totals.
     *
     * @param string $guestToken
     * @param \AlbertMage\Checkout\Api\Data\CartItemInterface[] $cartItems
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateGuestCartTotals($guestToken, $cartItems);

    /**
     * Get mine quote totals.
     *
     * @param int $cartId
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMineQuoteTotals($cartId);

    /**
     * Get guest quote totals.
     *
     * @param string $guestToken
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getGuestQuoteTotals($guestToken);

    /**
     * Place Order
     *
     * @param int $cartId
     * @param \AlbertMage\Checkout\Api\Data\ShippingAddressInterface $address
     * @param string paymentMethod
     * @return \AlbertMage\Sales\Api\Data\OrderInterface Order.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function minePlaceOrder($cartId, ShippingAddressInterface $address, $paymentMethod);

    /**
     * Place Order
     *
     * @param string $guestToken
     * @param \AlbertMage\Checkout\Api\Data\ShippingAddressInterface $address
     * @param string paymentMethod
     * @return \AlbertMage\Sales\Api\Data\OrderInterface Order.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function guestPlaceOrder($guestToken, ShippingAddressInterface $address, $paymentMethod);

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