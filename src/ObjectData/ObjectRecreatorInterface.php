<?php

/**
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\ObjectData;

/**
 * All object recreators should implement this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ObjectRecreatorInterface
{
    /**
     * Recreate object with class name and message data
     *
     * @param string $className
     * @param mixed  $data
     *
     * @return object
     *
     * @throws \FivePercent\Component\Notifier\Exception\ObjectRecreateException
     */
    public function recreate($className, $data);
}
