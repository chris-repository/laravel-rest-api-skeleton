<?php


namespace App\Utility;


use Illuminate\Contracts\Foundation\Application;

interface ApplicationAwareInterface
{
    /**
     * @return Application
     */
    public function getApplication();

    /**
     * @param Application $application
     * @return $this
     */
    public function setApplication(Application $application);
}