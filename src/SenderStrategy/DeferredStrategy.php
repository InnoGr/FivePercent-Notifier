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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Deferred strategy. Send notification on terminate symfony event
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class DeferredStrategy implements SenderStrategyInterface
{
    /**
     * @var array
     */
    private $notifications = [];

    /**
     * {@inheritDoc}
     */
    public function sendNotification(SenderInterface $sender, Notification $notification)
    {
        $hash = spl_object_hash($notification);

        $this->notifications[$hash] = [
            'notification' => $notification,
            'sender' => $sender
        ];
    }

    /**
     * Flush storage
     */
    public function flush()
    {
        foreach ($this->notifications as $entry) {
            /** @var SenderInterface $sender */
            $sender = $entry['sender'];
            /** @var Notification $notification */
            $notification = $entry['notification'];

            $sender->send($notification);
        }

        // Clear notifications
        $this->notifications = [];
    }
}
