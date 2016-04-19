<?php


namespace App\Transformers;


use App\Serializer\Fractal\DataArraySerializer;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Hashids\Hashids;
use League\Fractal\TransformerAbstract as FractalTransformerAbstract;

abstract class TransformerAbstract extends FractalTransformerAbstract implements TransformerInterface
{
    /**
     * @var TransformerProvider
     */
    protected $transformerProvider;
    /**
     * @var Hashids
     */
    protected $idHasher;
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * TransformerAbstract constructor.
     * @param TransformerProvider $transformerProvider
     * @param Hashids $hashids
     * @param EntityManager $entityManager
     */
    public function __construct(
        TransformerProvider $transformerProvider,
        Hashids $hashids,
        EntityManager $entityManager
    ) {
        $this->transformerProvider = $transformerProvider;
        $this->idHasher = $hashids;
        $this->entityManager = $entityManager;
    }

    public function hashId(string $id) : string
    {
        return $this->idHasher->encode($id);
    }

    protected function item($data, $resourceKey = null, $transformer = null)
    {
        if(!$data) {
            return null;
        }
        if ($transformer === null) {
            /** @var \League\Fractal\TransformerAbstract $transformer */
            $transformer = $this->transformerProvider->getTransformer(get_class($data));
        }

        if ($resourceKey === null) {
            $resourceKey = DataArraySerializer::STRIPPED_RESOURCE_KEY;
        }
        return parent::item($data, $transformer, $resourceKey);
    }

    /**
     * @param Collection $data
     * @param null $resourceKey
     * @param null $transformer
     * @return \League\Fractal\Resource\Collection
     * @throws \App\Exceptions\TransformerNotFoundException
     */
    protected function collection($data, $resourceKey = null, $transformer = null)
    {
        if(!$data) {
            return null;
        }
        $dataArray = $data->toArray();
        if ($transformer === null && isset($dataArray[0])) {
            /** @var \League\Fractal\TransformerAbstract $transformer */
            $transformer = $this->transformerProvider->getTransformer(get_class($dataArray[0]));
        }

        return parent::collection($data, $transformer, $resourceKey);
    }
}