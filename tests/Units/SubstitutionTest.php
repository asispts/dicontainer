<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Container\NotFoundException;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\DependInterfaceA;
use Xynha\Tests\Data\ImplementInterfaceA;
use Xynha\Tests\Data\InterfaceA;

final class SubstitutionTest extends AbstractTestCase
{

    public function testMissingInterface()
    {
        $msg = sprintf('Class or rule `%s` does not exist', InterfaceA::class);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(DependInterfaceA::class);
    }

    public function testSubstitution()
    {
        $rules['substitutions'] = [InterfaceA::class => ImplementInterfaceA::class];
        $rlist = $this->rlist->addRule(DependInterfaceA::class, $rules);
        $dic = new DiContainer($rlist);
        $obj = $dic->get(DependInterfaceA::class);

        $this->assertInstanceOf(ImplementInterfaceA::class, $obj->arg);
    }

    public function testObjectSubstitution()
    {
        $rules['substitutions'] = [InterfaceA::class => new ImplementInterfaceA];
        $rlist = $this->rlist->addRule(DependInterfaceA::class, $rules);
        $dic = new DiContainer($rlist);
        $obj = $dic->get(DependInterfaceA::class);

        $this->assertInstanceOf(ImplementInterfaceA::class, $obj->arg);
    }
}
