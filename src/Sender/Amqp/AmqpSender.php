<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Sender\Amqp;

use FivePercent\Component\Notifier\Notification;
use FivePercent\Component\Notifier\Sender\SenderInterface;

/**
 * Amqp sender
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class AmqpSender implements SenderInterface
{
    /**
     * @var \AMQPExchange
     */
    private $exchange;

    /**
     * Construct
     *
     * @param \AMQPExchange $exchange
     */
    public function __construct(\AMQPExchange $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * {@inheritDoc}
     */
    public function send(Notification $event)
    {
        $routingKey = $event->getName();

        $entry = [
            'name' => $event->getName(),
            'data' => $event->getData()
        ];

        $this->exchange->publish(json_encode($entry), $routingKey);
    }
}
