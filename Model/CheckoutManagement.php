<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Model;

use AlbertMage\Checkout\Api\CheckoutManagementInterface;
use AlbertMage\Quote\Api\Data\CartInterface;
use AlbertMage\Quote\Api\Data\CartItemInterface;
use AlbertMage\Quote\Api\Data\CartItemInterfaceFactory;
use AlbertMage\Quote\Api\CartRepositoryInterface;
use AlbertMage\Quote\Api\CartItemRepositoryInterface;
use AlbertMage\Quote\Api\Data\TotalsInterfaceFactory;
use AlbertMage\Quote\Api\Data\TotalsItemInterfaceFactory;
use AlbertMage\Checkout\Api\Data\ShippingAddressInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface as MageCartRepositoryInterface;
use Magento\Quote\Api\Data\CartItemInterfaceFactory as MageCartItemInterfaceFactory;
use Magento\Quote\Api\CartManagementInterfaceFactory;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
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
     * @var ProductFactory
     */
    protected $productFactory;

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
     * @param MageCartRepositoryInterface $quoteRepository
     * @param CartInterface $cart
     * @param CartRepositoryInterface $cartRepository
     * @param CartItemRepositoryInterface $cartItemRepository
     * @param CartItemInterfaceFactory $cartItemInterfaceFactory
     * @param TotalsInterfaceFactory $totalsInterfaceFactory
     * @param TotalsItemInterfaceFactory $totalsItemInterfaceFactory
     * @param MageCartItemInterfaceFactory $mageCartItemInterfaceFactory
     * @param CartManagementInterfaceFactory $cartManagementInterfaceFactory
     * @param CartTotalRepositoryInterface $cartTotalRepository
     * @param ProductFactory $productFactory
     * @param TotalsInformationInterfaceFactory $totalsInformationInterfaceFactory
     * @param AddressInterfaceFactory $addressInterfaceFactory
     * @param ShippingInformationManagementInterfaceFactory $shippingInformationManagementInterfaceFactory
     * @param ShippingInformationFactory $shippingInformationFactory
     * @param PaymentInformationManagementInterfaceFactory $paymentInformationManagementInterfaceFactory
     * @param PaymentInterfaceFactory $paymentInterfaceFactory
     */
    public function __construct(
        MageCartRepositoryInterface $quoteRepository,
        CartInterface $cart,
        CartRepositoryInterface $cartRepository,
        CartItemRepositoryInterface $cartItemRepository,
        CartItemInterfaceFactory $cartItemInterfaceFactory,
        TotalsInterfaceFactory $totalsInterfaceFactory,
        TotalsItemInterfaceFactory $totalsItemInterfaceFactory,
        MageCartItemInterfaceFactory $mageCartItemInterfaceFactory,
        CartManagementInterfaceFactory $cartManagementInterfaceFactory,
        CartTotalRepositoryInterface $cartTotalRepository,
        ProductFactory $productFactory,
        TotalsInformationInterfaceFactory $totalsInformationInterfaceFactory,
        AddressInterfaceFactory $addressInterfaceFactory,
        ShippingInformationManagementInterfaceFactory $shippingInformationManagementInterfaceFactory,
        ShippingInformationFactory $shippingInformationFactory,
        PaymentInformationManagementInterfaceFactory $paymentInformationManagementInterfaceFactory,
        PaymentInterfaceFactory $paymentInterfaceFactory
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->cart = $cart;
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->cartItemInterfaceFactory = $cartItemInterfaceFactory;
        $this->totalsInterfaceFactory = $totalsInterfaceFactory;
        $this->totalsItemInterfaceFactory = $totalsItemInterfaceFactory;
        $this->mageCartItemInterfaceFactory = $mageCartItemInterfaceFactory;
        $this->cartManagementInterfaceFactory = $cartManagementInterfaceFactory;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->productFactory = $productFactory;
        $this->totalsInformationInterfaceFactory = $totalsInformationInterfaceFactory;
        $this->addressInterfaceFactory = $addressInterfaceFactory;
        $this->shippingInformationManagementInterfaceFactory = $shippingInformationManagementInterfaceFactory;
        $this->shippingInformationFactory = $shippingInformationFactory;
        $this->paymentInformationManagementInterfaceFactory = $paymentInformationManagementInterfaceFactory;
        $this->paymentInterfaceFactory = $paymentInterfaceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getCartTotals($customerId, $cartItems)
    {

        try {
            $quote = $this->quoteRepository->getActiveForCustomer($customerId);
        } catch (NoSuchEntityException $e) {
            $this->cartManagementInterfaceFactory->create()->createEmptyCartForCustomer($customerId);
        }

        $selectedItems = [];

        foreach($cartItems as $cartItem) {
            $selectedItems[] = $cartItem->getItemId();
        }

        $cart = $this->cart->load($customerId, 'customer_id');

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
     * {@inheritdoc}
     */
    public function getQuoteTotals($cartId)
    {
        $quote = $this->quoteRepository->getActive($cartId);

        $address = $this->addressInterfaceFactory->create();
        $address->setCountryId('ZH');
        $totalsInformation = $this->totalsInformationInterfaceFactory->create();
        $totalsInformation->setShippingMethodCode('flatrate');
        $totalsInformation->setShippingCarrierCode('flatrate');
        $totalsInformation->setAddress($address);

        $quote = $this->setAddressInformation($quote, $totalsInformation);

        return $this->calculateQuoteTotals($quote);

    }

    /**
     * {@inheritdoc}
     */
    public function placeOrder($cartId, ShippingAddressInterface $address, $paymentMethod)
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
        $shippingAddress->setCountryId('CN');
        $shippingAddress->setPostcode('2032');
        $shippingAddress->setFirstname($address->getFirstname());
        $shippingAddress->setLastname($address->getLastname());
        $shippingAddress->setEmail('saf@safs2.com');
        $shippingAddress->setTelephone($address->getTelephone());

        $billingAddress->setRegion($address->getRegion());
        $billingAddress->setRegionId($address->getRegionId());
        $billingAddress->setDistrict($address->getDistrict());
        $billingAddress->setDistrictId($address->getDistrictId());
        $billingAddress->setCity($address->getCity());
        $billingAddress->setCityId($address->getCityId());
        $billingAddress->setStreet([$address->getStreet()]);
        $billingAddress->setCountryId('CN');
        $billingAddress->setPostcode('2032');
        $billingAddress->setFirstname($address->getFirstname());
        $billingAddress->setLastname($address->getLastname());
        $billingAddress->setEmail('saf@safs2.com');
        $billingAddress->setTelephone($address->getTelephone());

        $shippingInformation = $this->shippingInformationFactory->create();
        $shippingInformation->setShippingAddress($shippingAddress);
        $shippingInformation->setBillingAddress($billingAddress);
        $shippingInformation->setShippingCarrierCode('flatrate');
        $shippingInformation->setShippingMethodCode('flatrate');

        $this->shippingInformationManagementInterfaceFactory->create()->saveAddressInformation($cartId, $shippingInformation);


        //Set payment method and place order
        $paymentInformationManagement = $this->paymentInformationManagementInterfaceFactory->create();

        $payment = $this->paymentInterfaceFactory->create();
        
        $payment->setMethod($paymentMethod);

        return (int) $paymentInformationManagement->savePaymentInformationAndPlaceOrder($cartId, $payment);

    }

    /**
     * Create TotalsItem
     * Using plugin to add extension attributes
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param int $qty
     * @return \AlbertMage\Quote\Api\Data\TotalsItemInterface
     */
    public function createTotalsItemByProduct(\Magento\Catalog\Model\Product $product, $qty = 1)
    {
        $totalsItem = $this->totalsItemInterfaceFactory->create();
        $totalsItem->setName($product->getName());
        $totalsItem->setThumbnail($product->getMediaConfig()->getBaseMediaUrl().$product->getThumbnail());
        $totalsItem->setPrice($product->getPrice());
        $totalsItem->setQty($qty);
        $totalsItem->setRowTotal($product->getPrice() * $qty);
        return $totalsItem;
    }

    /**
     * Calulate Cart Totals
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
            if ($cartTotalItem = $this->getCartTotalItem($cartTotal->getItems(), $cartItem)) {
                $totalsItem = $this->createTotalsItemByProduct($cartItem->getProduct(), $cartItem->getQty());
                $this->prepareTotalsItem($totalsItem, $cartTotalItem);
                $totalsItem->setItemId($cartItem->getId());
                $totalsItems[] = $totalsItem;
            } else {
                $totalsItem = $this->createTotalsItemByProduct($cartItem->getProduct(), $cartItem->getQty());
                $totalsItem->setIsActive(0);
                $totalsItem->setItemId($cartItem->getId());
                $totalsItems[] = $totalsItem;
            }
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
                $product = $this->productFactory->create()->load($cartTotalItem->getExtensionAttributes()->getProductId());
                $totalsItem = $this->createTotalsItemByProduct($product);
                $this->prepareTotalsItem($totalsItem, $cartTotalItem);
                $totalsItems[] = $totalsItem;
            }
        } else {
            $cart = $this->cart->load($quote->getCustomerId(), 'customer_id');
            $cartItems = $cart->getAllItems();
            foreach($cartItems as $cartItem) {
                if ($cartTotalItem = $this->getCartTotalItem($cartTotal->getItems(), $cartItem)) {
                    $totalsItem = $this->createTotalsItemByProduct($cartItem->getProduct(), $cartItem->getQty());
                    $this->prepareTotalsItem($totalsItem, $cartTotalItem);
                    $totalsItem->setItemId($cartItem->getId());
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
        $totalsItem->setIsActive(1);
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
        $quote = $this->quoteRepository->getActiveForCustomer($cart->getCustomerId());

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

        return $this->quoteRepository->getActiveForCustomer($cart->getCustomerId());
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