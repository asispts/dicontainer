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
        $mapper = $this->dic->get(ClassMapper::class);
        $generator = $this->dic->get(ClassGenerator::class);

        $this->assertInstanceOf(ClassMapper::class, $mapper);
        $this->assertInstanceOf(ClassGenerator::class, $generator);
    }

    public function testReturnClassShared()
    {
        $mapperA = $this->dic->get(ClassMapper::class);
        $mapperB = $this->dic->get(ClassMapper::class);

        $generatorA = $this->dic->get(ClassGenerator::class);
        $generatorB = $this->dic->get(ClassGenerator::class);

        $this->assertSame($mapperA, $mapperB);
        $this->assertSame($generatorA, $generatorB);
    }

    public function testReturnClassNotSharedMap()
    {
        $mapper = $this->dic->get(ClassMapper::class);
        $generator = $this->dic->get(ClassGenerator::class);

        $this->assertNotSame($mapper->map, $generator->map);
    }

    public function testReturnClassSharedFactory()
    {
        $mapDep = $this->dic->get(MapperDep::class);
        $genDep = $this->dic->get(GeneratorDep::class);

        $this->assertSame($mapDep->mapper->map, $genDep->gen->map);
    }

    public function testComplexMapperDep()
    {
        $mapper = $this->dic->get(ComplexMapperDep::class);

        $this->assertInstanceOf(FactoryInterfaceImpl::class, $mapper->interface);
        $this->assertInstanceOf(ClassMapper::class, $mapper->mapper);
    }
}
