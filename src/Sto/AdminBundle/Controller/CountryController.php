<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\Country;
use Sto\AdminBundle\Form\CountryType;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Country controller.
 *
 * @Route("/admin/country")
 */
class CountryController extends Controller
{
    /**
     * Lists all Country entities.
     *
     * @Route("/", name="country")
     * @Template()
     */
    public function indexAction()
    {

        $session = $this->get('session');
        $filter_name = $session->get('filter_country_name');

        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('StoCoreBundle:Country')
            ->createQueryBuilder('entity');

        if (isset($filter_name) && !empty($filter_name)){
                $query->where( $query->expr()->like('entity.name', $query->expr()->literal('%' . $filter_name . '%')) );
            } else
                $session->set('filter_country_name', '');

        $query->orderBy('entity.id')
            ->getQuery();


        $def_limit = $this->container->getParameter('pagination_default_value');

        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('numItemsPerPage', $def_limit)
        );

        return array(
            'pagination' => $pagination,
        );


    }

    /**
     * Displays a form to create a new Country entity.
     *
     * @Route("/new", name="country_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Country();
        $form   = $this->createForm(new CountryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Country entity.
     *
     * @Route("/create", name="country_create")
     * @Method("POST")
     * @Template("StoCoreBundle:Country:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Country();
        $form = $this->createForm(new CountryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('country'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Country entity.
     *
     * @Route("/{id}/edit", name="country_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('country.errors.unable_2_find'));
        }

        $editForm = $this->createForm(new CountryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Country entity.
     *
     * @Route("/{id}/update", name="country_update")
     * @Method("POST")
     * @Template("StoCoreBundle:Country:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('country.errors.unable_2_find'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CountryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('country'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Country entity.
     *
     * @Route("/{id}/delete", name="country_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('country.errors.unable_2_find'));
        }

        $em->remove($entity);
        $em->flush();


        return $this->redirect($this->generateUrl('country'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Set Filter for a Dictionary lists.
     *
     * @Route("/filter", name="country_filter")
     * @Method("POST")
     */
    public function filterAction(Request $request)
    {
        $filter_name = $request->request->get('filter_country_name');
        $session = $this->get('session');
        $session->set('filter_country_name', $filter_name);

        return $this->redirect($this->generateUrl('country'));
    }
}
