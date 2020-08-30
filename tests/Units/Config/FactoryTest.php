<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRuleList;
use Xynha\Tests\Data\FactoryInterface;
use Xynha\Tests\Data\FactoryInterfaceDep;
use Xynha\Tests\Data\FactoryInterfaceImpl;
use Xynha\Tests\Data\GeneratorDep;
use Xynha\Tests\Data\MapFactory;
use Xynha\Tests\Data\MapperDep;
use Xynha\Tests\Units\Config\AbstractConfigTest;

final class FactoryTest extends AbstractConfigTest
{

    public function testInvalidGetFrom()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Invalid getFrom format');

        $rule['getFrom'] = ['invalid'];

        $rlist = new DiRuleList();
        $rlist = $rlist->addRule(FactoryInterface::class, $rule);
        $dic = new DiContainer($rlist);

        $dic->get(FactoryInterfaceDep::class);
    }

    public function testGetFromTooManyFields()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Invalid getFrom format');

        $rule['getFrom'] = ['invalid', '', '', ''];

        $rlist = new DiRuleList();
        $rlist = $rlist->addRule(FactoryInterface::class, $rule);
        $dic = new DiContainer($rlist);

        $dic->get(FactoryInterfaceDep::class);
    }

    public function testNotCallable()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Rule getFrom is not callable');

        $rule['getFrom'] = ['DateTime', 'Invalid', 'method'];

        $rlist = new DiRuleList();
        $rlist = $rlist->addRule(FactoryInterface::class, $rule);
        $dic = new DiContainer($rlist);

        $dic->get(FactoryInterfaceDep::class);
    }

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

        $factory = $this->dic->get(MapFactory::class);
        $this->assertSame($mapper->mapper, $factory->getMapper());
        $this->assertSame($generator->gen, $factory->getGenerator());
    }
}
