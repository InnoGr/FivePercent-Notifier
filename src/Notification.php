<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier;

/**
 * Base event model
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Notification
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Construct
     *
     * @param string $name
     * @param array  $data
     */
    public function __construct($name = null, array $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Notification
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return Notification
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
