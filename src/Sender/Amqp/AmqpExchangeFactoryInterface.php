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

/**
 * All amqp exchange factories should implement this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface AmqpExchangeFactoryInterface
{
    /**
     * Create exchange for send notification
     *
     * @return \AMQPExchange
     */
    public function createExchange();
}
