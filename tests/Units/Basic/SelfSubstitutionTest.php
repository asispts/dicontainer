<?php declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Xynha\Container\DiContainer;
use Xynha\Container\NotFoundException;
use Xynha\Tests\Units\AbstractTestCase;
use Xynha\Tests\Data\DicDependant;
use Xynha\Tests\Data\OverriddenDic;

final class SelfSubstitutionTest extends AbstractTestCase
{

    public function testEmptySelfDependencyRule()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('Class or rule %s does not exist', ContainerInterface::class));

        $this->dic->get(DicDependant::class);
    }

    public function testSelfDependencyRule()
    {
        $rule['substitutions'] = [ContainerInterface::class => DiContainer::class];
        $rlist = $this->rlist->addRule('*', $rule);

        $rule = $rlist->getRule(DicDependant::class);
        $this->assertSame(DiContainer::class, $rule->getSubstitutions()[ContainerInterface::class]);

        $dic = new DiContainer($rlist);
        $obj = $dic->get(DicDependant::class);

        $dicProp = new ReflectionProperty($dic, 'list');
        $dicProp->setAccessible(true);

        $objProp = new ReflectionProperty($obj->dic, 'list');
        $objProp->setAccessible(true);

        // Different DiContainer instance but with the same role list
        $this->assertNotSame($dic, $obj->dic);
        $this->assertSame($objProp->getValue($obj->dic), $dicProp->getValue($dic));
    }

    public function testOverriddenRule()
    {
        $rule['substitutions'] = [ContainerInterface::class => DiContainer::class];
        $rlist = $this->rlist->addRule('*', $rule);

        $orule['substitutions'] = [ContainerInterface::class => OverriddenDic::class];
        $rlist = $rlist->addRule(DicDependant::class, $orule);

        $rule = $rlist->getRule(DicDependant::class);
        $this->assertSame(OverriddenDic::class, $rule->getSubstitutions()[ContainerInterface::class]);

        $dic = new DiContainer($rlist);
        $obj = $dic->get(DicDependant::class);

        $this->assertInstanceOf(OverriddenDic::class, $obj->dic);
    }
}
