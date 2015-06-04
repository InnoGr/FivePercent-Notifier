<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\SenderStrategy;

/**
 * All send strategies manager should be implemented of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface SenderStrategyManagerInterface
{
    /**
     * Get strategy
     *
     * @param string $key
     *
     * @return SenderStrategyInterface
     */
    public function getStrategy($key);
}
