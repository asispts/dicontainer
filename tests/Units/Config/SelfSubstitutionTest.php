<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Tests\Data\DicDependant;
use Xynha\Tests\Units\Config\AbstractConfigTest;

final class SelfSubstitutionTest extends AbstractConfigTest
{

    public function testInterfaceRule()
    {
        $obj = $this->dic->get(DicDependant::class);

        $this->assertInstanceOf(DiContainer::class, $obj->dic);

        $listVar = new ReflectionProperty(DiContainer::class, 'list');
        $listVar->setAccessible(true);

        // Different DiContainer instance but with the same role list
        $this->assertNotSame($this->dic, $obj->dic);
        $this->assertSame($listVar->getValue($obj->dic), $listVar->getValue($this->dic));
    }
}
