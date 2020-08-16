<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\DependInterfaceA;
use Xynha\Tests\Data\ImplementInterfaceA;
use Xynha\Tests\Data\InterfaceA;
use Xynha\Tests\Data\NoConstructor;

final class SubstitutionTest extends AbstractTestCase
{

    public function testRequiredSubstitution()
    {
        $msg = sprintf('Missing %s substitution', InterfaceA::class);
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(DependInterfaceA::class);
    }

    public function testSubstitution()
    {
        $rules['substitutions'] = [InterfaceA::class => ImplementInterfaceA::class];
        $this->rlist->newRule(DependInterfaceA::class, $rules);
        $dic = new DiContainer($this->rlist);

        $obj = $dic->get(DependInterfaceA::class);

        $this->assertInstanceOf(ImplementInterfaceA::class, $obj->arg);
    }

    public function testSubstituteInvalidClass()
    {
        $msg = 'Argument 1 passed to %s::__construct() must implement interface %s, instance of %s given';
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(sprintf($msg, DependInterfaceA::class, InterfaceA::class, NoConstructor::class));

        $rules['substitutions'] = [InterfaceA::class => NoConstructor::class];
        $this->rlist->newRule(DependInterfaceA::class, $rules);
        $dic = new DiContainer($this->rlist);

        $dic->get(DependInterfaceA::class);
    }
}
