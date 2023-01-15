<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Model;

use AlbertMage\Checkout\Api\Data\ShippingAddressInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class CartItem
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class ShippingAddress extends AbstractModel implements ShippingAddressInterface
{

    /**
     * {@inheritdoc}
     */
    public function getCountryId()
    {
        return $this->getData(self::KEY_COUNTRY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryId($countryId)
    {
        return $this->setData(self::KEY_COUNTRY_ID, $countryId);
    }

    /**
     * {@inheritdoc}
     */
    public function getRegion()
    {
        return $this->getData(self::KEY_REGION);
    }

    /**
     * {@inheritdoc}
     */
    public function setRegion($region)
    {
             return $this->setData(self::KEY_REGION, $region);   
    }

    /**
     * {@inheritdoc}
     */
    public function getRegionId()
    {
        return $this->getData(self::KEY_REGION_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRegionId($regionId)
    {
        return $this->setData(self::KEY_REGION_ID, $regionId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCity()
    {
        return $this->getData(self::KEY_CITY);
    }

    /**
     * {@inheritdoc}
     */
    public function setCity($city)
    {
        return $this->setData(self::KEY_CITY, $city);
    }

    /**
     * {@inheritdoc}
     */
    public function getCityId()
    {
        return $this->getData(self::KEY_CITY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCityId($cityId)
    {
        return $this->setData(self::KEY_CITY_ID, $cityId);
    }

    /**
     * {@inheritdoc}
     */
    public function getDistrict()
    {
        return $this->getData(self::KEY_DISTRICT);
    }

    /**
     * {@inheritdoc}
     */
    public function setDistrict($district)
    {
        return $this->setData(self::KEY_DISTRICT, $district);
    }

    /**
     * {@inheritdoc}
     */
    public function getDistrictId()
    {
        return $this->getData(self::KEY_DISTRICT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setDistrictId($districtId)
    {
        return $this->setData(self::KEY_DISTRICT_ID, $districtId);
    }

    /**
     * {@inheritdoc}
     */
    public function getPostcode()
    {
        return $this->getData(self::KEY_POSTCODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPostcode($postcode)
    {
        return $this->setData(self::KEY_POSTCODE, $postcode);
    }

    /**
     * {@inheritdoc}
     */
    public function getStreet()
    {
        return $this->getData(self::KEY_STREET);
    }

    /**
     * {@inheritdoc}
     */
    public function setStreet($street)
    {
        return $this->setData(self::KEY_STREET, $street);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstname()
    {
        return $this->getData(self::KEY_FIRSTNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstname($firstname)
    {
        return $this->setData(self::KEY_FIRSTNAME, $firstname);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastname()
    {
        return $this->getData(self::KEY_LASTNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setLastname($lastname)
    {
        return $this->setData(self::KEY_LASTNAME, $lastname);
    }

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

    /**
     * {@inheritdoc}
     */
    public function getTelephone()
    {
        return $this->getData(self::KEY_TELEPHONE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTelephone($telephone)
    {
        return $this->setData(self::KEY_TELEPHONE, $telephone);
    }

}