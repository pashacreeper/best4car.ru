<?php

namespace Sto\ContentBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sto\ContentBundle\Form\Extension\ChoiceList\SubscriptionType;
use Sto\ContentBundle\Form\Type\CompanySubscriptionType;
use Sto\ContentBundle\Form\Type\DealSubscriptionType;
use Sto\CoreBundle\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SubscriptionController
 *
 * @package Sto\ContentBundle\Controller
 *
 * @Route("subscriptions")
 */
class SubscriptionController extends Controller
{
    /**
     * @Template
     * @Route("/", name="subscription_list")
     * @Secure("ROLE_USER")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        return compact('user');
    }

    /**
     * @Template
     * @Route("/manage", name="subscription_manage")
     * @Secure("ROLE_USER")
     */
    public function manageAction()
    {
        $em = $this->getDoctrine();
        $subscriptionRepository = $em->getRepository('StoCoreBundle:Subscription');

        $user = $this->getUser();

        $companySubscriptions = $subscriptionRepository->findBy(['type' => SubscriptionType::COMPANY, 'user' => $user]);
        $dealSubscriptions = $subscriptionRepository->findBy(['type' => SubscriptionType::DEAL, 'user' => $user]);

        return compact('user', 'companySubscriptions', 'dealSubscriptions');
    }

    /**
     * @Template
     * @Route("/store", name="subscription_store")
     */
    public function storeAction(Request $request)
    {
        $subscription = new Subscription();

        $type = $request->request->get('type');
        $typeClass = $this->getSubscriptionTypeClass($type);

        $form = $this->createForm($typeClass, $subscription, [
            'subscriptions' => null
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $subscription->setUser($this->getUser());

            $em->persist($subscription);
            $em->flush();

            $html = $this->renderView('StoContentBundle:Subscription:_listElement.html.twig', [
                'subscription' => $subscription
            ]);

            return new JsonResponse([
                'success' => true,
                'html' => $html
            ]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Template()
     * @param $subscriptions
     *
     * @return Response
     */
    public function renderCompanySubscriptionFormAction($subscriptions)
    {
        $form = $this->createForm(new CompanySubscriptionType(), null, ['subscriptions' => $subscriptions]);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Template()
     * @param $subscriptions
     *
     * @return Response
     */
    public function renderDealSubscriptionFormAction($subscriptions)
    {
        $form = $this->createForm(new DealSubscriptionType(), null, ['subscriptions' => $subscriptions]);

        return [
            'form' => $form->createView()
        ];
    }

    protected function getSubscriptionTypeClass($type)
    {
        $types = SubscriptionType::getTypeAndClass();

        if (! array_key_exists($type, $types)) {
            throw new \Exception('Wrong type of subscription provided');
        }

        return new $types[$type];
    }
}