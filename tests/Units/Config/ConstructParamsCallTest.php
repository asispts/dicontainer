<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */

use PHPUnit\Framework\Error\Warning;
use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Tests\Data\ArrayInjected;
use Xynha\Tests\Data\ClassInjected;
use Xynha\Tests\Data\Injector;
use Xynha\Tests\Data\MixedArgument;
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

    public function testInvalidCallScalarInCallObjectRule()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Require CALL::OBJECT, CALL::SCALAR given');

        $rule['constructParams'] = [
                                    [
                                     'CALL::SCALAR',
                                     [Injector::class, 'getClass'],
                                    ],
                                   ];
        $rlist = $this->rlist->addRule(ClassInjected::class, $rule);
        $dic = new DiContainer($rlist);

        $dic->get(ClassInjected::class);
    }

    public function testInvalidCallObjectInCallScalarRule()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Require CALL::SCALAR or CALL::CONSTANT, CALL::OBJECT given');

        $rule['constructParams'] = [
                                    [
                                     'CALL::OBJECT',
                                     [Injector::class, 'getClass'],
                                    ],
                                   ];
        $rlist = $this->rlist->addRule(ArrayInjected::class, $rule);
        $dic = new DiContainer($rlist);

        $dic->get(ArrayInjected::class);
    }

    public function testCallConstantBuiltin()
    {
        $obj = $this->dic->get('$const_builtin');

        $this->assertSame(PDO::ERRMODE_EXCEPTION, $obj->arg);
    }

    public function testCallConstantClass()
    {
        $obj = $this->dic->get('$const_class');

        $this->assertSame(MixedArgument::PUBLIC_CONST, $obj->arg);
    }

    public function testCallConstantFromDefine()
    {
        $obj = $this->dic->get('$define');

        $this->assertSame(DATA_DIR, $obj->arg);
    }

    public function testCallPrivateConstant()
    {
        $msg = sprintf('constant(): Couldn\'t find constant %s::PRIVATE_CONST', MixedArgument::class);
        $this->expectException(Warning::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get('$const_private');
    }

    public function testCallConstantUnknown()
    {
        $this->expectException(Warning::class);
        $this->expectExceptionMessage('constant(): Couldn\'t find constant 1');

        $this->dic->get('$const_unknown');
    }

    public function testCallConstantInvalidEmpty()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Invalid CALL::CONSTANT format');

        $this->dic->get('$const_empty');
    }

    public function testCallConstantInvalidArray()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Invalid CALL::CONSTANT format');

        $this->dic->get('$const_array');
    }
}
