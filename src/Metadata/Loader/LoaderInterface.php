<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Metadata\Loader;

/**
 * All metadata loaders should be implemented of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface LoaderInterface
{
    /**
     * Is class supported
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class);

    /**
     * Load metadata
     *
     * @param string $class
     *
     * @return \FivePercent\Component\Notifier\Metadata\ClassMetadata
     */
    public function loadMetadata($class);
}
