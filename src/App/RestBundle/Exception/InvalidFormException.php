<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 31/05/2015
 * Time: 10:40
 */

namespace App\RestBundle\Exception;


class InvalidFormException extends \RuntimeException
{
    protected $form;

    public function __construct($message, $form = null)
    {
        parent::__construct($message);
        $this->form = $form;
    }
    /**
     * @return array|null
     */
    public function getForm()
    {
        return $this->form;
    }
}