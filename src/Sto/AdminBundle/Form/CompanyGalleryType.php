<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'file', [
                'label' => 'Image',
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'data-image' => 'image',
                ]
            ])
            ->add('name', null, [
                'label' => 'Name'
            ])
            ->add('visible', null, [
                'label' => 'Visbible',
                'required' => false,
                'render_optional_text' => false
            ])

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\CompanyGallery'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_company_gallery';
    }
}
