<?php declare(strict_types=1);

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Xynha\Tests\AbstractTestCase;

final class FailedTest extends AbstractTestCase
{

    public function testCreateNotExistClass()
    {
        $msg = sprintf('Class or rule `%s` is not found or it is an interface', 'NotExistClass');
        $this->expectException(NotFoundExceptionInterface::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get('NotExistClass');
    }

    public function testInterface()
    {
        $msg = sprintf('Class or rule `%s` is not found or it is an interface', 'ArrayAccess');
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get('ArrayAccess');
    }

    public function testAbstractClass()
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage('Cannot instantiate abstract class AbstractFailed');

        $this->dic->get('AbstractFailed');
    }

    public function testPrivateConstructor()
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage('Access to non-public constructor of class PrivateFailed');

        $this->dic->get('PrivateFailed');
    }

    public function testProtectedConstructor()
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ProtectedFailed');

        $this->dic->get('ProtectedFailed');
    }
}
