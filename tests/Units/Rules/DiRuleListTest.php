<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Xynha\Container\DiRuleList;
use Xynha\Container\NotFoundException;

final class DiRuleListTest extends TestCase
{

    public function testAddRules()
    {
        $json = '
        {
            "key1" : {
                "substitutions": {
                    "InterfaceA" : "ClassA"
                }
            },
            "key2" : {
                "constructParams": [
                    "string",
                    ["array", "value"]
                ]
            }
        }';

        $rlist = new DiRuleList();
        $rlist = $rlist->addRules(json_decode($json, true));

        $this->assertSame(['InterfaceA' => 'ClassA'], $rlist->getRule('key1')->getSubstitutions());
        $this->assertSame([], $rlist->getRule('key2')->getSubstitutions());

        $this->assertSame([], $rlist->getRule('key1')->getParams());
        $this->assertSame(['string', ['array', 'value']], $rlist->getRule('key2')->getParams());
    }

    public function testNotExistRule()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Rule $rule does not exist');

        $rlist = new DiRuleList();
        $rlist->getRule('$rule');
    }
}
