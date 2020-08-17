<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Container\NotFoundException;
use Xynha\Tests\Data\Invalid\AbstractClass;
use Xynha\Tests\Data\Invalid\PrivateClass;
use Xynha\Tests\Data\Invalid\ProtectedClass;
use Xynha\Tests\Data\Invalid\TraitTest;
use Xynha\Tests\Units\AbstractTestCase;

final class FailedTest extends AbstractTestCase
{

    public function testInterface()
    {
        $msg = sprintf('Class or rule `%s` is not found or it is an interface', 'ArrayAccess');
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(ArrayAccess::class);
    }

    public function testTrait()
    {
        $msg = sprintf('Class or rule `%s` is not found or it is an interface', TraitTest::class);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(TraitTest::class);
    }

    public function testAbstractClass()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Cannot instantiate abstract class ' . AbstractClass::class);

        $this->dic->get(AbstractClass::class);
    }

    public function testPrivateConstructor()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . PrivateClass::class);

        $this->dic->get(PrivateClass::class);
    }

    public function testProtectedConstructor()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . ProtectedClass::class);

        $this->dic->get(ProtectedClass::class);
    }
}
