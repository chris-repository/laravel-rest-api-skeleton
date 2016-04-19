<?php
declare(strict_types = 1);

namespace App\Collections;


use Doctrine\Common\Inflector\Inflector;

class FilterMethodNormalizer implements MethodNameConverter
{
    const METHOD_NAMING_PATTERN = 'process%sFilter';

    public function normalize(string $filter)
    {
        return sprintf(self::METHOD_NAMING_PATTERN, ucfirst(Inflector::camelize($filter)));
    }

    /**
     * @param string $name
     * @return string | null
     */
    public function denormalize(string $name)
    {

        list($filter) = sscanf($name, self::METHOD_NAMING_PATTERN);
        //hack to remove "Filter"
        if(!$filter) {
            return null;
        }
        $filter = substr($filter, 0, -6);
        return $this->toUnderscore($filter);
    }

    public function toUnderscore(string $string)
    {
        return strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", $string));
    }
}