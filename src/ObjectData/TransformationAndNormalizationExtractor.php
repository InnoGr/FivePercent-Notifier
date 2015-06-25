<?php

/*
 * This file is part of the Notifier package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Notifier\ObjectData;

use FivePercent\Component\ModelNormalizer\ModelNormalizerManagerInterface;
use FivePercent\Component\ModelTransformer\ModelTransformerManagerInterface;
use FivePercent\Component\Notifier\Exception\ExtractorNotSupportedException;

/**
 * Try transform and normalize object for extract data
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class TransformationAndNormalizationExtractor implements ObjectDataExtractorInterface
{
    /**
     * @var ModelTransformerManagerInterface
     */
    private $transformerManager;

    /**
     * @var ModelNormalizerManagerInterface
     */
    private $normalizerManager;

    /**
     * Construct
     *
     * @param ModelTransformerManagerInterface $transformerManager
     * @param ModelNormalizerManagerInterface  $normalizerManager
     */
    public function __construct(
        ModelTransformerManagerInterface $transformerManager,
        ModelNormalizerManagerInterface $normalizerManager
    ) {
        $this->normalizerManager = $normalizerManager;
        $this->transformerManager = $transformerManager;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($object)
    {
        if (!$this->transformerManager->supports($object)) {
            throw new ExtractorNotSupportedException(sprintf(
                'Can not extract data from object via class "%s" (Transformer).',
                get_class($object)
            ));
        }

        $transformedObject = $this->transformerManager->transform($object);

        if (!$this->normalizerManager->supports($transformedObject)) {
            throw new ExtractorNotSupportedException(sprintf(
                'Can not extract data from object via class "%s" (Normalizer).',
                get_class($transformedObject)
            ));
        }

        $normalizedData = $this->normalizerManager->normalize($transformedObject);

        return $normalizedData;
    }
}
