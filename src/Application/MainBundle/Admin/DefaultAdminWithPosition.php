<?php
namespace Application\MainBundle\Admin;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class DefaultAdminWithPosition extends Admin
{
    public function createQuery($context = "list")
    {
        $class = $this->getClass();
        $repository = $this->getModelManager()->getEntityManager($this->getClass())->getRepository($class);
        $repository = $repository->createQueryBuilder("p");

        if ($context == "list") {
            $filter = $this->getFilterParameters();
            if (empty($filter["_sort_by"])) {
                $repository->addOrderBy("p.position", "DESC");
            }
        }

        return new ProxyQuery($repository);
    }

    public function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        if (!$this->hasRequest()) {
            $this->datagridValues = array(
                "_page"       => 1,
                "_sort_order" => "DESC", // sort direction
                "_sort_by"    => "position", // field name
                "_per_page"   => $this->maxPerPage,
            );
        }
    }

}