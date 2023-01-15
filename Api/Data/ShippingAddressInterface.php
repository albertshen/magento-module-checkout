<?php
/**
 * Copyright © PHP Digital, Inc. All rights reserved.
 */
namespace AlbertMage\Checkout\Api\Data;

/**
 * ShippingAddress Interface
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface ShippingAddressInterface
{
    /**#@+
     * Constants defined for keys of array, makes typos less likely
     */

    const KEY_COUNTRY_ID = 'country_id';

    const KEY_REGION = 'region';

    const KEY_REGION_ID = 'region_id';

    const KEY_CITY = 'city';

    const KEY_CITY_ID = 'city_id';

    const KEY_DISTRICT = 'district';

    const KEY_DISTRICT_ID = 'district_id';

    const KEY_POSTCODE = 'postcode';

    const KEY_STREET = 'street';

    const KEY_FIRSTNAME = 'firstname';

    const KEY_LASTNAME = 'lastname';

    const KEY_EMAIL = 'email';

    const KEY_TELEPHONE = 'telephone';

    /**#@-*/

    /**
     * Get country id
     *
     * @return string
     */
    public function getCountryId();

    /**
     * Set country id
     *
     * @param string $countryId
     * @return $this
     */
    public function setCountryId($countryId);

    /**
     * Get region name
     *
     * @return string
     */
    public function getRegion();

    /**
     * Set region name
     *
     * @param string $region
     * @return $this
     */
    public function setRegion($region);

    /**
     * Get region id
     *
     * @return int
     */
    public function getRegionId();

    /**
     * Set region id
     *
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId);

    /**
     * Get city name
     *
     * @return string
     */
    public function getCity();

    /**
     * Set city name
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * Get city id
     *
     * @return string
     */
    public function getCityId();

    /**
     * Set city id
     *
     * @param string $cityId
     * @return $this
     */
    public function setCityId($cityId);

    /**
     * Get district name
     *
     * @return string
     */
    public function getDistrict();

    /**
     * Set district name
     *
     * @param string $district
     * @return $this
     */
    public function setDistrict($district);

    /**
     * Get district id
     *
     * @return string
     */
    public function getDistrictId();

    /**
     * Set district id
     *
     * @param string $districtId
     * @return $this
     */
    public function setDistrictId($districtId);

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode();

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return $this
     */
    public function setPostcode($postcode);

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet();

    /**
     * Set street
     *
     * @param string $street
     * @return $this
     */
    public function setStreet($street);

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstname();

    /**
     * Set first name
     *
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname);

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastname();

    /**
     * Set last name
     *
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname);

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

    /**
     * Get telephone number
     *
     * @return string
     */
    public function getTelephone();

    /**
     * Set telephone number
     *
     * @param string $telephone
     * @return $this
     */
    public function setTelephone($telephone);

}
