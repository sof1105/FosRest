<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 31/05/2015
 * Time: 09:54
 */

namespace App\RestBundle\Model;


Interface UserInterface {


    /**
     * Set name
     *
     * @param string $name
     * @return UserInterface
     */
    public function setName($name);


    /**
     * Get name
     *
     * @return string
     */
    public function getName();


    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email);


    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set address
     *
     * @param string $address
     * @return UserInterface
     */
    public function setAddress($address);

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress();
}