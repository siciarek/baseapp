<?php

namespace Application\MainBundle\Admin;

use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Route\RouteCollection;

class PageAdmin extends Admin
{
    public $last_position = 0;

    private $positionService;

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    );

    public function setPositionService(\Pix\SortableBehaviorBundle\Services\PositionHandler $positionHandler)
    {
        $this->positionService = $positionHandler;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->tab('Page')
                ->with(null, [ 'box_class' => null,])
                ->add('enabled')
                ->add('displayTitle')
                ->add('group', 'sonata_type_model', [
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'common.choose_from_the_list',
                    ],
                ])
                ->add('name')
                ->add('translations', 'a2lix_translations', [
                    'label' => false,
                    'fields' => [
                        'title' => [
                            'field_type' => 'text',
                        ],
                        'content' => [
                            'field_type' => 'ckeditor',
                            'config_name' => 'extended',
                            'label' => false,
                        ]
                    ]
                ])
                ->end()
                ->end()
                ->tab('visibility.name')
                ->with(null, [ 'box_class' => null,])
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
                ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        
        $this->last_position = $this->positionService->getLastPosition($this->getRoot()->getClass());

        $listMapper
                ->addIdentifier('name')
                ->add('slug')
                ->add('group')
                ->add('enabled', null, [ 'editable' => true])
                ->add('displayTitle', null, [ 'editable' => true])
                ->add('createdAt')
                ->add('_action', 'actions', [
                    'actions' => [
                        'move' => [ 'template' => 'ApplicationMainBundle:CRUD:list__action_move.html.twig'],
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
                ->add('displayTitle')
                ->add('group')
                ->add('name')
                ->add('slug')
                ->add('title')
                ->add('content', null, [
                    'safe' => true,
                ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('enabled')
                ->add('name')
                ->add('group')
                ->add('createdAt')
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
                ->end();
    }

}
