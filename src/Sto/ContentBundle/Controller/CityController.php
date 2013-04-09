<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sto\CoreBundle\Entity\Dictionary\City;

class CityController extends Controller
{
    /**
     * Ajax get cities
     *
     * @Route("/ajax/get-user-city", name="get_user_city")
     */
    public function getUserCity()
    {
        $session = $this->getRequest()->getSession();
        $city = $session->get('user_city');
        if (!$city)
            $city = 'Ваш город';
        $response = new Response(json_encode(array('user_city' => $city)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Ajax get cities
     *
     * @Route("/ajax/get-cities", name="city_ajax")
     */
    public function getCitiesAjax(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository('StoCoreBundle:Dictionary\Country')
            ->createQueryBuilder('city')
            ->where('city.parent is not null')
            ->getQuery()
            ->getArrayResult();

        if (!$cities)
            return new Responce(500, 'Companies Not found.');

        $response = new Response(json_encode(array('cities' => $cities)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Ajax save city
     * @Route("/ajax/save-city", name="city_ajax_save")
     */
    public function saveCityAjax(Request $request){
        $session = $request->getSession();
        if ($request->get('city')) {
            $session->set('user_city', $request->get('city'));
        }

        $city = $session->get('user_city');

        $response = new Response(json_encode(array('result' => $city)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}