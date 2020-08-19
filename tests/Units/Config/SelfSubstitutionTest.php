<?php declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Xynha\Container\AbstractDiContainer;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRuleList;
use Xynha\Container\NotFoundException;
use Xynha\Tests\Data\DicDependant;
use Xynha\Tests\Data\OverriddenDic;
use Xynha\Tests\Units\Config\AbstractConfigTest;

final class SelfSubstitutionTest extends AbstractConfigTest
{

    public function testEmptySelfDependencyRule()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('Class or rule %s does not exist', ContainerInterface::class));

        $dic = new DiContainer(new DiRuleList);
        $dic->get(DicDependant::class);
    }

    public function testSelfDependencyRule()
    {
        $obj = $this->dic->get(DicDependant::class);

        $dicProp = new ReflectionProperty(AbstractDiContainer::class, 'list');
        $dicProp->setAccessible(true);

        $objProp = new ReflectionProperty(AbstractDiContainer::class, 'list');
        $objProp->setAccessible(true);

        // Different DiContainer instance but with the same role list
        $this->assertNotSame($this->dic, $obj->dic);
        $this->assertSame($objProp->getValue($obj->dic), $dicProp->getValue($this->dic));
    }

    public function testOverriddenRule()
    {
        $rlist = $this->loadList('selfsubstitution');

        $dic = new DiContainer($rlist);
        $obj = $dic->get(DicDependant::class);
        $this->assertInstanceOf(DiContainer::class, $obj->dic);

        $orule['substitutions'] = [ContainerInterface::class => OverriddenDic::class];
        $rlist = $rlist->addRule(DicDependant::class, $orule);
        $dic = new DiContainer($rlist);
        $obj = $dic->get(OverriddenDic::class);
    }
}
