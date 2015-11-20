<?php
namespace Application\MainBundle\Doctrine\DBAL\Types;

class CollectionElementTypeType extends EnumType
{
    const UNKNOWN = 'unknown';
    const BASIC = 'basic';
    const ENHANCED = 'enhanced';
    const ADVANCED = 'advanced';

    protected $default = self::UNKNOWN;
    protected $name = 'collection_element_type';
    protected $values = array(
        self::UNKNOWN,
        self::BASIC,
        self::ENHANCED,
        self::ADVANCED,
    );

    public static function getValues() {
        return self::getType('collection_element_type')->values;
    }

    public static function getDefault() {
        return self::getType('collection_element_type')->default;
    }
}