<?php declare(strict_types=1);

use Xynha\Tests\Data\FactoryInterfaceDep;
use Xynha\Tests\Data\FactoryInterfaceImpl;
use Xynha\Tests\Units\Config\AbstractConfigTest;

final class FactoryTest extends AbstractConfigTest
{

    public function testReturnInterface()
    {
        $obj = $this->dic->get(FactoryInterfaceDep::class);
        $this->assertInstanceOf(FactoryInterfaceImpl::class, $obj->obj);
        $this->assertStringStartsWith('factory_', $obj->obj->generated);
        $this->assertSame('passed value from config', $obj->obj->passed);
    }
}