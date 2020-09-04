<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Container\NotFoundException;
use Xynha\Tests\Data\ClassNoRule;
use Xynha\Tests\Data\DependInterfaceA;
use Xynha\Tests\Data\DependInvalidSubsInterface;
use Xynha\Tests\Data\ImplementInterfaceA;
use Xynha\Tests\Data\InterfaceNoRule;
use Xynha\Tests\Units\Config\AbstractConfigTestCase;

final class SubstitutionsTest extends AbstractConfigTestCase
{

    public function testMissingSubstitution()
    {
        $msg = sprintf('Class or rule %s does not exist', InterfaceNoRule::class);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(ClassNoRule::class);
    }

    public function testSubstitutionRule()
    {
        $obj = $this->dic->get(DependInterfaceA::class);

        $this->assertInstanceOf(ImplementInterfaceA::class, $obj->obj);
    }

    public function testSubsWithDifferentClass()
    {
        $this->expectException(ContainerException::class);

        $this->dic->get(DependInvalidSubsInterface::class);
    }
}
