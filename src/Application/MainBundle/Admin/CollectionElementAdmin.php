<?php

namespace Application\MainBundle\Admin;

use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class CollectionElementAdmin extends Admin {
    
    protected function configureDatagridFilters(DatagridMapper $datagrid) {
        $datagrid
            ->add('enabled')
            ->add('collection')
            ->add('type')
        ;
    }
    
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
            ->add('id')
            ->add('enabled')
            ->add('collection')
            ->add('type')
            ->add('name')
            ->add('info')
            ->add('createdAt')
            ->add('createdBy')
            ->add('updatedAt')
            ->add('updatedBy')
        ;
    }
    
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->add('enabled', 'boolean', [
                'editable' => true,
            ])
            ->addIdentifier('id')
            ->add('type')
            ->addIdentifier('name')
            ->addIdentifier('info')
            ->add('collection')
            ->add('createdAt')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'show' => [],
                ],
        ]);
    }
    
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->with('collection.element.name')
            ->add('enabled')
            ->add('type', 'choice', [
                'choices' => [
                    'unknown' => 'unknown',
                    'basic' => 'basic',
                    'enhanced' => 'enhanced',
                    'advanced' => 'advanced',
                ],
            ])
            ->add('translations', 'a2lix_translations', [
                'label' => false,
                'fields' => [
                    'name' => [
                        'field_type' => 'text',
                    ],
                    'info' => [
                        'field_type' => 'text',
                    ]
                ]
            ])
            ->end()
        ;
    }
}