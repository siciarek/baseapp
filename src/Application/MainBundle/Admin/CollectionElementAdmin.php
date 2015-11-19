<?php

namespace Application\MainBundle\Admin;

use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class CollectionElementAdmin extends Admin {
    
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->add('enabled', 'boolean', [
                'editable' => true,
            ])
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('info')
            ->add('collection')
            ->add('createdAt')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
        ]);
    }
    
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->with('collection.element.name')
            ->add('enabled')
            ->add('name')
            ->add('info')
            ->end()
        ;
    }
}