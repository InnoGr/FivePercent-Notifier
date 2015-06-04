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

/**
 * All metadata factories should be implemented of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface MetadataFactoryInterface
{
    /**
     * Is object supported
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class);

    /**
     * Load metadata for object
     *
     * @param string $class
     *
     * @return ClassMetadata
     */
    public function loadMetadata($class);
}
