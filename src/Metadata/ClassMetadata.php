<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Metadata;

/**
 * Class metadata
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ClassMetadata
{
    /**
     * @var NotificationMetadata
     */
    private $notifications = [];

    /**
     * Construct
     *
     * @param array|NotificationMetadata[] $notifications
     */
    public function __construct(array $notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * Get notifications
     *
     * @return array|NotificationMetadata[]
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Get notification for event
     *
     * @param string $event
     *
     * @return NotificationMetadata|null
     */
    public function getNotificationForEvent($event)
    {
        foreach ($this->notifications as $notification) {
            if ($notification->getOnEvent() == $event) {
                return $notification;
            }
        }

        return null;
    }
}
