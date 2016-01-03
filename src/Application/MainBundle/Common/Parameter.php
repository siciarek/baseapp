<?php

namespace Application\MainBundle\Common;

use Application\MainBundle\Entity as E;

/**
 * Entity parameter service
 */
class Parameter
{

    const TYPE_STRING = 'string';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getList($entity, $array = true)
    {
        list($entityType, $entityId) = $this->getEntityData($entity);

        $criteria = [
            'entityType' => $entityType,
            'entityId' => $entityId,
            'deletedAt' => null,
        ];

        $list = $this->em->getRepository('ApplicationMainBundle:Parameter')->findBy($criteria);

        if ($array === false) {
            return $list;
        }

        $array = [];

        foreach ($list as $e) {
            $array[$e->getName()] = $e->getValue();
        }

        return $array;
    }

    /**
     * Return parameter value
     * 
     * @param type $entity
     * @param type $name
     */
    public function get($entity, $name)
    {
        $param = $this->findParameter($entity, $name);

        if (!$param instanceof \Application\MainBundle\Entity\Parameter) {
            throw new \Exception('Parameter not found.');
        }

        return $param->getValue();
    }

    protected function findParameter($entity, $name)
    {

        list($entityType, $entityId) = $this->getEntityData($entity);

        $criteria = [
            'entityType' => $entityType,
            'entityId' => $entityId,
            'name' => $name,
        ];

        $param = $this->em->getRepository('ApplicationMainBundle:Parameter')->findOneBy($criteria);

        return $param;
    }

    /**
     * Set parameter value, if parameter does not exist, create one
     * 
     * @param type $entity
     * @param type $name
     * @param type $value
     * @param type $type parameter data type (string, integer, boolean, text)
     */
    public function set($entity, $name, $value, $type = self::TYPE_STRING)
    {
        $param = $this->findParameter($entity, $name);

        if (!$param instanceof \Application\MainBundle\Entity\Parameter) {

            list($entityType, $entityId) = $this->getEntityData($entity);

            $param = new E\Parameter();

            $param->setEntityType($entityType)
                    ->setEntityId($entityId)
                    ->setName($name)
                    ->setType($type)
            ;
        }

        if ($param->isDeleted()) {
            $param->restore();
        }

        $param->setValue(var_export($value, true));

        $this->em->persist($param);
        $this->em->flush();

        return $param;
    }

    public function remove($entity, $name)
    {
        $param = $this->findParameter($entity, $name);

        if ($param instanceof \Application\MainBundle\Entity\Parameter) {
            $this->em->remove($param);
            $this->em->flush();
        }

        return true;
    }

    protected function getEntityData($entity)
    {
        $entityType = get_class($entity);
        $entityId = $entity->getId();

        return [$entityType, $entityId];
    }

}
