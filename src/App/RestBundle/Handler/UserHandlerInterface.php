<?php

namespace App\RestBundle\Handler;

use App\RestBundle\Model\UserInterface;

interface UserHandlerInterface
{
    /**
     * Get a User given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return UserInterface
     */
    public function get($id);

    /**
     * Get a list of Users.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post User, creates a new User.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return UserInterface
     */
    public function post(array $parameters);

    /**
     * Edit a User.
     *
     * @api
     *
     * @param UserInterface   $user
     * @param array           $parameters
     *
     * @return UserInterface
     */
    public function put(UserInterface $user, array $parameters);

    /**
     * Partially update a User.
     *
     * @api
     *
     * @param UserInterface   $user
     * @param array           $parameters
     *
     * @return UserInterface
     */
    public function patch(UserInterface $user, array $parameters);
}