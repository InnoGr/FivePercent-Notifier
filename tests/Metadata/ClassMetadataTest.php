<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Metadata;

/**
 * Class metadata testing
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get notifications
     */
    public function testGetNotifications()
    {
        $notifications = $this->createNotifications();
        $metadata = new ClassMetadata($notifications);

        $this->assertEquals($notifications, $metadata->getNotifications());
    }

    /**
     * Test get notification for event
     */
    public function testGetNotificationForEvent()
    {
        $notifications = $this->createNotifications();
        $metadata = new ClassMetadata($notifications);

        $this->assertEquals($notifications[1], $metadata->getNotificationForEvent('event_name'));
    }

    /**
     * Test test notification for event and not exist
     */
    public function testGetNotificationForEventAndNotExist()
    {
        $notifications = $this->createNotifications();
        $metadata = new ClassMetadata($notifications);

        $this->assertNull($metadata->getNotificationForEvent('foo_bar'));
    }

    /**
     * Create notifications
     *
     * @return array|NotificationMetadata[]
     */
    private function createNotifications()
    {
        $notifications = [];

        array_push(
            $notifications,
            new NotificationMetadata('foo', 'immediately', null),
            new NotificationMetadata('bar', 'immediately', 'event_name')
        );

        return $notifications;
    }
}
