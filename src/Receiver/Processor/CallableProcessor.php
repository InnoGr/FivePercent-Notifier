<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Receiver\Processor;

/**
 * Callable processor.
 * Process notification with custom callback
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class CallableProcessor implements ProcessorInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * Construct
     *
     * @param callable $callback
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException(sprintf(
                'The first argument must be a callable. but "%s" given.',
                is_object($callback) ? get_class($callback) : gettype($callback)
            ));
        }

        $this->callback = $callback;
    }

    /**
     * {@inheritDoc}
     */
    public function process($name, $object)
    {
        return call_user_func($this->callback, $name, $object);
    }
}
