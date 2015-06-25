<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\ObjectData;

/**
 * All object data extractors should be implemented of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ObjectDataExtractorInterface
{
    /**
     * Extract data from object for notification
     *
     * @param object $object
     *
     * @return array
     *
     * @throws \FivePercent\Component\Notifier\Exception\ExtractorNotSupportedException
     */
    public function extract($object);
}
