<?php

namespace Application\MainBundle\Admin;

use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class CollectionAdmin extends Admin
{
    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('enabled')
            ->add('type')
            ->add('name')
            ->add('version')
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('enabled')
            ->add('type')
            ->add('name')
            ->add('info')
            ->add('description')
            ->add('createdAt')
            ->add('createdBy')
            ->add('updatedAt')
            ->add('updatedBy')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('enabled', 'boolean', [
                'editable' => true,
            ])
            ->add('type')
            ->addIdentifier('id')
            ->addIdentifier('version')
            ->addIdentifier('name')
            ->add('info')
            ->add('createdAt')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'show' => [],
                ],
        ]);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('collection.name')
            ->add('enabled')
            ->add('type', 'choice', [
                'choices' => [
                    'draft' => 'draft',
                    'regular' => 'regular',
                    'test' => 'test',
                ],
            ])
            ->add('name')
            ->add('info')
            ->add('description', 'ckeditor', [
                'required' => false,
            ])
            ->end()
            ->with('collection.element.name_plural')
            ->add('elements', 'sonata_type_collection',
                [
                    'label' => false,
                    'required' => false,
                    'by_reference' => false,
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
