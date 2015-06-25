<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Receiver\Processor;

/**
 * All receive processors should implement this interface.
 * Process notification by name and object
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ProcessorInterface
{
    /**
     * Process notification
     *
     * @param string $name
     * @param object $object
     */
    public function process($name, $object);
}
