<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Receiver;

use FivePercent\Component\Exception\JsonParseException;
use FivePercent\Component\Notifier\Exception\MissingMessagePropertyException;
use FivePercent\Component\Notifier\Exception\UndefinedClassForReceiveException;
use FivePercent\Component\Notifier\Metadata\MetadataFactoryInterface;
use FivePercent\Component\Notifier\ObjectData\ObjectRecreatorInterface;
use FivePercent\Component\Notifier\Receiver\Adapter\ReceiverAdapterInterface;
use FivePercent\Component\Notifier\Receiver\Processor\ProcessorInterface;

/**
 * Base receiver system
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Receiver implements ReceiverInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var ReceiverAdapterInterface
     */
    private $receiverAdapter;

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @var ObjectRecreatorInterface
     */
    private $objectRecreator;

    /**
     * @var ListenCollectionInterface
     */
    private $listens;

    /**
     * Construct
     *
     * @param MetadataFactoryInterface  $metadataFactory
     * @param ReceiverAdapterInterface  $receiver
     * @param ObjectRecreatorInterface  $objectRecreator
     * @param ProcessorInterface        $processor
     * @param ListenCollectionInterface $listens
     */
    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        ReceiverAdapterInterface $receiver,
        ObjectRecreatorInterface $objectRecreator,
        ProcessorInterface $processor,
        ListenCollectionInterface $listens
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->receiverAdapter = $receiver;
        $this->objectRecreator = $objectRecreator;
        $this->processor = $processor;
        $this->listens = $listens;
    }

    /**
     * {@inheritDoc}
     */
    public function listen()
    {
        $this->receiverAdapter->receive($this->listens->getEventNames(), function ($message) {
            // @todo: control exceptions?
            return $this->doListen($message);
        });
    }

    /**
     * Process for listen message
     *
     * @param mixed $message
     *
     * @return bool
     *
     * @throws JsonParseException
     * @throws MissingMessagePropertyException
     * @throws UndefinedClassForReceiveException
     */
    protected function doListen($message)
    {
        // Try decode message (JSON)
        $message = @json_decode($message, true);

        if (null === $message || false === $message) {
            // Decode fail
            throw JsonParseException::create(json_last_error());
        }

        if (empty($message['name'])) {
            // Missing requires parameter
            throw new MissingMessagePropertyException('Missing requires parameter "name" in message.');
        }

        if (!isset($message['data'])) {
            // Missing requires parameter
            throw new MissingMessagePropertyException('Missing requires parameter "data" in message.');
        }

        $eventName = $message['name'];

        // Try get class for event
        $class = $this->listens->getClassNameForEvent($eventName);

        if (!$class) {
            throw new UndefinedClassForReceiveException(sprintf(
                'Undefined class for receive notification by name "%s".',
                $eventName
            ));
        }

        // Try recreate object from class
        $object = $this->objectRecreator->recreate($class, $message['data']);

        return $this->processor->process($eventName, $object);
    }
}
