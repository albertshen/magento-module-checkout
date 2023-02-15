<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Model;

use AlbertMage\Checkout\Api\CheckoutManagementInterface;
use AlbertMage\Catalog\Api\ProductManagementInterface;
use AlbertMage\Quote\Api\Data\CartInterface;
use AlbertMage\Quote\Api\Data\CartItemInterface;
use AlbertMage\Quote\Api\Data\CartItemInterfaceFactory;
use AlbertMage\Quote\Api\CartRepositoryInterface;
use AlbertMage\Quote\Api\CartItemRepositoryInterface;
use AlbertMage\Quote\Api\Data\TotalsInterfaceFactory;
use AlbertMage\Quote\Api\Data\TotalsItemInterfaceFactory;
use AlbertMage\Checkout\Api\Data\ShippingAddressInterface;
use AlbertMage\Customer\Api\Data\SocialAccountInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
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
     * @var ProductManagementInterface
     */
    protected $productManagement;

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
     * @var CartItemInterfaceFactory
     */
    protected $cartItemInterfaceFactory;

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
     * @param MageCartRepositoryInterface $quoteRepository
     * @param CartInterface $cart
     * @param ProductManagementInterface $productManagement
     * @param CartRepositoryInterface $cartRepository
     * @param CartItemRepositoryInterface $cartItemRepository
     * @param CartItemInterfaceFactory $cartItemInterfaceFactory
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
     */
    public function __construct(
        MageCartRepositoryInterface $quoteRepository,
        CartInterface $cart,
        ProductManagementInterface $productManagement,
        CartRepositoryInterface $cartRepository,
        CartItemRepositoryInterface $cartItemRepository,
        CartItemInterfaceFactory $cartItemInterfaceFactory,
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
        SocialAccountInterfaceFactory $socialAccountInterfaceFactory
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->cart = $cart;
        $this->productManagement = $productManagement;
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->cartItemInterfaceFactory = $cartItemInterfaceFactory;
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
        return $this->placeOrder($cartId, $address, $paymentMethod);
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

        $cart = $this->cart->load($guest->getOpenId(), 'guest_id');

        return $this->placeOrder($cart->getQuoteId(), $address, $paymentMethod);
    }

    /**
     * Create TotalsItem
     * Using plugin to add extension attributes
     *
     * @param int $productId
     * @param int $qty
     * @return \AlbertMage\Quote\Api\Data\TotalsItemInterface
     */
    public function createTotalsItemByProductId($productId, $qty = 1)
    {
        $totalsItem = $this->totalsItemInterfaceFactory->create();
        $product = $this->productManagement->createProductListItemById($productId);
        $totalsItem->setProduct($product);
        $totalsItem->setPrice($product->getPrice());
        $totalsItem->setQty($qty);
        $totalsItem->setRowTotal($product->getPrice() * $qty);
        return $totalsItem;
    }

    /**
     * One step checkout
     *
     * @param int $cartId
     * @param \AlbertMage\Checkout\Api\Data\ShippingAddressInterface $address
     * @param string paymentMethod
     * @return int Order ID.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function placeOrder($cartId, ShippingAddressInterface $address, $paymentMethod)
    {

        // Save quote address
        $shippingAddress = $this->addressInterfaceFactory->create();
        $billingAddress = $this->addressInterfaceFactory->create();

        $shippingAddress->setRegion($address->getRegion());
        $shippingAddress->setRegionId($address->getRegionId());
        $shippingAddress->setDistrict($address->getDistrict());
        $shippingAddress->setDistrictId($address->getDistrictId());
        $shippingAddress->setCity($address->getCity());
        $shippingAddress->setCityId($address->getCityId());
        $shippingAddress->setStreet([$address->getStreet()]);
        $shippingAddress->setCountryId(self::DEFAULT_COUNTRY_ID);
        $shippingAddress->setPostcode(self::DEFAULT_POST_CODE);
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
        $billingAddress->setStreet([$address->getStreet()]);
        $billingAddress->setCountryId(self::DEFAULT_COUNTRY_ID);
        $billingAddress->setPostcode(self::DEFAULT_POST_CODE);
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


        //Set payment method and place order
        $paymentInformationManagement = $this->paymentInformationManagementInterfaceFactory->create();

        $payment = $this->paymentInterfaceFactory->create();
        
        $payment->setMethod($paymentMethod);

        return (int) $paymentInformationManagement->savePaymentInformationAndPlaceOrder($cartId, $payment);

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
        $address->setCountryId('ZH');
        $totalsInformation = $this->totalsInformationInterfaceFactory->create();
        $totalsInformation->setShippingMethodCode('freeshipping');
        $totalsInformation->setShippingCarrierCode('freeshipping');
        $totalsInformation->setAddress($address);

        $quote = $this->setAddressInformation($quote, $totalsInformation);

        return $this->calculateCartTotals($cart, $quote);

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
            $this->cartItemRepository->save($cartItem);
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

        $totals = $this->totalsInterfaceFactory->create();

        $this->prepareTotals($totals, $cartTotal);
  
        $cartItems = $cart->getAllItems();
        $totalsItems = [];
        foreach($cartItems as $cartItem) {
            $totalsItem = $this->createTotalsItemByProductId($cartItem->getProductId(), $cartItem->getQty());
            if ($cartTotalItem = $this->getCartTotalItem($cartTotal->getItems(), $cartItem)) {
                $this->prepareTotalsItem($totalsItem, $cartTotalItem);
                $totalsItem->setIsActive(1);
            } else {
                $totalsItem->setIsActive(0);
            }
            $totalsItem->setItemId($cartItem->getId());
            $totalsItems[] = $totalsItem;
        }
        $totals->setItems($totalsItems);
 
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

        $quoteItems = $quote->getItems();
       
        $totals = $this->totalsInterfaceFactory->create();

        $this->prepareTotals($totals, $cartTotal);

        $totalsItems = [];
        if (count($quoteItems) == 1) {
            foreach($cartTotal->getItems() as $cartTotalItem) {
                $totalsItem = $this->createTotalsItemByProductId(
                    $cartTotalItem->getExtensionAttributes()->getProductId(), $cartTotalItem->getQty()
                );
                $this->prepareTotalsItem($totalsItem, $cartTotalItem);
                $totalsItems[] = $totalsItem;
            }
        } else {
            $cart = $this->cart->load($quote->getId(), 'quote_id');
            $cartItems = $cart->getAllItems();
            foreach($cartItems as $cartItem) {
                if ($cartTotalItem = $this->getCartTotalItem($cartTotal->getItems(), $cartItem)) {
                    $totalsItem = $this->createTotalsItemByProductId($cartItem->getProductId(), $cartTotalItem->getQty());
                    $this->prepareTotalsItem($totalsItem, $cartTotalItem);
                    $totalsItems[] = $totalsItem;
                }
            }
        }

        $totals->setItems($totalsItems);
   
        return $totals;
    }

    /**
     * Prepare totals
     *
     * @param \AlbertMage\Quote\Api\Data\TotalsInterface $totals
     * @param \Magento\Quote\Api\Data\TotalsInterface $cartTotal
     * @return $this
     */
    private function prepareTotals(\AlbertMage\Quote\Api\Data\TotalsInterface $totals, \Magento\Quote\Api\Data\TotalsInterface $cartTotal)
    {
        $totals->setGrandTotal($cartTotal->getGrandTotal());
        $totals->setSubtotal($cartTotal->getSubtotal());
        $totals->setDiscountAmount($cartTotal->getDiscountAmount());
        $totals->setSubtotalWithDiscount($cartTotal->getSubtotalWithDiscount());
        $totals->setShippingAmount($cartTotal->getShippingAmount());
        $totals->setItemsQty($cartTotal->getItemsQty());
        return $this;
    }

    /**
     * Prepare totals item
     *
     * @param \AlbertMage\Quote\Api\Data\TotalsItemInterface $totalsItem
     * @param \Magento\Quote\Api\Data\TotalsItemInterface $cartTotalItem
     * @return $this
     */
    private function prepareTotalsItem(\AlbertMage\Quote\Api\Data\TotalsItemInterface $totalsItem, \Magento\Quote\Api\Data\TotalsItemInterface $cartTotalItem)
    {
        $totalsItem->setDiscountAmount($cartTotalItem->getDiscountAmount());
        return $this;
    }

    /**
     * Sync items between cart and quote
     *
     * @param \AlbertMage\Quote\Api\Data\CartInterface $cart
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    private function syncCartAndQuoteItems(\AlbertMage\Quote\Api\Data\CartInterface $cart)
    {
        //create quote if not exist
        $quoteId = $cart->getQuoteId();
        if ($quoteId == 0) {
            if ($cart->getCustomerId()) {
                $quoteId =  $this->cartManagementInterfaceFactory->create()->createEmptyCartForCustomer($cart->getCustomerId());
            } else {
                $quoteId = $this->cartManagementInterfaceFactory->create()->createEmptyCart();
            }
            $cart->setQuoteId($quoteId);
            $this->cartRepository->save($cart);
        }

        $quote = $this->quoteRepository->getActive($quoteId);
        
        $quoteItems = $quote->getItems();

        foreach($quoteItems as $quoteItem) {
            $cartItem = $this->getCartItem($cart->getAvailableItems(), $quoteItem);
            if (!$cartItem) {
                $quote->removeItem($quoteItem->getId());
            }
        }

        foreach($cart->getAvailableItems() as $cartItem) {
            if ($quoteItem = $this->getQuoteItem($quoteItems, $cartItem)) {
                $quoteItem->setQty($cartItem->getQty());
            } else {
                $quoteItem = $this->mageCartItemInterfaceFactory->create();
                $quoteItem->setSku($cartItem->getSku());
                $quoteItem->setQty($cartItem->getQty());
                $quoteItems[] = $quoteItem;
            }
        }

        $quote->setItems($quoteItems);
        $this->quoteRepository->save($quote);

        return $this->quoteRepository->getActive($cart->getQuoteId());
    }

    /**
     * Get Quote Item
     *
     * @param array $quoteItems
     * @param \AlbertMage\Quote\Api\Data\CartItemInterface $cartItem
     * @return \Magento\Quote\Api\Data\CartItemInterface|null
     */
    private function getQuoteItem($quoteItems, \AlbertMage\Quote\Api\Data\CartItemInterface $cartItem)
    {
        foreach($quoteItems as $quoteItem) {
            if ($quoteItem->getProductId() == $cartItem->getProductId()) {
                return $quoteItem;
            }
        }
        return null;
    }

    /**
     * Get Cart Item
     *
     * @param array $cartItems
     * @param \Magento\Quote\Api\Data\CartItemInterface $quoteItem
     * @return \AlbertMage\Quote\Api\Data\CartItemInterface|null
     */
    private function getCartItem($cartItems, \Magento\Quote\Api\Data\CartItemInterface $quoteItem)
    {
        foreach($cartItems as $cartItem) {
            if ($cartItem->getProductId() == $quoteItem->getProductId()) {
                return $cartItem;
            }
        }
        return null;
    }

    /**
     * Get Cart total Item
     *
     * @param array $cartTotalItems
     * @param \AlbertMage\Quote\Api\Data\CartItemInterface $cartItem
     * @return \Magento\Quote\Api\Data\TotalsItemInterface|null
     */
    private function getCartTotalItem($cartTotalItems, \AlbertMage\Quote\Api\Data\CartItemInterface $cartItem)
    {
        foreach($cartTotalItems as $cartTotalItem) {
            if ($cartTotalItem->getExtensionAttributes()->getProductId() == $cartItem->getProductId()) {
                return $cartTotalItem;
            }
        }
        return null;
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