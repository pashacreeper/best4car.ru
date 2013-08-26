<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CompanyContactsAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type')
            ->add('value')
        ;
    }

}
