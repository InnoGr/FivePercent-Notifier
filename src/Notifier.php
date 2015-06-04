<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier;

use FivePercent\Component\Exception\UnexpectedTypeException;
use FivePercent\Component\Notifier\Exception\ClassNotSupportedException;
use FivePercent\Component\Notifier\Metadata\ClassMetadata;
use FivePercent\Component\Notifier\Metadata\MetadataFactoryInterface;
use FivePercent\Component\Notifier\Metadata\NotificationMetadata;
use FivePercent\Component\Notifier\ObjectData\ObjectDataExtractorInterface;
use FivePercent\Component\Notifier\Sender\SenderInterface;
use FivePercent\Component\Notifier\SenderStrategy\SenderStrategyManagerInterface;

/**
 * Base notifier system
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * @todo: correct control event names for send notification
 */
class Notifier implements NotifierInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var SenderStrategyManagerInterface
     */
    private $senderStrategyManager;

    /**
     * @var SenderInterface
     */
    private $sender;

    /**
     * @var ObjectDataExtractorInterface
     */
    private $objectDataExtractor;

    /**
     * Construct
     *
     * @param MetadataFactoryInterface       $metadataFactory
     * @param SenderStrategyManagerInterface $senderStrategyManager
     * @param SenderInterface                $sender
     * @param ObjectDataExtractorInterface   $objectDataExtractor
     */
    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        SenderStrategyManagerInterface $senderStrategyManager,
        SenderInterface $sender,
        ObjectDataExtractorInterface $objectDataExtractor
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->senderStrategyManager = $senderStrategyManager;
        $this->sender = $sender;
        $this->objectDataExtractor = $objectDataExtractor;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsObject($object, $onEvent = null)
    {
        if (!is_object($object)) {
            throw UnexpectedTypeException::create($object, 'object');
        }

        $class = get_class($object);

        if (!$this->metadataFactory->supportsClass($class)) {
            return false;
        }

        if (!$onEvent) {
            return true;
        }

        $metadata = $this->metadataFactory->loadMetadata($class);

        if (count($metadata->getNotifications()) == 1) {
            return true;
        }

        $notificationMetadata = $metadata->getNotificationForEvent($onEvent);

        return (bool) $notificationMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function notify($object, $onEvent = null)
    {
        if (!is_object($object)) {
            throw UnexpectedTypeException::create($object, 'object');
        }

        $notifyClass = get_class($object);

        if (!$this->supportsObject($object)) {
            throw new ClassNotSupportedException(sprintf(
                'The object instance "%s" not supports send notification with event "%s".',
                $notifyClass,
                $onEvent ? $onEvent : 'NULL'
            ));
        }

        $classMetadata = $this->metadataFactory->loadMetadata($notifyClass);
        $metadata = $this->getNotificationMetadataForEvent($classMetadata, $onEvent, $notifyClass);

        $notification = $this->createNotification($metadata, $object);

        $this->senderStrategyManager->getStrategy($metadata->getStrategy())
            ->sendNotification($this->sender, $notification);
    }

    /**
     * Get notification metadata for event
     *
     * @param ClassMetadata $classMetadata
     * @param string        $onEvent
     * @param string        $notifyClass
     *
     * @return NotificationMetadata
     *
     * @throws \LogicException
     */
    private function getNotificationMetadataForEvent(ClassMetadata $classMetadata, $onEvent, $notifyClass)
    {
        if ($onEvent) {
            $metadata = $classMetadata->getNotificationForEvent($onEvent);

            if (!$metadata) {
                $metadataNotifications = $classMetadata->getNotifications();

                if (count($metadataNotifications) == 1) {
                    $metadata = array_pop($metadataNotifications);
                } else {
                    throw new \LogicException(sprintf(
                        'Can not get notification metadata for class "%s" and event "%s".',
                        $notifyClass,
                        $onEvent
                    ));
                }
            }
        } else {
            $metadataNotifications = $classMetadata->getNotifications();

            if (count($metadataNotifications) > 1) {
                throw new \LogicException(sprintf(
                    'The class "%s" have many notification, and you must specify event name for send notification.',
                    $notifyClass
                ));
            }

            $metadata = array_pop($metadataNotifications);
        }

        return $metadata;
    }

    /**
     * Create notification
     *
     * @param NotificationMetadata $metadata
     * @param object               $object
     *
     * @return Notification
     */
    protected function createNotification(NotificationMetadata $metadata, $object)
    {
        $data = $this->objectDataExtractor->extract($object);

        return new Notification($metadata->getName(), $data);
    }
}
