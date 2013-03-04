<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Deal;

class LoadDealData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $types = ['Скидка', 'Маркетинговое мероприятие', 'Тест-драйв', 'Презентация, день открытых дверей.', 'Распродажа', 'Сезонное предложение'];

        for ($i=1; $i < 43 ; $i++) {
            $deal = new Deal;
            $deal->setName('Test deal - ' . $i);
            $deal->setCompany($this->getReference("company[" . rand(1, 38) . "]"));
            $deal->setStartDate(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020)));
            $deal->setEndDate(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020)));
            $deal->setStartTime(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020) . ' ' . rand(0, 23) . ':' . rand(0, 59)));
            $deal->setEndTime(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020) . ' ' . rand(0, 23) . ':' . rand(0, 59)));
            $deal->setType(rand(1,5));

            $manager->persist($deal);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
