<?php
declare(strict_types = 1);

namespace App\Serializer;


use Doctrine\ORM\Mapping\Annotation;

class PropertyType implements PropertyTypeInterface
{

    const TYPE_SINGLE_ENTITY = 'type_single_entity';
    const TYPE_ARRAY_ENTITY = 'type_array_entity';
    const TYPE_COLUMN = 'type_column';

    /**
     * @var Annotation
     */
    private $annotation;

    /**
     * @var string
     */
    private $type;

    /**
     * PropertyType constructor.
     * @param Annotation $annotation
     * @param string $type
     */
    public function __construct(Annotation $annotation, $type)
    {
        $this->annotation = $annotation;
        $this->type = $type;
    }


    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }
}