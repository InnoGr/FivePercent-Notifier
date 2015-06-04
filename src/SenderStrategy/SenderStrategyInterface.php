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

use FivePercent\Component\Notifier\Notification;
use FivePercent\Component\Notifier\Sender\SenderInterface;

/**
 * All strategies for send notification should be implement of this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface SenderStrategyInterface
{
    /**
     * Send notification
     *
     * @param SenderInterface $sender
     * @param Notification    $notification
     */
    public function sendNotification(SenderInterface $sender, Notification $notification);
}
