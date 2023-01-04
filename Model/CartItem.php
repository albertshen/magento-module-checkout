<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Model;

use AlbertMage\Checkout\Api\Data\CartItemInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class CartItem
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class CartItem extends AbstractModel implements CartItemInterface
{
    /**
     * {@inheritdoc}
     */
    public function getItemId()
    {
        return $this->getData(self::ITEM_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setItemId($itemId)
    {
        return $this->setData(self::ITEM_ID, $itemId);
    }
}