<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Xynha\Container\DiContainer;
use Xynha\Tests\Data\AbstractClass;
use Xynha\Tests\Data\PrivateConstructor;
use Xynha\Tests\Data\ProtectedConstructor;

final class FailedTest extends TestCase
{

    /** @var DiContainer */
    private $dic;

    protected function setUp()
    {
        $this->dic = new DiContainer();
    }

    /** @return void */
    public function testCreateNotExistClass()
    {
        $msg = sprintf('Class or rule `%s` is not found or it is an interface', 'NotExistClass');
        $this->expectException(NotFoundExceptionInterface::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get('NotExistClass');
    }

    public function testInterface()
    {
        $msg = sprintf('Class or rule `%s` is not found or it is an interface', ArrayAccess::class);
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(ArrayAccess::class);
    }

    public function testAbstractClass()
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage('Cannot instantiate abstract class ' . AbstractClass::class);

        $this->dic->get(AbstractClass::class);
    }

    public function testPrivateConstructor()
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . PrivateConstructor::class);

        $this->dic->get(PrivateConstructor::class);
    }

    public function testProtectedConstructor()
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . ProtectedConstructor::class);

        $this->dic->get(ProtectedConstructor::class);
    }
}
