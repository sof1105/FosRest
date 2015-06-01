<?php

namespace App\RestBundle\Controller;

use App\RestBundle\Exception\InvalidFormException;
use App\RestBundle\Form\UserType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations;

class UserController extends FOSRestController
{

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets all users",
     *   output = "App\RestBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="user")
     *
     * @return array
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getUsersAction()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppRestBundle:User')->findAll();
    }


    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a user for a given id",
     *   output = "App\RestBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="user")
     *
     * @param Request $request the request object
     * @param int     $id      the page id
     *
     * @return array
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getUserAction($id,Request $request)
    {
        return $this->getDoctrine()->getManager()->getRepository('AppRestBundle:User')->findOneById($id);
    }



    /**
     *
     * @ApiDoc(
     * input = "App\UserBundle\Form\UserType",
     *   resource = true,
     *   description = "Creates a new page from the submitted data.",
     *   input = "App\RestBundle\Form\UserType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AppRestBundle:User:newUser.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postUserAction(Request $request)
    {
        try {

            $form = new UserType();
            $newUser = $this->container->get('app_rest.user.handler')->post(
                $request->request->get($form->getName())
            );

            return $this->redirect($this->generateUrl('get_user',array(
                'id' => $newUser->getId(),
                '_format' => $request->get('_format')
            )));

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }


    /**
     * Update existing user from the submitted data or create a new user at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "App\RestBundle\Form\UserType",
     *   statusCodes = {
     *     201 = "Returned when the User is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the page id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function putUserAction(Request $request, $id)
    {
        try {
            $form = new UserType();
            if (!($user = $this->container->get('app_rest.user.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $user = $this->container->get('app_rest.user.handler')->post(
                    $request->request->get($form->getName())
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $user = $this->container->get('app_rest.user.handler')->put(
                    $user,
                    $request->request->get($form->getName())
                );
            }

            $routeOptions = array(
                'id' => $user->getId(),
            );

            return $this->routeRedirectView('get_user', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }

    }

    /**
     * Deletes a Task entity.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete existing user.",
     *   requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *      }
     *   },
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when entity does not exists",
     *     400 = "Returned when form can not be submitted",
     *   }
     * )
     * @Annotations\View(statusCode=204)
     */
    public function deleteUserAction($id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppRestBundle:User')->findOneById($id);

            if ($entity) {
                $em->remove($entity);
                $em->flush();

           }else{
                throw $this->createNotFoundException('Unable to find user entity.');
            }
    }



    /**
     * Presents the form to use to create a new page.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return FormTypeInterface
     */
    public function newUserAction()
    {
        return $this->createForm(new UserType());
    }

    /**
     * Creates a form to delete a Task entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(null, array('csrf_protection' => false))
            ->setAction($this->generateUrl('delete_user', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }

}
