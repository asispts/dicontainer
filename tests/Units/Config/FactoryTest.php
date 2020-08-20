<?php declare(strict_types=1);

use Xynha\Tests\Data\ClassGenerator;
use Xynha\Tests\Data\ClassMapper;
use Xynha\Tests\Data\ComplexMapperDep;
use Xynha\Tests\Data\FactoryInterfaceDep;
use Xynha\Tests\Data\FactoryInterfaceImpl;
use Xynha\Tests\Data\GeneratorDep;
use Xynha\Tests\Data\MapperDep;
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

    public function testReturnClass()
    {
        $mapper = $this->dic->get(MapperDep::class);

        $this->assertInstanceOf(MapperDep::class, $mapper);
    }

    public function testSharedFactory()
    {
        $mapper = $this->dic->get(MapperDep::class);
        $generator = $this->dic->get(GeneratorDep::class);

        $this->assertSame($mapper->mapper->map, $generator->gen->map);
    }
}
