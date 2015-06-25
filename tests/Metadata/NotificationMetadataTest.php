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
 * Testing notification metadata
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class NotificationMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Base testing
     */
    public function testBase()
    {
        $notification = new NotificationMetadata('foo', 'immediately', 'bar');

        $this->assertEquals('foo', $notification->getName());
        $this->assertEquals('immediately', $notification->getStrategy());
        $this->assertEquals('bar', $notification->getOnEvent());
    }
}
