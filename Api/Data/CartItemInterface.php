<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Api\Data;

/**
 * Cart Interface
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface CartItemInterface
{

    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ITEM_ID = 'item_id';
    /**#@-*/

    /**
     * Get Item ID
     *
     * @return int|null
     */
    public function getItemId();

    /**
     * Set Item ID
     *
     * @param int $itemId
     * @return $this
     */
    public function setItemId($itemId);


}
