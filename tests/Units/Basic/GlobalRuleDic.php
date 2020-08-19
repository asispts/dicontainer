<?php declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Xynha\Container\DiContainer;
use Xynha\Container\NotFoundException;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\DicDependant;

final class GlobalRuleDic extends AbstractTestCase
{

    public function testEmptyRule()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('Class or rule %s does not exist', ContainerInterface::class));

        $this->dic->get(DicDependant::class);
    }

    public function testSelfDependency()
    {
        $rule['substitutions'] = [ContainerInterface::class => DiContainer::class];
        $rlist = $this->rlist->addRule('*', $rule);

        $rule = $rlist->getRule(DicDependant::class);
        $this->assertNotEmpty($rule->getSubstitutions());
        $dic = new DiContainer($rlist);

        $obj = $dic->get(DicDependant::class);

        $this->assertSame($dic, $obj->dic);
    }
}
