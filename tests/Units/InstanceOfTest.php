<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Container\DiRule;
use Xynha\Tests\Data\CheckArgument;
use Xynha\Tests\Units\AbstractTestCase;

final class InstanceOfTest extends AbstractTestCase
{

    public function testCreateFromVariable()
    {
        $rule['instanceOf'] = CheckArgument::class;
        $rule['constructParams'] = ['Passed value'];

        $rlist = $this->rlist->addRule(new DiRule('$instance', $rule));
        $dic = new DiContainer($rlist);

        $obj = $dic->get('$instance');
        $this->assertInstanceOf(CheckArgument::class, $obj);
        $this->assertSame('Passed value', $obj->arg);
    }

    public function testSameInstanceOf()
    {
        $ruleA['instanceOf'] = CheckArgument::class;
        $ruleA['constructParams'] = ['Rule A'];

        $ruleB['instanceOf'] = CheckArgument::class;
        $ruleB['constructParams'] = ['Rule B'];

        $rlist = $this->rlist->addRule(new DiRule('$instanceA', $ruleA));
        $rlist = $rlist->addRule(new DiRule('$instanceB', $ruleB));

        $dic = new DiContainer($rlist);

        $objA = $dic->get('$instanceA');
        $objB = $dic->get('$instanceB');

        $this->assertInstanceOf(CheckArgument::class, $objA);
        $this->assertSame('Rule A', $objA->arg);

        $this->assertInstanceOf(CheckArgument::class, $objB);
        $this->assertSame('Rule B', $objB->arg);
    }
}
