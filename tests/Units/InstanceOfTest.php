<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\CheckArgument;

final class InstanceOfTest extends AbstractTestCase
{

    public function testCreateFromVariable()
    {
        $rule['instanceOf'] = CheckArgument::class;
        $rule['constructParams'] = ['Passed value'];

        $this->rlist->newRule('$instance', $rule);
        $dic = new DiContainer($this->rlist);

        $obj = $dic->get('$instance');
        $this->assertInstanceOf(CheckArgument::class, $obj);
        $this->assertSame('Passed value', $obj->arg);
    }

    public function testSameInstanceOf()
    {
        $ruleA['instanceOf'] = CheckArgument::class;
        $ruleA['constructParams'] = ['Rule A'];
        $this->rlist->newRule('$instanceA', $ruleA);

        $ruleB['instanceOf'] = CheckArgument::class;
        $ruleB['constructParams'] = ['Rule B'];
        $this->rlist->newRule('$instanceB', $ruleB);

        $dic = new DiContainer($this->rlist);

        $objA = $dic->get('$instanceA');
        $objB = $dic->get('$instanceB');

        $this->assertInstanceOf(CheckArgument::class, $objA);
        $this->assertSame('Rule A', $objA->arg);

        $this->assertInstanceOf(CheckArgument::class, $objB);
        $this->assertSame('Rule B', $objB->arg);
    }
}
