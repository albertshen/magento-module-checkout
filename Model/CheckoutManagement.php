<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Model;

use AlbertMage\Checkout\Api\CheckoutManagementInterface;
use AlbertMage\Quote\Api\Data\CartInterface;
use AlbertMage\Quote\Api\Data\CartItemInterface;
use AlbertMage\Quote\Api\CartRepositoryInterface;
use AlbertMage\Quote\Api\CartItemRepositoryInterface;
use AlbertMage\Quote\Api\Data\TotalsInterfaceFactory;
use AlbertMage\Quote\Api\Data\TotalsItemInterfaceFactory;
use AlbertMage\Checkout\Api\Data\ShippingAddressInterface;
use AlbertMage\Customer\Api\Data\SocialAccountInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface as MageCartRepositoryInterface;
use Magento\Quote\Api\Data\CartItemInterfaceFactory as MageCartItemInterfaceFactory;
use Magento\Quote\Api\CartManagementInterfaceFactory;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Checkout\Api\Data\TotalsInformationInterface;
use Magento\Checkout\Api\Data\TotalsInformationInterfaceFactory;
use Magento\Quote\Api\Data\AddressInterfaceFactory;
use Magento\Checkout\Api\ShippingInformationManagementInterfaceFactory;
use Magento\Checkout\Model\ShippingInformationFactory;
use Magento\Checkout\Api\PaymentInformationManagementInterfaceFactory;
use Magento\Quote\Api\Data\PaymentInterfaceFactory;
use AlbertMage\Quote\Model\TotalsManagement;
use Magento\Sales\Api\Data\OrderInterface;
use AlbertMage\Sales\Api\Data\OrderInterfaceFactory;

/**
 * Class CheckoutManagement
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class CheckoutManagement implements CheckoutManagementInterface
{

    /**
     * Quote repository.
     *
     * @var MageCartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var CartInterface
     */
    protected $cart;

    /**
     * Cart repository.
     *
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * Cart item repository.
     *
     * @var CartItemRepositoryInterface
     */
    protected $cartItemRepository;

    /**
     * @var TotalsInterfaceFactory
     */
    protected $totalsInterfaceFactory;

    /**
     * @var TotalsItemInterfaceFactory
     */
    protected $totalsItemInterfaceFactory;

    /**
     * @var MageCartItemInterfaceFactory
     */
    protected $mageCartItemInterfaceFactory;

    /**
     * @var CartManagementInterfaceFactory
     */
    protected $cartManagementInterfaceFactory;

    /**
     * @var CartTotalRepositoryInterface
     */
    protected $cartTotalRepository;

    /**
     * @var TotalsInformationInterfaceFactory
     */
    protected $totalsInformationInterfaceFactory;

    /**
     * @var AddressInterfaceFactory
     */
    protected $addressInterfaceFactory;

    /**
     * @var ShippingInformationManagementInterfaceFactory
     */
    protected $shippingInformationManagementInterfaceFactory;

    /**
     * @var ShippingInformationFactory
     */
    protected $shippingInformationFactory;

    /**
     * @var PaymentInformationManagementInterfaceFactory
     */
    protected $paymentInformationManagementInterfaceFactory;

    /**
     * @var PaymentInterfaceFactory
     */
    protected $paymentInterfaceFactory;

    /**
     * @var SocialAccountInterfaceFactory
     */
    protected $socialAccountInterfaceFactory;

    /**
     * @var TotalsManagement
     */
    protected $totalsManagement;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var OrderInterfaceFactory
     */
    protected $orderInterfaceFactory;

    /**
     * @param MageCartRepositoryInterface $quoteRepository
     * @param CartInterface $cart
     * @param CartRepositoryInterface $cartRepository
     * @param CartItemRepositoryInterface $cartItemRepository
     * @param TotalsInterfaceFactory $totalsInterfaceFactory
     * @param TotalsItemInterfaceFactory $totalsItemInterfaceFactory
     * @param MageCartItemInterfaceFactory $mageCartItemInterfaceFactory
     * @param CartManagementInterfaceFactory $cartManagementInterfaceFactory
     * @param CartTotalRepositoryInterface $cartTotalRepository
     * @param TotalsInformationInterfaceFactory $totalsInformationInterfaceFactory
     * @param AddressInterfaceFactory $addressInterfaceFactory
     * @param ShippingInformationManagementInterfaceFactory $shippingInformationManagementInterfaceFactory
     * @param ShippingInformationFactory $shippingInformationFactory
     * @param PaymentInformationManagementInterfaceFactory $paymentInformationManagementInterfaceFactory
     * @param PaymentInterfaceFactory $paymentInterfaceFactory
     * @param SocialAccountInterfaceFactory $socialAccountInterfaceFactory
     * @param TotalsManagement $totalsManagement
     * @param OrderInterface $order
     * @param OrderInterfaceFactory $orderInterfaceFactory
     */
    public function __construct(
        MageCartRepositoryInterface $quoteRepository,
        CartInterface $cart,
        CartRepositoryInterface $cartRepository,
        CartItemRepositoryInterface $cartItemRepository,
        TotalsInterfaceFactory $totalsInterfaceFactory,
        TotalsItemInterfaceFactory $totalsItemInterfaceFactory,
        MageCartItemInterfaceFactory $mageCartItemInterfaceFactory,
        CartManagementInterfaceFactory $cartManagementInterfaceFactory,
        CartTotalRepositoryInterface $cartTotalRepository,
        TotalsInformationInterfaceFactory $totalsInformationInterfaceFactory,
        AddressInterfaceFactory $addressInterfaceFactory,
        ShippingInformationManagementInterfaceFactory $shippingInformationManagementInterfaceFactory,
        ShippingInformationFactory $shippingInformationFactory,
        PaymentInformationManagementInterfaceFactory $paymentInformationManagementInterfaceFactory,
        PaymentInterfaceFactory $paymentInterfaceFactory,
        SocialAccountInterfaceFactory $socialAccountInterfaceFactory,
        TotalsManagement $totalsManagement,
        OrderInterface $order,
        OrderInterfaceFactory $orderInterfaceFactory
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->cart = $cart;
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->totalsInterfaceFactory = $totalsInterfaceFactory;
        $this->totalsItemInterfaceFactory = $totalsItemInterfaceFactory;
        $this->mageCartItemInterfaceFactory = $mageCartItemInterfaceFactory;
        $this->cartManagementInterfaceFactory = $cartManagementInterfaceFactory;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->totalsInformationInterfaceFactory = $totalsInformationInterfaceFactory;
        $this->addressInterfaceFactory = $addressInterfaceFactory;
        $this->shippingInformationManagementInterfaceFactory = $shippingInformationManagementInterfaceFactory;
        $this->shippingInformationFactory = $shippingInformationFactory;
        $this->paymentInformationManagementInterfaceFactory = $paymentInformationManagementInterfaceFactory;
        $this->paymentInterfaceFactory = $paymentInterfaceFactory;
        $this->socialAccountInterfaceFactory = $socialAccountInterfaceFactory;
        $this->totalsManagement = $totalsManagement;
        $this->order = $order;
        $this->orderInterfaceFactory = $orderInterfaceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getMineCartTotals($customerId)
    {
        //create quote if not exist
        try {
            $this->quoteRepository->getActiveForCustomer($customerId);
        } catch (NoSuchEntityException $e) {
            $this->cartManagementInterfaceFactory->create()->createEmptyCartForCustomer($customerId);
        }

        $cart = $this->cart->load($customerId, 'customer_id');

        return $this->generateCartTotals($cart);
    }

    /**
     * {@inheritdoc}
     */
    public function getGuestCartTotals($guestToken)
    {

        $guest = $this->socialAccountInterfaceFactory->create()->load($guestToken, 'unique_hash');

        if (!$guest->getId()) {
            throw new NoSuchEntityException(__('Invalid guest token.'));
        }

        $cart = $this->cart->load($guest->getOpenId(), 'guest_id');

        return $this->generateCartTotals($cart);
    }

    /**
     * {@inheritdoc}
     */
    public function updateMineCartTotals($customerId, $cartItems)
    {

        $cart = $this->cart->load($customerId, 'customer_id');

        $this->updateCartTotals($cart, $cartItems);

    }

    /**
     * {@inheritdoc}
     */
    public function updateGuestCartTotals($guestToken, $cartItems)
    {

        $guest = $this->socialAccountInterfaceFactory->create()->load($guestToken, 'unique_hash');

        if (!$guest->getId()) {
            throw new NoSuchEntityException(__('Invalid guest token.'));
        }

        $cart = $this->cart->load($guest->getOpenId(), 'guest_id');

        $this->updateCartTotals($cart, $cartItems);

    }

    /**
     * {@inheritdoc}
     */
    public function getMineQuoteTotals($cartId)
    {
        return $this->generateQuoteTotals($cartId);
    }

    /**
     * {@inheritdoc}
     */
    public function getGuestQuoteTotals($guestToken)
    {
        $guest = $this->socialAccountInterfaceFactory->create()->load($guestToken, 'unique_hash');

        if (!$guest->getId()) {
            throw new NoSuchEntityException(__('Invalid guest token.'));
        }

        $cart = $this->cart->load($guest->getOpenId(), 'guest_id');

        return $this->generateQuoteTotals($cart->getQuoteId());
    }

    /**
     * {@inheritdoc}
     */
    public function oneStepCheckout($customerId, $sku)
    {
        try {
            $quote = $this->quoteRepository->getActiveForCustomer($customerId);
        } catch (NoSuchEntityException $e) {
            $cartId = $this->cartManagementInterfaceFactory->create()->createEmptyCartForCustomer($customerId);
            $quote = $this->quoteRepository->getActive($cartId);
        }

        $quote->removeAllItems();

        $quoteItem = $this->mageCartItemInterfaceFactory->create();
        $quoteItem->setSku($sku);
        $quoteItem->setQty(1);
        $quoteItems[] = $quoteItem;
        $quote->setItems($quoteItems);
        $this->quoteRepository->save($quote);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function minePlaceOrder($cartId, ShippingAddressInterface $address, $paymentMethod)
    {
        try {
            return $this->placeOrder($cartId, $address, $paymentMethod);
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()), $e, 4100);
        }   
    }

    /**
     * {@inheritdoc}
     */
    public function guestPlaceOrder($guestToken, ShippingAddressInterface $address, $paymentMethod)
    {
        $guest = $this->socialAccountInterfaceFactory->create()->load($guestToken, 'unique_hash');

        if (!$guest->getId()) {
            throw new NoSuchEntityException(__('Invalid guest token.'));
        }

        $cart = $this->cart->load($guest->getId(), 'guest_id');

        return $this->placeOrder($cart->getQuoteId(), $address, $paymentMethod);
    }

    /**
     * Place an order
     *
     * @param int $cartId
     * @param \AlbertMage\Checkout\Api\Data\ShippingAddressInterface $address
     * @param string paymentMethod
     * @return \AlbertMage\Sales\Api\Data\OrderInterface Order.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function placeOrder($cartId, ShippingAddressInterface $address, $paymentMethod)
    {

        // Save quote address
        $this->saveAddressInformation($cartId, $address);

        //Set payment method and place order
        $paymentInformationManagement = $this->paymentInformationManagementInterfaceFactory->create();

        $payment = $this->paymentInterfaceFactory->create();
        
        $payment->setMethod($paymentMethod);

        $orderId = (int) $paymentInformationManagement->savePaymentInformationAndPlaceOrder($cartId, $payment);

        $order = $this->order->load($orderId);

        return $this->orderInterfaceFactory->create(['data' => $order->toArray()]);

    }

    /**
     * Generate quote totals.
     *
     * @param int $cartId
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function generateQuoteTotals($cartId)
    {
        $quote = $this->quoteRepository->getActive($cartId);

        $address = $this->addressInterfaceFactory->create();
        $address->setCountryId(self::DEFAULT_COUNTRY_ID);
        $totalsInformation = $this->totalsInformationInterfaceFactory->create();
        $totalsInformation->setShippingMethodCode(self::DEFAULT_DILIVERY_METHOD);
        $totalsInformation->setShippingCarrierCode(self::DEFAULT_DILIVERY_METHOD);
        $totalsInformation->setAddress($address);

        $quote = $this->setAddressInformation($quote, $totalsInformation);

        return $this->calculateQuoteTotals($quote);
        //return $this->calculateQuoteTotalsByCartSort($quote);
    }

    /**
     * Generate Cart Totals
     *
     * @param \AlbertMage\Quote\Api\Data\CartInterface $cart
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     */
    private function generateCartTotals($cart)
    {
        $quote = $this->syncCartAndQuoteItems($cart);

        $address = $this->addressInterfaceFactory->create();
        $address->setCountryId('CN');
        $totalsInformation = $this->totalsInformationInterfaceFactory->create();
        $totalsInformation->setShippingMethodCode(self::DEFAULT_DILIVERY_METHOD);
        $totalsInformation->setShippingCarrierCode(self::DEFAULT_DILIVERY_METHOD);
        $totalsInformation->setAddress($address);

        $quote = $this->setAddressInformation($quote, $totalsInformation);

        return $this->calculateQuoteTotalsByCartSort($quote);
        //return $this->calculateCartTotals($cart, $quote);

    }

    /**
     * @param CartInterface $cart
     * @param \AlbertMage\Checkout\Api\Data\CartItemInterface[] $cartItems
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     */
    private function updateCartTotals(CartInterface $cart, $cartItems)
    {

        $selectedItems = [];

        foreach($cartItems as $cartItem) {
            $selectedItems[] = $cartItem->getItemId();
        }

        foreach($cart->getAllItems() as $cartItem) {
            if (in_array($cartItem->getId(), $selectedItems)) {
                $cartItem->setIsActive(1);
            } else {
                $cartItem->setIsActive(0);
            }
            $cartItem->save();
        }

        return $this->generateCartTotals($cart);
    }

    /**
     * Calculate totals in carts
     *
     * @param \AlbertMage\Quote\Api\Data\CartInterface $cart
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     */
    private function calculateCartTotals(
        \AlbertMage\Quote\Api\Data\CartInterface $cart,
        \Magento\Quote\Api\Data\CartInterface $quote
    ) {

        $cartTotal = $this->cartTotalRepository->get($quote->getId());

        $totals = $this->totalsManagement->mergeTotalsItemsToCart($cartTotal, $cart);
 
        return $totals;
    }

    /**
     * Calculate totals for one quote item
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     */
    private function calculateQuoteTotalsByCartSort(
        \Magento\Quote\Api\Data\CartInterface $quote,
    ) {

        $cart = $this->cart->load($quote->getId(), 'quote_id');

        $sortIds = [];
        foreach($cart->getSelectedItems() as $cartItem) {
            $sortIds[] = $cartItem->getProductId();
        }

        $totals = $this->calculateQuoteTotals($quote);
        $oldItems = $totals->getItems();
        $newItems = [];

        foreach ($totals->getItems() as $item) {
            $oldItems[$item->getProduct()->getId()] = $item;
        }

        foreach ($sortIds as $productId) {
            if (isset($oldItems[$productId])) {
                $newItems[] = $oldItems[$productId];
            }
        }

        $totals->setItems($newItems);
   
        return $totals;
    }

    /**
     * Calculate totals for one quote item
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     */
    private function calculateQuoteTotals(
        \Magento\Quote\Api\Data\CartInterface $quote
    ) {

        $cartTotal = $this->cartTotalRepository->get($quote->getId());

        $totals = $this->totalsManagement->getTotals($cartTotal);

        return $totals;
    }

    /**
     * Sync items between cart and quote
     *
     * @param \AlbertMage\Quote\Api\Data\CartInterface $cart
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    private function syncCartAndQuoteItems(\AlbertMage\Quote\Api\Data\CartInterface $cart)
    {

        $quote = $this->getQuoteFromCart($cart);

        $quoteItems = [];
        foreach($cart->getSelectedItems() as $cartItem) {
            $quoteItem = $this->mageCartItemInterfaceFactory->create();
            $quoteItem->setProductId($cartItem->getProductId());
            $quoteItem->setSku($cartItem->getSku());
            $quoteItem->setQty($cartItem->getQty());
            $quoteItems[] = $quoteItem;
        }

        $this->syncQuoteItems($quote, $quoteItems);

        return $this->quoteRepository->getActive($cart->getQuoteId());
    }

    /**
     * Get Quote Item from cart
     *
     * @param \AlbertMage\Quote\Api\Data\CartInterface $cart
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    private function getQuoteFromCart(\AlbertMage\Quote\Api\Data\CartInterface $cart)
    {
        $quoteId = $cart->getQuoteId();
        if ($quoteId == 0) {
            if ($cart->getCustomerId()) {
                $quoteId = $this->cartManagementInterfaceFactory->create()->createEmptyCartForCustomer($cart->getCustomerId());
            } else {
                $quoteId = $this->cartManagementInterfaceFactory->create()->createEmptyCart();
            }
            $cart->setQuoteId($quoteId);
            $cart->save();
        }

        return $this->quoteRepository->getActive($quoteId);
    }

    /**
     * Sync Quote Item
     * 
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param \Magento\Quote\Api\Data\CartItemInterface[] $quoteItems
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    private function syncQuoteItems(\Magento\Quote\Api\Data\CartInterface $quote, $quoteItems)
    {

        $newQuoteItems = [];

        $oldQuoteItems = [];

        foreach($quoteItems as $quoteItem) {
            $newQuoteItems[$quoteItem->getProductId()] = $quoteItem;
        }

        foreach($quote->getItems() as $quoteItem) {
            $oldQuoteItems[$quoteItem->getProductId()] = $quoteItem;
        }

        $updateItems = array_intersect_key($oldQuoteItems, $newQuoteItems);

        $createItems = array_diff_key($newQuoteItems, $oldQuoteItems);

        $deleteItems = array_diff_key($oldQuoteItems, $newQuoteItems);

        $quoteItems = [];

        foreach($deleteItems as $item) {
            $quote->removeItem($item->getId());
        }

        foreach($updateItems as $item) {
            $item->setQty($newQuoteItems[$item->getProductId()]->getQty());
        }

        foreach($createItems as $item) {
            $quoteItems[] = $item;
        }

        $quote->setItems($quoteItems);

        $this->quoteRepository->save($quote);

        return $quote;

    }

    
    /**
     * Save the address information before place an order.
     *
     * @param int $cartId
     * @param \AlbertMage\Checkout\Api\Data\ShippingAddressInterface $address
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    private function saveAddressInformation($cartId, ShippingAddressInterface $address)
    {
        $shippingAddress = $this->addressInterfaceFactory->create();
        $billingAddress = $this->addressInterfaceFactory->create();

        //['data' => $totalsData]
        $shippingAddress->setRegion($address->getRegion());
        $shippingAddress->setRegionId($address->getRegionId());
        $shippingAddress->setDistrict($address->getDistrict());
        $shippingAddress->setDistrictId($address->getDistrictId());
        $shippingAddress->setCity($address->getCity());
        $shippingAddress->setCityId($address->getCityId());
        $shippingAddress->setStreet($address->getStreet());
        $shippingAddress->setCountryId($address->getCountryId());
        $shippingAddress->setPostcode($address->getPostcode());
        $shippingAddress->setFirstname($address->getFirstname());
        $shippingAddress->setLastname($address->getLastname());
        $shippingAddress->setEmail($address->getEmail());
        $shippingAddress->setTelephone($address->getTelephone());

        $billingAddress->setRegion($address->getRegion());
        $billingAddress->setRegionId($address->getRegionId());
        $billingAddress->setDistrict($address->getDistrict());
        $billingAddress->setDistrictId($address->getDistrictId());
        $billingAddress->setCity($address->getCity());
        $billingAddress->setCityId($address->getCityId());
        $billingAddress->setStreet($address->getStreet());
        $billingAddress->setCountryId($address->getCountryId());
        $billingAddress->setPostcode($address->getPostcode());
        $billingAddress->setFirstname($address->getFirstname());
        $billingAddress->setLastname($address->getLastname());
        $billingAddress->setEmail($address->getEmail());
        $billingAddress->setTelephone($address->getTelephone());

        $shippingInformation = $this->shippingInformationFactory->create();
        $shippingInformation->setShippingAddress($shippingAddress);
        $shippingInformation->setBillingAddress($billingAddress);
        $shippingInformation->setShippingCarrierCode(self::DEFAULT_DILIVERY_METHOD);
        $shippingInformation->setShippingMethodCode(self::DEFAULT_DILIVERY_METHOD);

        $this->shippingInformationManagementInterfaceFactory->create()->saveAddressInformation($cartId, $shippingInformation);
    }

    /**
     * Calculate quote totals based on address and shipping method.
     *
     * @param CartInterface $quote
     * @param \Magento\Checkout\Api\Data\TotalsInformationInterface $addressInformation
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    private function setAddressInformation(
        $quote,
        TotalsInformationInterface $addressInformation
    ) {

        if ($quote->getIsVirtual()) {
            $quote->setBillingAddress($addressInformation->getAddress());
        } else {
            $quote->setShippingAddress($addressInformation->getAddress());
            if ($addressInformation->getShippingCarrierCode() && $addressInformation->getShippingMethodCode()) {
                $shippingMethod = implode(
                    '_',
                    [$addressInformation->getShippingCarrierCode(), $addressInformation->getShippingMethodCode()]
                );
                $quoteShippingAddress = $quote->getShippingAddress();
                if ($quoteShippingAddress->getShippingMethod() &&
                    $quoteShippingAddress->getShippingMethod() !== $shippingMethod
                ) {
                    $quoteShippingAddress->setShippingAmount(0);
                    $quoteShippingAddress->setBaseShippingAmount(0);
                }
                $quoteShippingAddress->setCollectShippingRates(true)
                    ->setShippingMethod($shippingMethod);
            }
        }
        $quote->collectTotals();

        return $quote;

    }
}