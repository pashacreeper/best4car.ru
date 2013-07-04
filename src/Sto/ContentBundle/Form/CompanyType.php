<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Sto\AdminBundle\Form\CompanyPhoneType;
use Sto\ContentBundle\Form\CompanyWorkingTimeType;
use Sto\ContentBundle\Form\CompanyManagerType;
use Sto\AdminBundle\Form\CompanyGalleryType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Сокращенное наименование'
            ])
            ->add('phones','collection', array(
                'label' => '',
                'type' => new CompanyPhoneType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
                'widget_add_btn' =>[
                    'icon' => 'b4c-plus',
                    'label' => 'add phone',
                    'attr' => [
                         'class' => 'btn btn-primary btn-small'
                    ]
                ],
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => [
                        'label' => '-',
                        'attr' => [
                            'class' => 'btn btn-danger btn-small '
                        ]
                    ],
                    'widget_control_group' => false,
                )
            ))
            ->add('city', 'entity', [
                'label' => 'Город',
                'class' => 'StoCoreBundle:Dictionary\Country',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('address', null, [
                'label' => 'Адрес',
            ])
            ->add('workingTime','collection', array(
                'label' => ' ',
                'type' => new CompanyWorkingTimeType($options['em']),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
                'widget_add_btn' =>[
                    'icon' => 'b4c-plus',
                    'label' => ' ',
                    'attr' => [
                         'class' => 'btn btn-primary btn-small'
                    ]
                ],
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => [
                        'label' => '-',
                        'attr' => [
                            'class' => 'btn btn-danger btn-small '
                        ]
                    ],
                    'widget_control_group' => false,
                )
            ))
            ->add('hourPrice', null, [
                'label' => 'Стоимость нормочаса',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'input-small'
                ],
            ])
            ->add('currency', null, [
                'label' => 'Валюта',
            ])
            ->add('services', 'entity', [
                'label' => 'Услуги',
                'multiple' => true,
                'class' => 'StoCoreBundle:Dictionary\Company',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'select2',
                    'style' => 'width:100%;'
                ]
            ])
            ->add('specialization', 'entity', [
                'label' => 'Основная специализация',
                'multiple' => true,
                'class' => 'StoCoreBundle:Dictionary\Company',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.parent is null')
                    ;
                },
                'attr' => [
                    'class' => 'select2',
                    'style' => 'width:100%;'
                ]
            ])
            ->add('logo', null, [
                'label' => 'Логотип компании',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'data-image' => 'logo',
                ]
            ])
            ->add('slogan', 'textarea', [
                'label' => 'Девиз (слоган)',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'rows' => '4',
                    'style' => 'width: 100%'
                ]
            ])
            ->add('fullName', 'text', [
                'label' => 'Полное наименование',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'style' => 'width: 100%',
                    'class' => 'input-xxlarge'
                ]
            ])
            ->add('web', 'text', [
                'label' => 'Адрес сайта',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('additionalServices', 'entity', [
                'label' => 'Дополнительные услуги',
                'required' => false,
                'render_optional_text' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => 'StoCoreBundle:Dictionary\AdditionalService',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.name')
                    ;
                },
            ])
            ->add('skype', null, [
                'label' => 'Skype',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('email', 'email', [
                'label' => 'E-mail',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('createtDate', 'date', [
                'widget' => 'single_text',
                'datepicker' => true,
                'label' => 'Начало работы на рынке',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('notes', 'textarea', [
                'label' => 'Дополнительное описание деятельности компании',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'rows' => '3',
                    'style' => 'width: 100%'
                ]
            ])
            ->add('gps', 'text', [
                'label' => 'Координаты Yandex-карты',
                'required' => true,
                'attr' => [
                    'onclick'=>"$('#myModal').modal();"
                ]
            ])
            ->add('linkVK', 'text', [
                'label' => 'Группа Vkontakte',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('linkFB', 'text', [
                'label' => 'Страница Facebook',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('linkTW', 'text', [
                'label' => 'Twitter',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('companyManager','collection', array(
                'label' => ' ',
                'type' => new CompanyManagerType($options['em']),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
                'widget_add_btn' =>[
                    'icon' => 'b4c-user-add',
                    'label' => 'add manager',
                    'attr' => [
                         'class' => 'btn btn-primary btn-small'
                    ]
                ],
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => [
                        'label' => '-',
                        'attr' => [
                            'class' => 'btn btn-danger btn-small '
                        ]
                    ],
                    'widget_control_group' => false,
                )
            ))
            ->add('contacts','collection', array(
                'label' => ' ',
                'type' => new CompanyContactsType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
                'widget_add_btn' =>[
                    'icon' => 'b4c-plus',
                    'label' => 'add contact',
                    'attr' => [
                         'class' => 'btn btn-primary btn-small'
                    ]
                ],
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => [
                        'label' => '-',
                        'attr' => [
                            'class' => 'btn btn-danger btn-small '
                        ]
                    ],
                    'widget_control_group' => false,
                )
            ))
            ->add('gallery','collection', array(
                'label' => ' ',
                'type' => new CompanyGalleryType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
                'widget_add_btn' =>[
                    'icon' => 'b4c-plus',
                    'label' => 'add photo',
                    'attr' => [
                         'class' => 'btn btn-primary btn-small'
                    ]
                ],
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => [
                        'label' => '-',
                        'attr' => [
                            'class' => 'btn btn-danger btn-small '
                        ]
                    ],
                    'widget_control_group' => false,
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'Sto\CoreBundle\Entity\Company'
            ])
            ->setRequired([
                'em',
            ])
            ->setAllowedTypes([
                'em' => 'Doctrine\Common\Persistence\ObjectManager',
            ])
        ;
    }

    public function getName()
    {
        return 'sto_content_company_registration';
    }
}