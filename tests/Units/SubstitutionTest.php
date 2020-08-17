<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRule;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\DependInterfaceA;
use Xynha\Tests\Data\ImplementInterfaceA;
use Xynha\Tests\Data\InterfaceA;

final class SubstitutionTest extends AbstractTestCase
{

    public function testMissingInterface()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(sprintf('Missing interface %s substitution', InterfaceA::class));

        $this->dic->get(DependInterfaceA::class);
    }

    public function testSubstitution()
    {
        $rules['substitutions'] = [InterfaceA::class => ImplementInterfaceA::class];
        $rlist = $this->rlist->addRule(new DiRule(DependInterfaceA::class, $rules));
        $dic = new DiContainer($rlist);
        $obj = $dic->get(DependInterfaceA::class);

        $this->assertInstanceOf(ImplementInterfaceA::class, $obj->arg);
    }

    public function testObjectSubstitution()
    {
        $rules['substitutions'] = [InterfaceA::class => new ImplementInterfaceA];
        $rlist = $this->rlist->addRule(new DiRule(DependInterfaceA::class, $rules));
        $dic = new DiContainer($rlist);
        $obj = $dic->get(DependInterfaceA::class);

        $this->assertInstanceOf(ImplementInterfaceA::class, $obj->arg);
    }
}
