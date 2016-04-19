<?php
declare(strict_types = 1);

namespace App\Serializer;


class DeserializeConfiguration
{
    const DEFAULT_RECURSION_DEPTH = 3;

    /**
     * @var int
     */
    private $recursionDepth = self::DEFAULT_RECURSION_DEPTH;

    /**
     * @var bool
     */
    private $ignoreAdditional;

    /**
     * @return int
     */
    public function getRecursionDepth()
    {
        return $this->recursionDepth;
    }

    /**
     * @param int $recursionDepth
     * @return $this
     */
    public function setRecursionDepth($recursionDepth)
    {
        $this->recursionDepth = $recursionDepth;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIgnoreAdditional()
    {
        return $this->ignoreAdditional;
    }

    /**
     * @param boolean $ignoreAdditional
     * @return $this
     */
    public function setIgnoreAdditional($ignoreAdditional)
    {
        $this->ignoreAdditional = $ignoreAdditional;
        return $this;
    }

}