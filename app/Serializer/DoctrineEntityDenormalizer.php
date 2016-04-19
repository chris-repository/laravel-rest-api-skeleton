<?php
declare(strict_types = 1);

namespace App\Serializer;


use App\Exceptions\RecursionException;
use BadMethodCallException;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Exception;
use LaravelDoctrine\ORM\Extensions\MappingDriverChain;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DoctrineEntityDenormalizer implements DenormalizerInterface, NormalizerInterface
{

    const ERROR_NAMECONVERTER_REGISTERED = 'NameConverter already registered for %s';
    const UNEXPECTED_VALUE_TYPE = 'Expected type %s for property %s';
    const UNRECOGNISED_PROPERTY = 'Could not load annotations for property: %s, on class: %s';
    const DEFAULT_RECURSION_DEPTH = 3;
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var NameConverterInterface[]
     */
    private $nameConverters;

    /**
     * @var PropertyTypeFactoryInterface
     */
    private $propertyTypeFactory;

    private $recursionDepth = self::DEFAULT_RECURSION_DEPTH;

    private $ignoreAdditional;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var  PropertyAccessorInterface */
    private $propertyAccessor;

    public function __construct(
        PropertyTypeFactoryInterface $propertyTypeFactory,
        DeserializeConfiguration $config,
        EntityManagerInterface $entityManager,
        array $nameConverters = [],
        PropertyAccessorInterface $propertyAccessor = null
    ) {
        /** @var  MappingDriverChain $mappingDriverChain */
        $mappingDriverChain = $entityManager->getConfiguration()->getMetadataDriverImpl();
        $this->reader = $mappingDriverChain->getReader();
        $this->nameConverters = $nameConverters;
        $this->propertyTypeFactory = $propertyTypeFactory;
        $this->recursionDepth = $config->getRecursionDepth();
        $this->ignoreAdditional = $config->getIgnoreAdditional();
        $this->entityManager = $entityManager;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param NameConverterInterface $nameConverter
     * @param string $class
     * @throws Exception
     */
    public function addNameConverter(NameConverterInterface $nameConverter, string $class)
    {
        if (array_key_exists($class, $this->nameConverters)) {
            throw new Exception(sprintf(self::ERROR_NAMECONVERTER_REGISTERED, $class));
        }
        $this->nameConverters[$class] = $nameConverter;
    }

    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed $data data to restore
     * @param mixed $class the expected class to instantiate
     * @param string $format format the given data was extracted from
     * @param array $context options available to the denormalizer
     * @param int $currentDepth
     * @return object
     * @throws ErrorException
     * @throws Exception
     * @throws RecursionException
     */
    public function denormalize($data, $class, $format = null, array $context = array(), $currentDepth = 0)
    {
        if (++$currentDepth >= $this->recursionDepth) {
            throw new RecursionException();
        }

        if (!is_object($class)) {
            $class = new $class;
        }

        $classMetaData = $this->entityManager->getClassMetadata(get_class($class));

        foreach ($data as $key => $value) {
            $normalizedKey = $this->getNameConverter($class)->denormalize($key);
            $normalizedKey = Inflector::camelize($normalizedKey);

            try {
                $property = $classMetaData->getReflectionProperty($normalizedKey);
            } catch (ErrorException $e) {
                if ($this->ignoreAdditional) {
                    continue;
                }
                throw $e;
            }

            $annotations = $this->reader->getPropertyAnnotations($property);

            if (empty($annotations)) {
                if ($this->ignoreAdditional) {
                    continue;
                }
                throw new Exception(sprintf(self::UNRECOGNISED_PROPERTY, $property->getName(),
                    $property->getDeclaringClass()));
            }

            $propertyType = $this->propertyTypeFactory->getPropertyType($annotations);

            switch ($propertyType->getType()) {
                case PropertyType::TYPE_ARRAY_ENTITY:
                    if (!is_array($value)) {
                        throw new Exception(sprintf(self::UNEXPECTED_VALUE_TYPE, 'array', $property->getName()));
                    }
                    $array = [];
                    foreach ($value as $values) {
                        $array[] = $this->denormalize($values, $propertyType->getAnnotation()->targetEntity, $format,
                            $context, $currentDepth);
                    }
                    $value = new ArrayCollection($array);
                    break;
                case PropertyType::TYPE_SINGLE_ENTITY:
                    /**
                     * if value is not an array, assume this is meant for the next level, pass on
                     */
                    if (!is_array($value)) {
                        $value = [
                            $key => $value
                        ];
                    }
                    $value = $this->denormalize($value, $propertyType->getAnnotation()->targetEntity, $format,
                        $context, $currentDepth);
                    break;
            }
            if ($this->propertyAccessor->isWritable($class, $normalizedKey, $value)) {
                $this->propertyAccessor->setValue($class, $normalizedKey, $value);
            }
        }
        return $class;
    }

    /**
     * Checks whether the given class is supported for denormalization by this normalizer.
     *
     * @param mixed $data Data to denormalize from.
     * @param string $type The class to which the data should be denormalized.
     * @param string $format The format being deserialized from.
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_object($type) || class_exists($type);
    }

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param object $object object to normalize
     * @param string $format format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array|string|bool|int|float|null
     */
    public function normalize($object, $format = null, array $context = array())
    {
        throw new BadMethodCallException();
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed $data Data to normalize.
     * @param string $format The format being (de-)serialized from or into.
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return false;
    }

    /**
     * @param $class
     * @return NameConverterInterface
     */
    public function getNameConverter($class) : NameConverterInterface
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        if (array_key_exists($class, $this->nameConverters)) {
            return $this->nameConverters[$class];
        }
        //return dummy nameConverter
        return (new class implements NameConverterInterface
        {

            public function normalize($propertyName)
            {
                return $propertyName;
            }

            public function denormalize($propertyName)
            {
                return $propertyName;
            }
        });
    }
}