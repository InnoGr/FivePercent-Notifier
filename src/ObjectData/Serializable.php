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

use FivePercent\Component\Exception\UnexpectedTypeException;
use FivePercent\Component\Notifier\Exception\ObjectRecreateException;

/**
 * Try serialize object for extract data and recreate object
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class Serializable implements ObjectDataExtractorInterface, ObjectRecreatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function extract($object)
    {
        return [
            'serialized' => serialize($object)
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function recreate($className, $data)
    {
        if (!is_array($data)) {
            throw UnexpectedTypeException::create($data, 'array');
        }

        if (empty($data['serialized'])) {
            throw new ObjectRecreateException('Missing requires parameter "serialized".');
        }

        $object = unserialize($data['serialized']);

        return $object;
    }
}
