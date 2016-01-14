<?php

namespace Application\MainBundle\Admin;

use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class PageGroupAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                
            ->tab('Group')
                ->with(null, [ 'box_class' => null, ])
                    ->add('enabled')
                    ->add('icon')
                    ->add('translations', 'a2lix_translations', [
                        'label' => false,
                        'fields' => [
                            'name' => [
                                'field_type' => 'text',
                            ],
                        ]
                    ])
                ->end()
            ->end()
            
            ->tab('visibility.name')
                ->with(null, [ 'box_class' => null, ])
                    ->add('role', 'choice', [
                        'choices' => [
                            'IS_AUTHENTICATED_ANONYMOUSLY' => 'visibility.public',
                            'ROLE_USER' => 'visibility.private',
                            'ROLE_ADMIN' => 'visibility.admin',
                        ],
                        'label' => false,
                        'expanded' => true,
                        'multiple' => false,
                        'required' => true,
                    ])
 
                ->end()
            ->end();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('enabled', null, ['editable' => true])
                ->addIdentifier('name')
                ->add('createdAt')
                ->add('_action', 'actions', [
                    'actions' => [
                        'edit' => [],
                        'delete' => [],
                        'show' => [],
                    ],
        ]);
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
                ->add('enabled')
                ->add('icon')
                ->add('name')
                ->add('pages')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
                ->end();
    }

}
