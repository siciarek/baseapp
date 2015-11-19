<?php

namespace Application\MainBundle\Admin;

use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class CollectionAdmin extends Admin {
    
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->add('enabled', 'boolean', [
                'editable' => true,
            ])
            ->addIdentifier('id')
            ->addIdentifier('version')
            ->addIdentifier('name')
            ->add('description')
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
            ->with('collection.name')
            ->add('enabled')
            ->add('name')
            ->add('description', 'ckeditor', [
                'required' => false,
            ])
            ->end()
            ->with('collection.element.name_plural')
            ->add('elements', 'sonata_type_collection',
                [
                    'label' => false,
                    'required' => false,
                    'by_reference' => false
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                ]
            )
            ->end()
        ;
    }
}