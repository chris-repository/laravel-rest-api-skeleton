<?php
declare(strict_types = 1);

namespace App\Collections;


interface MethodDenormalizerInterface
{
    /**
     * @param string $name
     * @return string | null
     */
    public function denormalize(string $name);
}