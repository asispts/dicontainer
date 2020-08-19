<?php declare(strict_types=1);

use Xynha\Container\NotFoundException;
use Xynha\Tests\Data\FactoryInterfaceDep;
use Xynha\Tests\Data\FactoryInterfaceImpl;
use Xynha\Tests\Data\GenericInterfaceDep;
use Xynha\Tests\Data\GenericInterfaceImpl;
use Xynha\Tests\Data\GlobalInterfaceDep;
use Xynha\Tests\Data\GlobalInterfaceImpl;
use Xynha\Tests\Data\OverriddenGlobalSubsImpl;
use Xynha\Tests\Data\SubsInterfaceDep;
use Xynha\Tests\Units\Config\AbstractConfigTest;

final class InterfaceTest extends AbstractConfigTest
{

    public function testMissingInterface()
    {
        $msg = sprintf('Class or rule %s does not exist', ArrayAccess::class);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(ArrayAccess::class);
    }

    public function testGlobalSubstitute()
    {
        $obj = $this->dic->get(GlobalInterfaceDep::class);
        $this->assertInstanceOf(GlobalInterfaceImpl::class, $obj->obj);
    }

    public function testOverriddenGlobalSubs()
    {
        $obj = $this->dic->get(SubsInterfaceDep::class);
        $this->assertInstanceOf(OverriddenGlobalSubsImpl::class, $obj->obj);
    }

    public function testGenericInterfaceRule()
    {
        $obj = $this->dic->get(GenericInterfaceDep::class);
        $this->assertInstanceOf(GenericInterfaceImpl::class, $obj->obj);
    }

    public function testFromFactory()
    {
        $obj = $this->dic->get(FactoryInterfaceDep::class);
        $this->assertInstanceOf(FactoryInterfaceImpl::class, $obj->obj);
        $this->assertStringStartsWith('factory_', $obj->obj->generated);
        $this->assertSame('passed value from config', $obj->obj->passed);
    }
}
