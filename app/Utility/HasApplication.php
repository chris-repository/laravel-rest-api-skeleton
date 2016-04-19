<?php


namespace App\Utility;


use Illuminate\Contracts\Foundation\Application;

trait HasApplication
{
    /**
     * @var Application
     */
    private $application;
    /**
     * @return Application
     */
    public function getApplication() : Application
    {
        return $this->application;
    }

    public function setApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }

}