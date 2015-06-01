<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 27/05/2015
 * Time: 22:33
 */

namespace App\RestBundle\Handler;


use App\RestBundle\Exception\InvalidFormException;
use App\RestBundle\Form\UserType;
use App\RestBundle\Model\UserInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

class UserHandler implements UserHandlerInterface{

    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Page.
     *
     * @param mixed $id
     *
     * @return UserInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }
    /**
     * Get a list of Users.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }
    /**
     * Edit a Page.
     *
     * @param UserInterface $user
     * @param array         $parameters
     *
     * @return UserInterface
     */
    public function put(UserInterface $user, array $parameters)
    {
        return $this->processForm($user, $parameters, 'PUT');
    }
    /**
     * Partially update a Page.
     *
     * @param UserInterface $user
     * @param array         $parameters
     *
     * @return UserInterface
     */
    public function patch(UserInterface $user, array $parameters)
    {
        return $this->processForm($user, $parameters, 'PATCH');
    }


    public function post(array $parameters)
    {
        $user = $this->createUser(); // factory method create an empty user

        // Process form does all the magic, validate and hydrate the user Object.
        return $this->processForm($user, $parameters, 'POST');
    }

    /**
     * Processes the form.
     *
     * @param UserInterface $user
     * @param array         $parameters
     * @param String        $method
     *
     * @return UserInterface
     *
     * @throws \App\RestBundle\Exception\InvalidFormException
     */
    private function processForm(UserInterface $user, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new UserType(), $user, array('method' => $method));
        $form->submit($parameters);
        if ($form->isValid()) {

            $user = $form->getData();
            $this->om->persist($user);
            $this->om->flush($user);
            return $user;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createUser()
    {
        return new $this->entityClass();
    }
}