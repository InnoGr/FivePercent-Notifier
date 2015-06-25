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

/**
 * Testing notifier proxy
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class EventDispatcherNotifierProxyText extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $eventDispatcher;

    /**
     * @var \FivePercent\Component\Notifier\NotifierInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $notifier;

    /**
     * @var EventDispatcherNotifierProxy
     */
    private $notifierProxy;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        if (!interface_exists('Symfony\Component\EventDispatcher\EventDispatcherInterface')) {
            $this->markTestSkipped('The Symfony EventDispatcher not installed.');
        }

        $this->eventDispatcher = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->notifier = $this->getMockForAbstractClass('FivePercent\Component\Notifier\NotifierInterface');
        $this->notifierProxy = new EventDispatcherNotifierProxy($this->eventDispatcher, $this->notifier, [
            'disable_event'
        ]);
    }

    /**
     * Test dispatch and notifier supports event
     */
    public function testDispatchWithNotifierSupportEvent()
    {
        $event = new EventTested();

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with('foo', $event)
            ->will($this->returnValue($event));

        $this->notifier->expects($this->once())
            ->method('supportsObject')
            ->with($event, 'foo')
            ->will($this->returnValue(true));

        $this->notifier->expects($this->once())
            ->method('notify')
            ->with($event, 'foo');

        $this->notifierProxy->dispatch('foo', $event);
    }

    /**
     * Test dispatch and notifier not supports event
     */
    public function testDispatchWithNotifierNotSupportEvent()
    {
        $event = new EventTested();

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with('foo', $event)
            ->will($this->returnValue($event));

        $this->notifier->expects($this->once())
            ->method('supportsObject')
            ->with($event, 'foo')
            ->will($this->returnValue(false));

        $this->notifier->expects($this->never())
            ->method('notify');

        $this->notifierProxy->dispatch('foo', $event);
    }

    /**
     * Test dispatch and event name in disabled list
     */
    public function testDispatchWithEventNameIsDisabledForNotify()
    {
        $event = new EventTested();

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with('disable_event', $event);

        $this->notifier->expects($this->never())
            ->method('supportsObject')
            ->with($event, 'foo');

        $this->notifier->expects($this->never())
            ->method('notify');

        $this->notifierProxy->dispatch('disable_event', $event);
    }

    /**
     * Test add listener
     */
    public function testAddListener()
    {
        $listener = function (){};

        $this->eventDispatcher->expects($this->once())
            ->method('addListener')
            ->with('foo', $listener, 2);

        $this->notifierProxy->addListener('foo', $listener, 2);
    }

    /**
     * Test remove listener
     */
    public function testRemoveListener()
    {
        $listener = function (){};

        $this->eventDispatcher->expects($this->once())
            ->method('removeListener')
            ->with('bar', $listener);

        $this->notifierProxy->removeListener('bar', $listener);
    }

    /**
     * Test add subscriber
     */
    public function testAddSubscriber()
    {
        $subscriber = new SubscriberTested();

        $this->eventDispatcher->expects($this->once())
            ->method('addSubscriber')
            ->with($subscriber);

        $this->notifierProxy->addSubscriber($subscriber);
    }

    /**
     * Test remove subscriber
     */
    public function testRemoveSubscriber()
    {
        $subscriber = new SubscriberTested();

        $this->eventDispatcher->expects($this->once())
            ->method('removeSubscriber')
            ->with($subscriber);

        $this->notifierProxy->removeSubscriber($subscriber);
    }

    /**
     * Test get listeners
     */
    public function testGetListeners()
    {
        $this->eventDispatcher->expects($this->once())
            ->method('getListeners')
            ->with(null)
            ->will($this->returnValue(['foo', 'bar']));

        $listeners = $this->notifierProxy->getListeners(null);

        $this->assertEquals(['foo', 'bar'], $listeners);
    }

    /**
     * Test has listeners
     */
    public function testHasListeners()
    {
        $this->eventDispatcher->expects($this->any())
            ->method('hasListeners')
            ->with($this->anything())
            ->will($this->returnCallback(function ($eventName){
                return $eventName == 'foo' ? true : false;
            }));

        $this->assertFalse($this->notifierProxy->hasListeners('bar'));
        $this->assertTrue($this->notifierProxy->hasListeners('foo'));
    }
}
