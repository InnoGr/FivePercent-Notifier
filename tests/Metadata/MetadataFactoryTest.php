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
 * Testing metadata factory
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class MetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FivePercent\Component\Notifier\Metadata\Loader\LoaderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loader;

    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->loader = $this->getMockForAbstractClass('FivePercent\Component\Notifier\Metadata\Loader\LoaderInterface');
        $this->metadataFactory = new MetadataFactory($this->loader);
    }

    /**
     * Test supports class
     */
    public function testSupportsClass()
    {
        $object = new \stdClass();

        $this->loader->expects($this->any())
            ->method('supportsClass')
            ->with($this->logicalOr(
                $this->equalTo('stdClass'),
                $this->equalTo('MyEvent')
            ))
            ->will($this->returnCallback(function ($class) {
                return $class == 'MyEvent' ? true : false;
            }));

        $this->assertFalse($this->metadataFactory->supportsClass($object));
        $this->assertTrue($this->metadataFactory->supportsClass('MyEvent'));
    }

    /**
     * Test load metadata
     */
    public function testLoadMetadata()
    {
        $object = new \stdClass();
        $metadata = $this->getMock('FivePercent\Component\Notifier\Metadata\NotificationMetadata', [], [], '', false);

        $this->loader->expects($this->once())
            ->method('supportsClass')
            ->with('stdClass')
            ->will($this->returnValue(true));

        $this->loader->expects($this->once())
            ->method('loadMetadata')
            ->with('stdClass')
            ->will($this->returnValue($metadata));

        $this->assertEquals($metadata, $this->metadataFactory->loadMetadata($object));
    }

    /**
     * Test load metadata and object not supported
     *
     * @expectedException \FivePercent\Component\Notifier\Exception\ClassNotSupportedException
     * @expectedExceptionMessage The class "MyEvent" not supported for load event metadata.
     */
    public function testLoadMetadataAndObjectNotSupported()
    {
        $this->loader->expects($this->once())
            ->method('supportsClass')
            ->with('MyEvent')
            ->will($this->returnValue(false));

        $this->loader->expects($this->never())
            ->method('loadMetadata');

        $this->metadataFactory->loadMetadata('MyEvent');
    }
}
