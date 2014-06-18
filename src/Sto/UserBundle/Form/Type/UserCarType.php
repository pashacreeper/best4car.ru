<?php

namespace Sto\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Sto\ContentBundle\Form\Extension\ChoiceList\TransmissionType;
use Sto\ContentBundle\Form\Extension\ChoiceList\EngineType;
use Sto\ContentBundle\Form\Extension\ChoiceList\WheelType;
use Sto\ContentBundle\Form\Extension\ChoiceList\BodyType;
use Sto\ContentBundle\Form\Extension\ChoiceList\FuelType;

class UserCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mark', null, [
                'label' => 'Марка',
                'required' => true,
                'class' => 'StoCoreBundle:Mark',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('mark')
                        ->where('mark.visible = true')
                        ;
                },
                'attr' => [
                    'class' => 'chzn-select select-car-model',
                    'data-placeholder' => "Выберите варианты"
                ]
            ])
            ->add('model', 'shtumi_dependent_filtered_entity', [
                'label' => 'Модель',
                'required' => true,
                'empty_value' => 'Выбрать модель',
                'entity_alias' => 'model_by_mark',
                'parent_field' => 'mark',
                'attr' => [
                    'class' => 'inputFormEnter'
                ],
            ])
            ->add('year', 'choice', [
                'label' => 'Год',
                'required' => true,
                'choices' => array_combine(range((int) date('Y'), 1965), range((int) date('Y'), 1965)),
                'attr' => [
                    'class' => 'inputFormEnter'
                ],
            ])
            ->add('modification', 'shtumi_dependent_filtered_entity', [
                'label' => 'Модификация',
                'required' => true,
                'empty_value' => 'Выбрать модификацию',
                'entity_alias' => 'modification_by_model',
                'parent_field' => 'model',
                'attr' => [
                    'class' => 'inputFormEnter'
                ],
            ])
            ->add('transmission', 'choice', [
                'label' => 'Трансмиссия',
                'choice_list' => new TransmissionType(),
                'expanded' => true,
                'required' => true,
                'empty_value' => 'Не знаю',
                'attr' => [
                    'class' => 'priceLevelMarker'
                ]
            ])
            ->add('vin', null, [
                'label' => 'VIN-номер',
                'required' => false,
                'attr' => [
                    'class' => 'inputField'
                ]
            ])
            ->add('drive2', null, [
                'label' => 'Ссылка на бортовой журнал на Drive2',
                'required' => false,
                'attr' => [
                    'class' => 'inputField'
                ]
            ])
            ->add('engineType', 'choice', [
                'label' => 'Тип двигателя',
                'empty_value' => 'Выберите тип двигателя',
                'required' => false,
                'choice_list' => new EngineType(),
                'attr' => [
                    'class' => 'styled'
                ]
            ])
            ->add('engineModel', null, [
                'label' => 'Модель двигателя',
                'required' => false,
                'attr' => [
                    'class' => 'inputField'
                ]
            ])
            ->add('engineVolume', 'text', [
                'label' => 'Объем',
                'required' => false,
                'attr' => [
                    'class' => 'smallInputField'
                ]
            ])
            ->add('enginePower', 'text', [
                'label' => 'Мощность',
                'required' => false,
                'attr' => [
                    'class' => 'smallInputField'
                ]
            ])
            ->add('fuelTypes', 'choice', [
                'label' => 'Топливо',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choice_list' => new FuelType(),
                'attr' => [
                    'class' => 'priceLevelMarker inline'
                ]
            ])
            ->add('wheelType', 'choice', [
                'label' => 'Привод',
                'required' => false,
                'empty_value' => 'Выберите тип привода',
                'choice_list' => new WheelType(),
                'attr' => [
                    'class' => 'styled inputField'
                ]
            ])
            ->add('bodyType', 'choice', [
                'label' => 'Тип кузова',
                'required' => false,
                'empty_value' => 'Выберите тип кузова',
                'choice_list' => new BodyType(),
                'attr' => [
                    'class' => 'styled inputField'
                ]
            ])
            ->add('images', 'collection', array(
                'label' => ' ',
                'type' => new CarImageType(),
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\UserBundle\Entity\UserCar',
        ]);
    }

    public function getName()
    {
        return 'sto_user_car';
    }
}
