<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\Receiver\Adapter;

/**
 * AMQP Adapter for receive events
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class AmqpAdapter implements ReceiverAdapterInterface
{
    /**
     * @var \AMQPQueue
     */
    private $queue;

    /**
     * @var \AMQPExchange
     */
    private $exchange;

    /**
     * @var int
     */
    private $consumeFlag;

    /**
     * Construct
     *
     * @param \AMQPExchange $exchange
     * @param \AMQPQueue    $queue
     * @param int           $consumeFlags AMQP_NOPARAM as default
     */
    public function __construct(\AMQPExchange $exchange, \AMQPQueue $queue, $consumeFlags = 0)
    {
        $this->exchange = $exchange;
        $this->queue = $queue;
    }

    /**
     * {@inheritDoc}
     */
    public function receive(array $eventNames, \Closure $callback)
    {
        $exchangeName = $this->exchange->getName();

        foreach ($eventNames as $eventName) {
            $this->queue->bind($exchangeName, $eventName);
        }

        $this->queue->consume(function (\AMQPEnvelope $envelope, \AMQPQueue $queue) use ($callback) {
            $message = $envelope->getBody();
            $messageTag = $envelope->getDeliveryTag();

            $status = call_user_func($callback, $message);

            if ($status) {
                $queue->ack($messageTag);
            } else {
                $queue->nack($messageTag);
            }
        });
    }
}
