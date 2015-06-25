<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Tested subscriber for tests add subscriber to notifier proxy
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class SubscriberTested implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [];
    }
}
