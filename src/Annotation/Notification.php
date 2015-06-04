<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Annotation;

/**
 * Indicate event for proxy to AMQP
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Notification
{
    /** @var string @Required */
    public $name;
    /** @var string */
    public $strategy = 'immediately';
    /** @var string */
    public $onEvent;
}
