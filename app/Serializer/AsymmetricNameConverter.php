<?php
declare(strict_types = 1);

namespace App\Serializer;


use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class AsymmetricNameConverter implements NameConverterInterface
{

    /**
     * @var array
     */
    private $conversionMap = [];

    /**
     * AsymmetricNameConverter constructor.
     * @param array $conversionMap
     */
    public function __construct(array $conversionMap)
    {
        $this->conversionMap = $conversionMap;
    }


    /**
     * Converts a property name to its normalized value.
     *
     * @param string $propertyName
     *
     * @return string
     */
    public function normalize($propertyName)
    {
        $inverseMap = array_keys($this->conversionMap);
        if(array_key_exists($propertyName, $inverseMap)) {
            return $inverseMap[$propertyName];
        }
        return $propertyName;
    }

    /**
     * Converts a property name to its denormalized value.
     *
     * @param string $propertyName
     *
     * @return string
     */
    public function denormalize($propertyName)
    {
        if(array_key_exists($propertyName, $this->conversionMap)) {
            return $this->conversionMap[$propertyName];
        }
        return $propertyName;
    }
}