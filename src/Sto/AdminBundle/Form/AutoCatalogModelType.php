<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AutoCatalogModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'dict.fields.name'
            ])
            ->add('visible', null, [
                'label' => 'Видимость'
            ])
            ->add('parent', 'entity', array(
                'label' => 'dict.fields.parent',
                'class' => 'StoCoreBundle:AutoCatalogCar',
                'query_builder' => function($repository) { return $repository->createQueryBuilder('ab')
                    //->where('ab.discr = :discr')
                    //->setParameter('discr', 'autocatalog_car')
                    ->orderBy('ab.name', 'ASC'); },
                'property' => 'name',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\AutoCatalogModel'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_autocatalog';
    }
}
