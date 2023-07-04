<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Api\Data;

/**
 * ShippingAddress Interface
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface ShippingAddressInterface extends \AlbertMage\Customer\Api\Data\AddressInterface
{
    /**#@+
     * Constants defined for keys of array, makes typos less likely
     */

    const KEY_EMAIL = 'email';

    /**#@-*/

    /**
     * Get billing/shipping email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set billing/shipping email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

}
