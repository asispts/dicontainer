<?php declare(strict_types=1);

use Xynha\Tests\Data\ArrayInjected;
use Xynha\Tests\Data\ClassInjected;
use Xynha\Tests\Units\Config\AbstractConfigTestCase;

final class ConstructParamsCallTest extends AbstractConfigTestCase
{

    protected function setUp()
    {
        $this->files = ['ConstructParams.php'];
        parent::setUp();
    }

    public function testInjectClass()
    {
        $obj = $this->dic->get(ClassInjected::class);

        $this->assertSame('Injector::getClass => Call object value', $obj->obj->arg);
    }

    public function testInjectArray()
    {
        $obj = $this->dic->get(ArrayInjected::class);

        $this->assertSame(['Injector', 'getArray'], $obj->values);
    }
}
