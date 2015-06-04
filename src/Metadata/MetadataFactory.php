<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Metadata;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use FivePercent\Component\Notifier\Exception\ClassNotSupportedException;
use FivePercent\Component\Notifier\Metadata\Loader\AnnotationLoader;
use FivePercent\Component\Notifier\Metadata\Loader\LoaderInterface;

/**
 * Base metadata factory
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class MetadataFactory implements MetadataFactoryInterface
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * Construct
     *
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        return $this->loader->supportsClass($class);
    }

    /**
     * {@inheritDoc}
     */
    public function loadMetadata($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        if (!$this->supportsClass($class)) {
            throw new ClassNotSupportedException(sprintf(
                'The class "%s" not supported for load event metadata.',
                $class
            ));
        }

        return $this->loader->loadMetadata($class);
    }

    /**
     * Create default metadata factory. Use annotation reader
     *
     * @return MetadataFactory
     */
    public static function createDefault()
    {
        $annotationReader = new AnnotationReader();
        // @todo: use array cache system for caching metadata (Annotation or Metadata?)
        $loader = new AnnotationLoader($annotationReader);

        $factory = new static($loader);

        return $factory;
    }
}
