<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Container\DiRule;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\ObjectDefaultValue;

final class InstanceOfTest extends AbstractTestCase
{

    public function testNamedRule()
    {
        $rules['instanceOf'] = ObjectDefaultValue::class;
        $rlist = $this->rlist->addRule(new DiRule('$named', $rules));
        $dic = new DiContainer($rlist);
        $obj = $dic->get('$named');

        $this->assertInstanceOf(ObjectDefaultValue::class, $obj);
    }
}
