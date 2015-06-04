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

use FivePercent\Component\Notifier\Exception\StrategyNotFoundException;

/**
 * Base sender strategy manager
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class StrategyManager implements SenderStrategyManagerInterface
{
    /**
     * @var array|SenderStrategyInterface[]
     */
    private $strategies = [];

    /**
     * Add strategy
     *
     * @param string                  $key
     * @param SenderStrategyInterface $strategy
     *
     * @return StrategyManager
     */
    public function addStrategy($key, SenderStrategyInterface $strategy)
    {
        $this->strategies[$key] = $strategy;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getStrategy($key)
    {
        if (!isset($this->strategies[$key])) {
            throw new StrategyNotFoundException(sprintf(
                'Not found sender strategy with key "%s".',
                $key
            ));
        }

        return $this->strategies[$key];
    }
}
