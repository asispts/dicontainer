<?php declare(strict_types=1);

use Xynha\Container\DiContainer;
use Xynha\Container\DiRule;
use Xynha\Tests\AbstractTestCase;
use Xynha\Tests\Data\ChainUnit;
use Xynha\Tests\Data\DbUnit;
use Xynha\Tests\Data\PdoUnit;
use Xynha\Tests\Data\PdoUnitFactory;

final class CallTest extends AbstractTestCase
{

    public function testSetterInjection()
    {
        $rules['call'] = [['setAttribute', [1, 'setter injection']]];
        $rlist = $this->rlist->addRule(new DiRule(PdoUnit::class, $rules));
        $dic = new DiContainer($rlist);

        $obj = $dic->get(PdoUnit::class);
        $this->assertSame('setter injection', $obj->attr[1]);
    }

    public function testDependOnSetterInjection()
    {
        $rules['call'] = [['setAttribute', [1, 'setter injection']]];
        $rlist = $this->rlist->addRule(new DiRule(PdoUnit::class, $rules));
        $dic = new DiContainer($rlist);

        $obj = $dic->get(DbUnit::class);
        $this->assertSame('setter injection', $obj->pdo->attr[1]);
    }

    public function testNamedFactory()
    {
        $pdoRule['call'] = [['setAttribute', [1, 'From pdo rule']]];

        $namedRule['instanceOf'] = PdoUnit::class;
        $namedRule['call'] = [['setAttribute', [1, 'From named rule']]];

        $factoryRules['instanceOf'] = PdoUnitFactory::class;
        $factoryRules['call'] = [['getPdo', [], '.:CHAIN:.']];
        $factoryRules['constructParams'] = [['.:INSTANCE:.' => '$pdo']];

        $dbRule['instanceOf'] = DbUnit::class;
        $dbRule['constructParams'] = [['.:INSTANCE:.' => '$getPdo']];

        $rlist = $this->rlist->addRule(new DiRule(PdoUnit::class, $pdoRule));
        $rlist = $rlist->addRule(new DiRule('$db', $dbRule));
        $rlist = $rlist->addRule(new DiRule('$pdo', $namedRule));
        $rlist = $rlist->addRule(new DiRule('$getPdo', $factoryRules));
        $dic = new DiContainer($rlist);

        $obj = $dic->get(DbUnit::class);
        $this->assertSame('From pdo rule', $obj->pdo->attr[1]);

        $db = $dic->get('$db');
        $this->assertSame('From named rule', $db->pdo->attr[1]);
    }

    public function testChainCall()
    {
        $rules['call'] = [
                          ['chain1', ['String value'], '.:CHAIN:.'],
                          ['chain2', [['array', 'value']], '.:CHAIN:.'],
                          ['chain3', ['multiple', ['values']], '.:CHAIN:.'],
                          ['chain4', [], '.:CHAIN:.'],
                         ];
        $rlist = $this->rlist->addRule(new DiRule(ChainUnit::class, $rules));
        $dic = new DiContainer($rlist);

        $obj = $dic->get(ChainUnit::class);
        $this->assertSame('String value', $obj->data[0]);
        $this->assertSame(['array', 'value'], $obj->data[1]);
        $this->assertSame('multiple', $obj->data[2]);
        $this->assertSame(['values'], $obj->data[3]);
        $this->assertSame('No value', $obj->data[4]);
    }
}
