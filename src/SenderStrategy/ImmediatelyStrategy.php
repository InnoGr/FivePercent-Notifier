<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\SenderStrategy;

use FivePercent\Component\Notifier\Notification;
use FivePercent\Component\Notifier\Sender\SenderInterface;

/**
 * Send immediately notification
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ImmediatelyStrategy implements SenderStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function sendNotification(SenderInterface $sender, Notification $notification)
    {
        $sender->send($notification);
    }
}
