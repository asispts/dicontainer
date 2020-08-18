<?php declare(strict_types=1);

use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\DbUnit;
use Xynha\Tests\Data\ObjectDefaultValue;

final class InstanceOfTest extends AbstractTestCase
{

    public function testMissingInstanceOf()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Class $named does not exist');

        $rlist = $this->rlist->addRule('$named', []);
        $dic = new DiContainer($rlist);
        $dic->get('$named');
    }

    public function testMissingInstanceOfInParams()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Class $pdo does not exist');

        $rules['constructParams'] = [['.:INSTANCE:.' => '$pdo']];
        $rules['instanceOf'] = DbUnit::class;
        $rlist = $this->rlist->addRule('$named', $rules);
        $rlist = $rlist->addRule('$pdo', []);

        $dic = new DiContainer($rlist);
        $dic->get('$named');
    }

    public function testNamedRule()
    {
        $rules['instanceOf'] = ObjectDefaultValue::class;
        $rlist = $this->rlist->addRule('$named', $rules);
        $dic = new DiContainer($rlist);
        $obj = $dic->get('$named');

        $this->assertInstanceOf(ObjectDefaultValue::class, $obj);
    }
}
