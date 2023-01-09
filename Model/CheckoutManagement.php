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
use Magento\Quote\Api\CartRepositoryInterface as MageCartRepositoryInterface;
use Magento\Quote\Api\Data\CartItemInterfaceFactory as MageCartItemInterfaceFactory;
use Magento\Quote\Api\CartManagementInterfaceFactory;
use Magento\Quote\Api\CartTotalRepositoryInterface;

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
        CartTotalRepositoryInterface $cartTotalRepository
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
    }

    /**
     * {@inheritdoc}
     */
    public function getCartTotals($customerId, $cartId, $cartItems)
    {
        if (!$cartId) {
           $cartId = $this->cartManagementInterfaceFactory->create()->createEmptyCartForCustomer($customerId);
        }
        return $this->getQuoteTotals($customerId);
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

        $this->calulateCartTotals($cart);
    }

    public function getQuoteTotals($customerId)
    {

        $quote = $this->quoteRepository->getActiveForCustomer($customerId);

        $quoteItems = $quote->getItems();

        $cartTotal = $this->cartTotalRepository->get($quote->getId());

        $totals = $this->totalsInterfaceFactory->create();

        $this->prepareTotals($totals, $cartTotal);

        $cart = $this->cart->load($customerId, 'customer_id');

        if ($cart->getId()) {
            $cartItems = $cart->getAllItems();
            $totalsItems = [];
            foreach($cartItems as $cartItem) {
                if ($cartTotalItem = $this->getCartTotalItem($cartTotal->getItems(), $cartItem)) {
                    $totalsItem = $this->createTotalsItemByProduct($cartItem->getProduct(), $cartItem->getQty());
                    $this->prepareTotalsItemInQuote($totalsItem, $cartTotalItem);
                } else {
                    if (true) {
                        $totalsItem = $this->createTotalsItemByProduct($cartItem->getProduct(), $cartItem->getQty());
                        $totalsItem->setIsActive(0);
                    }
                }
                $totalsItem->setItemId($cartItem->getId());
                $totalsItems[] = $totalsItem;
            }
            $totals->setItems($totalsItems);
        }
        return $totals;
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
     * Prepare totals item in quote
     *
     * @param \AlbertMage\Quote\Api\Data\TotalsItemInterface $totalsItem
     * @param \Magento\Quote\Api\Data\TotalsItemInterface $cartTotalItem
     * @return $this
     */
    private function prepareTotalsItemInQuote(\AlbertMage\Quote\Api\Data\TotalsItemInterface $totalsItem, \Magento\Quote\Api\Data\TotalsItemInterface $cartTotalItem)
    {
        $totalsItem->setIsActive(1);
        $totalsItem->setDiscountAmount($cartTotalItem->getDiscountAmount());
        return $this;
    }


    /**
     * Calulate Cart Totals
     *
     * @param \AlbertMage\Quote\Api\Data\CartInterface $cart
     * @return \AlbertMage\Quote\Api\Data\TotalsInterface
     */
    public function calulateCartTotals($cart)
    {
        $quote = $this->quoteRepository->getActiveForCustomer($cart->getCustomerId());

        $quoteItems = $quote->getItems();

        //$cartItems = $cart->getAllItems();
//var_dump(count($cart->getAvailableItems()));exit;
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

        
        // $this->doCalculate($quote);

        // $quote = $this->quoteRepository->getActiveForCustomer($cart->getCustomerId());

        // $quoteItems = $quote->getItems();

        // foreach($cartItems as $cartItem) {
        //     if ($quoteItem = $this->getQuoteItem($quoteItems, $cartItem)) {
        //         $cartItem->setQuoteItem($quoteItem);
        //     }
        // }

        // foreach($cartItems as $cartItem) {
        //     if ($cartItem->getQuoteItem()) {
        //         var_dump($cartItem->getQuoteItem()->getId());
        //     }
        // }

        // foreach($quoteItems as $cartItem) {
        //     if ($cartItem->getIsActive()) {
        //         if ($quoteItem = $this->getQuoteItem($quoteItems, $cartItem)) {
        //             $quoteItem->setQty($cartItem->getQty());
        //         } else {
        //             $quoteItems[] = $this->createQuoteItem($cartItem);
        //         }
        //     }
        // }

        // foreach($cart->getAllItems() as $cartItem) {
        //     if ($cartItem->getIsActive() && $this->getQuoteItem($quoteItems, $cartItem)) {
        //         $cartItem->
        //     }
        //     var_dump($cartItem->getSku());
        // }
        exit;
    }

    /**
     * Get Quote Item
     *
     * @param array $quoteItems
     * @param CartItemInterface $cartItem
     * @return \Magento\Quote\Api\Data\CartItemInterface|null
     */
    private function getQuoteItem($quoteItems, $cartItem)
    {
        foreach($quoteItems as $quoteItem) {
            if ($quoteItem->getProductId() == $cartItem->getProductId()) {
                return $quoteItem;
            }
        }
        return null;
    }

    /**
     * Get Quote Item
     *
     * @param array $cartTotalItems
     * @param CartItemInterface $cartItem
     * @return \Magento\Quote\Api\Data\TotalsItemInterface|null
     */
    private function getCartTotalItem($cartTotalItems, $cartItem)
    {
        foreach($cartTotalItems as $cartTotalItem) {
            if ($cartTotalItem->getExtensionAttributes()->getProductId() == $cartItem->getProductId()) {
                return $cartTotalItem;
            }
        }
        return null;
    }

    /**
     * Get Cart Item
     *
     * @param array $cartItems
     * @param \Magento\Quote\Api\Data\CartItemInterface $quoteItem
     * @return CartItemInterface|null
     */
    private function getCartItem($cartItems, $quoteItem)
    {
        foreach($cartItems as $cartItem) {
            if ($cartItem->getProductId() == $quoteItem->getProductId()) {
                return $cartItem;
            }
        }
        return null;
    }

    /**
     * Calculate quote totals based on address and shipping method.
     *
     * @param CartInterface $quote
     * @param \Magento\Checkout\Api\Data\TotalsInformationInterface $addressInformation
     * @return \Magento\Quote\Api\Data\TotalsInterface
     */
    private function doCalculate(
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

        return $this->cartTotalRepository->get($quote->getId());
    }
}