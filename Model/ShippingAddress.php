<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Model;

use AlbertMage\Checkout\Api\Data\ShippingAddressInterface;

/**
 * Class ShippingAddress
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class ShippingAddress extends \AlbertMage\Customer\Model\Address implements ShippingAddressInterface
{

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->getData(self::KEY_EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        return $this->setData(self::KEY_EMAIL, $email);   
    }

}