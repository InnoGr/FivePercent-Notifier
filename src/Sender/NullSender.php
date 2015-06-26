<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Sender;

use FivePercent\Component\Notifier\Notification;

/**
 * Stub of sender
 *
 * @author Vitlaiy Zhuk <zhuk2205@gmail.com>
 */
class NullSender implements SenderInterface
{
    /**
     * {@inheritDoc}
     */
    public function send(Notification $notification)
    {
        return true;
    }
}
