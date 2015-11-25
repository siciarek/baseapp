<?php

namespace Application\MainBundle\Admin;

use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class OwnerAdmin extends Admin
{

    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('enabled')
            ->add('type')
            ->add('name')
            ->add('lastName')
            ->add('createdAt');
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('enabled')
            ->add('type')
            ->add('name')
            ->add('lastName')
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
            ->add('name')
            ->add('lastName')
            ->add('info')
            ->add('createdAt')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit'   => [ ],
                    'delete' => [ ],
                    'show'   => [ ],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('owner.name')
            ->add('enabled')
            ->add('type', 'choice', [
                'choices' => [
                    'person' => 'person',
                    'organisation' => 'organisation',
                ],
            ])
            ->add('name')
            ->add('lastName')
            ->add('info')
            ->add('description', 'ckeditor', [
                'required' => false,
            ])
            ->end();
    }
}