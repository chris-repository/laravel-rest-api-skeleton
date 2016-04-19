<?php
declare(strict_types = 1);

namespace App\Serializer;


use Exception;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Serializer;


class DoctrineDeserializer implements DenormalizerInterface, DecoderInterface, DeserializerAdapterInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var DoctrineEntityDenormalizer
     */
    private $denormalizer;

    public function __construct(DoctrineEntityDenormalizer $denormalizer, array $decoders = [])
    {
        $this->denormalizer = $denormalizer;
        $this->serializer = new Serializer([$this->denormalizer], $decoders);
    }

    /**
     * Decodes a string into PHP data.
     *
     * @param string $data Data to decode
     * @param string $format Format name
     * @param array $context options that decoders have access to.
     *
     * The format parameter specifies which format the data is in; valid values
     * depend on the specific implementation. Authors implementing this interface
     * are encouraged to document which formats they support in a non-inherited
     * phpdoc comment.
     *
     * @return mixed
     *
     * @throws UnexpectedValueException
     */
    public function decode($data, $format, array $context = array())
    {
        return $this->serializer->decode($data, $format, $context);
    }

    /**
     * Checks whether the deserializer can decode from given format.
     *
     * @param string $format format name
     *
     * @return bool
     */
    public function supportsDecoding($format)
    {
        return $this->serializer->supportsDecoding($format);
    }

    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed $data data to restore
     * @param string $class the expected class to instantiate
     * @param string $format format the given data was extracted from
     * @param array $context options available to the denormalizer
     *
     * @return object
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return $this->serializer->denormalize($data, $class, $format, $context);
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
        return $this->serializer->supportsDenormalization($data, $type, $format);
    }

    /**
     * Deserializes data into the given type.
     *
     * @param mixed $data
     * @param string $type
     * @param string $format
     * @param array $context
     *
     * @return object
     */
    public function deserialize($data, $type, $format, array $context = array())
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }

    /**
     * @param NameConverterInterface $nameConverter
     * @param $class
     * @return $this
     * @throws Exception
     */
    public function addNameConverter(NameConverterInterface $nameConverter, $class)
    {
        $this->denormalizer->addNameConverter($nameConverter, $class);
        return $this;
    }
}