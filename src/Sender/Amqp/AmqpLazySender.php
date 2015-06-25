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
 * Amqp lazy sender. Create exchange only on send notification
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class AmqpLazySender implements SenderInterface
{
    /**
     * @var AmqpExchangeFactoryInterface
     */
    private $exchangeFactory;

    /**
     * @var \AMQPExchange
     */
    private $exchange;

    /**
     * Construct
     *
     * @param AmqpExchangeFactoryInterface $exchangeFactory
     */
    public function __construct(AmqpExchangeFactoryInterface $exchangeFactory)
    {
        $this->exchangeFactory = $exchangeFactory;
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

        $this->getExchange()->publish(json_encode($entry), $routingKey);
    }

    /**
     * Get exchange
     *
     * @return \AMQPExchange
     */
    protected function getExchange()
    {
        if (!$this->exchange) {
            $exchange = $this->exchangeFactory->createExchange();

            if (!$exchange) {
                throw new \RuntimeException(
                    'The exchange factory should return \AMQPExchange, but NULL returned.'
                );
            }

            if (!$exchange instanceof \AMQPExchange) {
                throw new \RuntimeException(sprintf(
                    'The exchange factory should return \AMQPExchange, but %s returned.',
                    is_object($exchange) ? get_class($exchange) : gettype($exchange)
                ));
            }

            $this->exchange = $exchange;
        }

        return $this->exchange;
    }
}
