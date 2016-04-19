<?php

namespace App\Transformers;

use App\Exceptions\TransformerNotFoundException;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class TransformerProvider
 */
class TransformerProvider
{

    const CONFIG_TRANSFORMER = 'transformers.%s';

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var array
     */
    private $transformers = [];

    /**
     * @var Application
     */
    private $application;

    /**
     * TransformerProvider constructor.
     * @param Repository $config
     * @param Application $application
     */
    public function __construct(Repository $config, Application $application)
    {
        $this->config = $config;
        $this->application = $application;
    }

    public function registerTransformers(array $transformers)
    {
        if(empty($transformers)) {
            return;
        }

        foreach($transformers as $alias => $transformer) {
            $transformerInstance = new $transformer;
            $this->registerTransformer($transformerInstance, $alias);
        }
    }


    /**
     * @param $transformer
     * @return TransformerInterface
     * @throws TransformerNotFoundException
     */
    public function getTransformer($transformer)
    {
        $transformer = $this->normaliseTransformerName($transformer);
        $identifier = sprintf(self::CONFIG_TRANSFORMER, $transformer);
        if(!$this->hasTransformer($transformer) && !$this->config->has($identifier)) {
            throw new TransformerNotFoundException(
                "Could not load {$identifier}.It is possible this needs to be registered in app/config/transformers.php"
            );
        }

        if($this->hasTransformer($transformer)) {
            return $this->transformers[$transformer];
        }

        $transformerClass = $this->config->get($identifier);

        $transformerInstance = $this->application->make($transformerClass);

        $this->registerTransformer($transformerInstance, $transformer);

        return $transformerInstance;
    }

    public function registerTransformer(TransformerInterface $transformer, $alias)
    {
        if(!$this->hasTransformer($alias)) {
            $this->transformers[$alias] = $transformer;
        }
    }

    public function hasTransformer($transformer)
    {
        return array_key_exists($transformer, $this->transformers);
    }

    public function normaliseTransformerName($transformer) {
        if(strpos($transformer, 'DoctrineProxies\__CG__\\') !== false) {
            $transformer = str_ireplace('DoctrineProxies\__CG__\\', '', $transformer);
        }
        return $transformer;
    }
}