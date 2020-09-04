<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Container\DiRuleList;
use Xynha\Tests\Data\ClassInstanceOf;
use Xynha\Tests\Data\ImplementInterfaceInstanceOf;
use Xynha\Tests\Data\InterfaceInstanceOf;
use Xynha\Tests\Data\OverrideClassInstanceOf;
use Xynha\Tests\Units\Config\AbstractConfigTestCase;

final class InstanceOfTest extends AbstractConfigTestCase
{

    public function testOverrideInterface()
    {
        $obj = $this->dic->get(InterfaceInstanceOf::class);

        $this->assertInstanceOf(ImplementInterfaceInstanceOf::class, $obj);
    }

    public function testOverrideClass()
    {
        $obj = $this->dic->get(ClassInstanceOf::class);

        $this->assertInstanceOf(OverrideClassInstanceOf::class, $obj);
    }

    public function testPassObjectAsInstanceOf()
    {
        $passedObj = new ImplementInterfaceInstanceOf();
        $rule['instanceOf'] = $passedObj;

        $rlist = new DiRuleList();
        $rlist = $this->rlist->addRule(InterfaceInstanceOf::class, $rule);
        $dic = new DiContainer($rlist);

        $obj = $dic->get(InterfaceInstanceOf::class);

        $this->assertSame($passedObj, $obj);
    }
}
