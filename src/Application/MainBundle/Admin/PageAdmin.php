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

    public $supportsPreviewMode = true;
    public $last_position = 0;
    private $positionService;

    public function getTemplate($name)
    {
        switch ($name) {
            case 'preview':
                return 'ApplicationMainBundle:CRUD:preview.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

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
                ->add('name')
                ->add('category', 'sonata_type_model', [
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'common.choose_from_the_list',
                    ],
                ])
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
                ->add('enabled')
                ->add('displayTitle')
                ->end()
                ->end()
                ->tab('visibility.name')
                ->with(null, [ 'box_class' => null,])
                ->add('role', 'choice', self::$roles)
                ->end()
                ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {

        $this->last_position = $this->positionService->getLastPosition($this->getRoot()->getClass());

        $listMapper
                ->addIdentifier('name')
                ->add('category')
                ->add('slug')
                ->add('enabled', null, [ 'editable' => true])
                ->add('displayTitle', null, [ 'editable' => true])
                ->add('createdAt')
                ->add('createdBy')
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
                ->add('slug')
                ->add('category')
                ->add('name')
                ->add('title')
                ->add('createdAt')
                ->add('createdBy')
                ->add('updatedAt')
                ->add('updatedBy')
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
                ->add('category')
                ->add('createdAt')
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
                ->end();
    }

}
