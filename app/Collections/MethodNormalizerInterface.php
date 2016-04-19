<?php
declare(strict_types = 1);

namespace App\Collections;

interface MethodNormalizerInterface
{
    /**
     * @param string $name
     * @return string | null
     */
    public function normalize(string $name);
}