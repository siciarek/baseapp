Guide to Symfony 3 upgrade
--------------------------

Compatible Bundles List:

https://docs.google.com/spreadsheets/d/1GOxJ2lNpLxN12YyZ87Bhsxb0iLAQ7e4UDZbJSly8Sy8/edit#gid=0

1. Update file ``vendor/pixassociates/sortable-behavior-bundle/Pix/SortableBehaviorBundle/Resources/config/services.yml``

change

.. code-block::yaml

    arguments:
        - @pix_sortable_behavior.position

to

.. code-block::yaml

    arguments:
        - "@pix_sortable_behavior.position"

2. Add somewhere in your project services definition:

.. code-block::yaml

    services:
        # Quick and dirty trick to enable SonataUserBundle in Symfony 3 project
        fos_user.entity_manager:
            alias: fos_user.object_manager

3. Add to file ``vendor/doctrine/data-fixtures/lib/Doctrine/Common/DataFixtures/Purger/ORMPurger.php`` (line 148)

.. code-block::php

    private function getCommitOrder(EntityManagerInterface $em, array $classes)
    {
        return []; // Quick and dirty trick to enable DoctrineFixturesBundle in Symfony 3 project

