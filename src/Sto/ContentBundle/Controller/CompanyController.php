<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\FeedbackCompany;
use Sto\CoreBundle\Entity\FeedbackAnswer;
use Sto\ContentBundle\Form\FeedbackCompanyType;

class CompanyController extends Controller
{
    /**
     * @Route("/catalog", name="content_companies")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $this->get('sto_content.manager.city')->selectedCity();

        $repository = $em->getRepository('StoCoreBundle:Company');
        $query = $repository->createQueryBuilder('company')
            ->select('company, s')
            ->join('company.specialization', 's')
            ->where('company.visible = true')
            ->andWhere('company.cityId = :city')
            ->setParameter('city', $city->getId())
        ;

        if ($request->isMethod('POST') and $request->get('search')) {
            $words = explode(" ", trim($request->get('search')));

            foreach ($words as $word) {
                $query->andWhere($query->expr()->orx(
                    $query->expr()->like('company.name',':search'),
                    $query->expr()->like('company.fullName',':search'),
                    $query->expr()->like('company.description',':search'),
                    $query->expr()->like('company.slogan',':search'),
                    $query->expr()->like('s.name',':search')
                ))
                ->setParameter('search', '%' . $word . '%');
            }
        }
        $companies = $query
            ->getQuery()
            ->getArrayResult()
        ;

        foreach ($companies as $key => $value) {
            $companies[$key]['specialization_template'] = $this
                ->render('StoContentBundle:Company:specialization_list.html.twig', ['specializations' => $value['specialization']])->getContent()
            ;

            $companies[$key]['workingTime_template'] = $this
                ->render('StoContentBundle:Company:workingTime_list.html.twig', ['workingTime' => $value['workingTime']])->getContent()
            ;
        }

        return [
            'companies' => json_encode($companies),
            'city' => $city
        ];
    }

    /**
     * @Route("/company/{id}", name="content_company_show", options={"expose"=true})
     * @Route("/company/{id}/{tab}", name="content_company_show_tab", options={"expose"=true})
     * @Method("GET")
     * @Template()
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function showAction(Company $company, $tab = 'information')
    {
        return [
            'company' => $company,
            'tab'     => $tab
        ];
    }

    /**
     * Ajax get companies
     *
     * @Route("/ajax/getall", name="company_ajax_get_all", options={"expose"=true})
     * @Template()
     */
    public function getAllAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('StoCoreBundle:Company')->getCompaniesByCity($this->get('sto_content.manager.city')->selectedCity());

        if (!$companies) {
            return new Response('Companies Not found.', 500);
        }

        return [
            'companies' => $companies,
        ];
    }

    /**
     * @Route("/company-feedbacks/{id}", name="company_feedbacks_show")
     * @Method("POST")
     * @Template()
     */
    public function feedbacksAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoCoreBundle:FeedbackCompany')
            ->createQueryBuilder('fc')
            ->where('fc.companyId = :company')
            ->setParameter('company', $id)
            ->getQuery()
        ;

        $feedbacks = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page',1),
            3
        );

        if ($this->getUser()) {
            $manager = $em->getRepository('StoUserBundle:User')
                ->createQueryBuilder('user')
                ->select('user')
                ->join('user.companies', 'company')
                ->where('user.id = :user_id AND company.id = :company')
                ->setParameter('user_id', $this->getUser()->getId())
                ->setParameter('company', $id)
                ->getQuery()
                ->getResult()
                ;
        }
        if (isset($manager) && count($manager)>0)
            $isManager = true;
        else
            $isManager = false;

        return [
            'feedbacks' => $feedbacks,
            'companyId' => $id,
            'isManager' => $isManager,
        ];
    }

    /**
     * @Route("/company/{id}/feedback/add", name="content_company_feedbacks_add")
     * @Method("GET")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template()
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function addFeedbackAction(Company $company)
    {
        $form = $this->createForm(new FeedbackCompanyType, (new FeedbackCompany)->setCompany($company));

        return [
            'form' => $form->createView(),
            'company' => $company
        ];
    }

    /**
     * @Route("/company/{id}/feedback/create", name="content_company_feedbacks_create")
     * @Method("POST")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     * @Template("StoContentBundle:Company:addFeedback.html.twig")
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function createFeedbackAction(Request $request, Company $company)
    {
        $entity = new FeedbackCompany;
        $form = $this->createForm(new FeedbackCompanyType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser())
                ->setCompany($company)
                ->setPluses(0)
                ->setMinuses(0)
                ->setPublished(false)
                ->setIp($request->getClientIp())
            ;
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('content_company_show', ['id' => $company->getId()]));
        }

        return [
            'form'    => $form->createView(),
            'company' => $company
        ];
    }

    /**
     * @Route("/company/{id}/feedback-answer/add", name="content_company_feedbacks_answer_add")
     * @Method("POST")
     * @Template()
     * @Secure(roles="IS_AUTHENTICATED_FULLY")
     */
    public function addFeedbackAnswerAction(Request $request, $id)
    {
        $feedback_id = $request->get('feedback_id');
        $em = $this->getDoctrine()->getManager();
        $feedback = $em->getRepository('StoCoreBundle:Feedback')->findOneById($feedback_id);
        if (!$feedback)
            return new Responce(500, 'Feedback Not found.');
        $answer = new FeedbackAnswer();
        $answer->setAnswer($request->get('answer'));
        $answer->setOwner($this->getUser());
        $answer->setFeedback($feedback);
        $answer->setDate(new \DateTime('now'));
        $em->persist($answer);
        $em->flush();

        return $this->redirect($this->generateUrl('content_company_show', ['id' => $id]));
    }
}
