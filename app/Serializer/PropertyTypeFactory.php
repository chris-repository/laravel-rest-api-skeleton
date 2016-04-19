<?php
declare(strict_types = 1);

namespace App\Serializer;


use Doctrine\ORM\Mapping\Annotation;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

class PropertyTypeFactory implements PropertyTypeFactoryInterface
{

    private $singleEntityRelations = [
        OneToOne::class,
        ManyToOne::class,
    ];

    private $arrayEntityRelations = [
        OneToMany::class,
        ManyToMany::class,
    ];

    private $columnRelations = [
        Column::class
    ];

    /**
     * Map a property type to applicable doctrine relations
     *
     * @return array
     */
    public function getRelationTypes()
    {
        return [
            PropertyType::TYPE_SINGLE_ENTITY => $this->singleEntityRelations,
            PropertyType::TYPE_ARRAY_ENTITY => $this->arrayEntityRelations,
            PropertyType::TYPE_COLUMN => $this->columnRelations
        ];
    }

    /**
     * Check if a given annotation is of a specific type
     *
     * @param $annotation
     * @param $relations
     * @return bool
     */
    private function checkType($annotation, $relations)
    {
        foreach ($relations as $relation) {
            if ($annotation instanceof $relation) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the property type given the properties annotations
     *
     * @param array $propertyAnnotations
     * @return PropertyTypeInterface
     */
    public function getPropertyType(array $propertyAnnotations) : PropertyTypeInterface
    {
        foreach($propertyAnnotations as $annotation) {
            foreach($this->getRelationTypes() as $propertyType => $relations) {
                if($this->checkType($annotation, $relations)) {
                    return new PropertyType($annotation, $propertyType);
                }
            }
        }
        return new PropertyType((new class implements Annotation {}), PropertyType::TYPE_COLUMN);
    }
}