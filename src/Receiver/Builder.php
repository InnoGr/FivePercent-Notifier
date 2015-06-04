<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Receiver;

use FivePercent\Component\Notifier\Exception\ClassNotSupportedException;
use FivePercent\Component\Notifier\Metadata\MetadataFactory;
use FivePercent\Component\Notifier\Metadata\MetadataFactoryInterface;
use FivePercent\Component\Notifier\ObjectData\ObjectRecreatorInterface;
use FivePercent\Component\Notifier\Receiver\Adapter\ReceiverAdapterInterface;
use FivePercent\Component\Notifier\Receiver\Processor\CallableProcessor;
use FivePercent\Component\Notifier\Receiver\Processor\ProcessorInterface;

/**
 * Build receiver
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Builder
{
    /**
     * @var array
     */
    private $listens = [];

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
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * Construct
     *
     * @param MetadataFactoryInterface $metadataFactory
     */
    public function __construct(MetadataFactoryInterface $metadataFactory = null)
    {
        $this->metadataFactory = $metadataFactory ?: MetadataFactory::createDefault();
    }

    /**
     * Add class listens
     *
     * @param string|array $classes
     * @param string       $onEvent
     *
     * @return Builder
     *
     * @throws ClassNotSupportedException
     */
    public function listen($classes, $onEvent = null)
    {
        if (!is_array($classes)) {
            $classes = [$classes];
        }

        foreach ($classes as $class) {
            if (!$this->metadataFactory->supportsClass($class)) {
                throw new ClassNotSupportedException(sprintf(
                    'The class "%s" not supports for notification system.',
                    $class
                ));
            }

            $metadata = $this->metadataFactory->loadMetadata($class);

            if ($onEvent) {
                $notifications = [$metadata->getNotificationForEvent($onEvent)];
            } else {
                $notifications = $metadata->getNotifications();
            }

            foreach ($notifications as $notification) {
                $this->listens[$notification->getName()] = $class;
            }
        }

        return $this;
    }

    /**
     * Add adapter for receive
     *
     * @param ReceiverAdapterInterface $adapter
     *
     * @return Builder
     */
    public function setAdapter(ReceiverAdapterInterface $adapter)
    {
        $this->receiverAdapter = $adapter;

        return $this;
    }

    /**
     * Add processor for receiver
     *
     * @param ProcessorInterface|\Closure $processor
     *
     * @return Builder
     */
    public function process($processor)
    {
        if ($processor instanceof ProcessorInterface) {
            $this->processor = $processor;
        } else if ($processor instanceof \Closure) {
            $this->processor = new CallableProcessor($processor);
        } else {
            throw new \InvalidArgumentException(sprintf(
                'The first argument should be ProcessorInterface or \Closure instance, but "%s" given.',
                is_object($processor) ? get_class($processor) : gettype($processor)
            ));
        }

        return $this;
    }

    /**
     * Sets object recreator
     *
     * @param ObjectRecreatorInterface $recreator
     *
     * @return Builder
     */
    public function setRecreator(ObjectRecreatorInterface $recreator)
    {
        $this->objectRecreator = $recreator;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return Receiver
     */
    public function getReceiver()
    {
        if (!$this->processor) {
            throw new \RuntimeException('Undefined processor. Please set processor (::processor).');
        }

        if (!$this->receiverAdapter) {
            throw new \RuntimeException('Undefined adapter for receive notification. Please set adapter (::adapter).');
        }

        if (!$this->objectRecreator) {
            throw new \RuntimeException('Undefined object recreator. Please set recreator (::recreator).');
        }

        $listenCollection = new ListenCollection($this->listens);

        $receiver = new Receiver(
            $this->metadataFactory,
            $this->receiverAdapter,
            $this->objectRecreator,
            $this->processor,
            $listenCollection
        );

        return $receiver;
    }
}
