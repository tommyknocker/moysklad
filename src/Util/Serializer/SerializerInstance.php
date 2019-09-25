<?php

namespace MoySklad\Util\Serializer;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class SerializerInstance
{
    public const JSON_FORMAT = 'json';

    private const DIRECTION = [
        'serialization' => 1,
        'deserialization' => 2,
    ];

    /**
     * @var Serializer
     */
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): Serializer
    {
        if (is_null(self::$instance)) {
            self::$instance = SerializerBuilder::create()->
            setPropertyNamingStrategy(
                new SerializedNameAnnotationStrategy(
                    new IdenticalPropertyNamingStrategy()
                )
            )->
            configureHandlers(function (HandlerRegistry $registry) {
                $registry->registerHandler(self::DIRECTION['deserialization'], 'MoySklad\Entity\MetaEntity', 'json', new MetaEntityDeserializeHandler());
                $registry->registerHandler(self::DIRECTION['deserialization'], 'MoySklad\Entity\Barcode', 'json', new BarcodeDeserializeHandler());
            })->
            addDefaultHandlers()->
            build();
        }

        return self::$instance;
    }
}
