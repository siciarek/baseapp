<?php
namespace Application\MainBundle\Doctrine\DBAL\Types;

class CollectionTypeType extends EnumType
{
    const DRAFT    = 'test';
    const TEST     = 'test';
    const REGULAR  = 'rerular';

    protected $default = self::DRAFT;
    protected $values = array(
        self::DRAFT,
        self::TEST,
        self::REGULAR,
    );

    public static function getValues() {
        return self::getType('collection_type')->values;
    }

    public static function getDefault() {
        return self::getType('collection_type')->default;
    }
}