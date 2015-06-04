<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Metadata\Loader;

use Doctrine\Common\Annotations\Reader;
use FivePercent\Component\Reflection\Reflection;
use FivePercent\Component\Notifier\Annotation\Notification as NotificationAnnotation;
use FivePercent\Component\Notifier\Exception\NotificationAnnotationNotFoundException;
use FivePercent\Component\Notifier\Metadata\ClassMetadata;
use FivePercent\Component\Notifier\Metadata\NotificationMetadata;

/**
 * Annotation loader
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class AnnotationLoader implements LoaderInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Construct
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        try {
            $this->loadMetadata($class);

            return true;
        } catch (NotificationAnnotationNotFoundException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function loadMetadata($class)
    {
        $notifications = [];

        $classAnnotations = Reflection::loadClassAnnotations($this->reader, $class);
        $withoutOnEventParameter = false;

        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof NotificationAnnotation) {
                if (!$classAnnotation->onEvent) {
                    $withoutOnEventParameter = true;
                }

                $notification = new NotificationMetadata(
                    $classAnnotation->name,
                    $classAnnotation->strategy,
                    $classAnnotation->onEvent
                );

                $notifications[] = $notification;
            }
        }

        if (count($notifications) > 1 && $withoutOnEventParameter) {
            throw new \RuntimeException(sprintf(
                'The parameter "onEvent" is required, if you use many @Notification annotation in class "%s".',
                $class
            ));
        }

        if (count($notifications) == 0) {
            throw new NotificationAnnotationNotFoundException(sprintf(
                'Not found @Notification annotation in class "%s".',
                $class
            ));
        }

        return new ClassMetadata($notifications);
    }
}
