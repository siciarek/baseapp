<?php

namespace Application\MainBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

class Admin extends \Sonata\AdminBundle\Admin\Admin
{
    protected $exportDateFormat = 'Y-m-d H:i';
    protected $maxPerPage = 25;
    protected $maxPageLinks = 10;
    protected $supportsPreviewMode = false;

    public function getDataSourceIterator() {

        $datagrid = $this->getDatagrid();
        $datagrid->buildPager();

        $dataSourceIterator = $this->getModelManager()->getDataSourceIterator($datagrid, $this->getExportFields());

        if($dataSourceIterator instanceof \Exporter\Source\DoctrineORMQuerySourceIterator) {
            $dataSourceIterator->setDateTimeFormat($this->exportDateFormat);
        }

        return $dataSourceIterator;
    }
    
    public function getContainer() {
        return $this->getConfigurationPool()->getContainer();
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array(
            'xls',
            'csv',
        );
    }
}