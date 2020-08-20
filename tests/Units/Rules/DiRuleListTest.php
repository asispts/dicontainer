<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Xynha\Container\DiRule;
use Xynha\Container\DiRuleList;
use Xynha\Container\NotFoundException;

final class DiRuleListTest extends TestCase
{

    private $json = '
    {
        "$empty": {
        },
        "$full": {
            "instanceOf" : "FullClassName",
            "shared" : false,
            "substitutions" : {
                "FullInterface" : "FullClass",
                "InterfaceOverwrite" : "InitialClass"
            },
            "constructParams" : ["Initial value"],
            "call" : [
                ["method", []]
            ]
        }
    }';

    /** @var DiRuleList */
    private $rlist;

    protected function setup()
    {
        $this->rlist = new DiRuleList();
        $this->rlist = $this->rlist->addRules(json_decode($this->json, true));
    }

    public function testNotExistRule()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Rule $rule does not exist');

        $this->rlist->getRule('$rule');
    }

    public function testEmptyRule()
    {
        $rule = $this->rlist->getRule('$empty');

        $this->assertSame('$empty', $rule->getKey());
        $this->assertFalse($rule->isShared());
        $this->assertSame('$empty', $rule->getClassname());
        $this->assertSame([], $rule->getSubstitutions());
        $this->assertSame([], $rule->getParams());
        $this->assertSame([], $rule->call());
    }

    public function testCheckRule()
    {
        $rule = $this->rlist->getRule('$full');

        $subs = ['FullInterface' => 'FullClass', 'InterfaceOverwrite' => 'InitialClass'];

        $this->assertSame('$full', $rule->getKey());
        $this->assertFalse($rule->isShared());
        $this->assertSame('FullClassName', $rule->getClassname());
        $this->assertSame($subs, $rule->getSubstitutions());
        $this->assertSame(['Initial value'], $rule->getParams());
        $this->assertSame([['method', []]], $rule->call());
    }

    public function testInheritRule()
    {
        $newSubs = [
                    'InterfaceOverwrite' => 'ClassOverwrite',
                    'NewInterface'       => 'NewClass'
                   ];

        $rules = [
                  'instanceOf'      => 'OverwriteInstance',
                  'shared'          => true,
                  'constructParams' => ['overwrite'],
                  'substitutions'   => $newSubs,
                  'call'            => ['overwriteMethod', []]
                 ];

        $rlist = $this->rlist->addRule('*', $rules);

        $emptyRule = $rlist->getRule('$empty');
        $this->assertSame('$empty', $emptyRule->getKey());
        $this->assertSame(true, $emptyRule->isShared());
        $this->assertSame('OverwriteInstance', $emptyRule->getClassname());
        $this->assertSame($newSubs, $emptyRule->getSubstitutions());
        $this->assertSame(['overwrite'], $emptyRule->getParams());
        $this->assertSame([], $emptyRule->call());


        $fullSubs = [
                     'FullInterface'      => 'FullClass',
                     'InterfaceOverwrite' => 'InitialClass',
                     'NewInterface'       => 'NewClass'
                    ];

        $fullRule = $rlist->getRule('$full');
        $this->assertSame('$full', $fullRule->getKey());
        $this->assertSame(false, $fullRule->isShared());
        $this->assertSame('FullClassName', $fullRule->getClassname());
        $this->assertSame($fullSubs, $fullRule->getSubstitutions());
        $this->assertSame(['Initial value'], $fullRule->getParams());
        $this->assertSame([['method', []]], $fullRule->call());
    }

    public function testMergeRule()
    {
        $newSubs = [
                    'InterfaceOverwrite' => 'ClassOverwrite',
                    'NewInterface'       => 'NewClass'
                   ];

        $rules = [
                  'instanceOf'      => 'OverwriteInstance',
                  'shared'          => true,
                  'constructParams' => ['overwrite'],
                  'substitutions'   => $newSubs,
                  'call'            => ['overwriteMethod', []]
                 ];

        $rlist = $this->rlist->addRule('$empty', $rules);
        $rlist = $rlist->addRule('$full', $rules);

        $emptyRule = $rlist->getRule('$empty');
        $this->assertSame('$empty', $emptyRule->getKey());
        $this->assertSame(true, $emptyRule->isShared());
        $this->assertSame('OverwriteInstance', $emptyRule->getClassname());
        $this->assertSame($newSubs, $emptyRule->getSubstitutions());
        $this->assertSame(['overwrite'], $emptyRule->getParams());
        $this->assertSame(['overwriteMethod', []], $emptyRule->call());


        $fullSubs = [
                     'FullInterface'      => 'FullClass',
                     'InterfaceOverwrite' => 'ClassOverwrite',
                     'NewInterface'       => 'NewClass'
                    ];

        $fullRule = $rlist->getRule('$full');
        $this->assertSame('$full', $fullRule->getKey());
        $this->assertSame(true, $fullRule->isShared());
        $this->assertSame('OverwriteInstance', $fullRule->getClassname());
        $this->assertSame($fullSubs, $fullRule->getSubstitutions());
        $this->assertSame(['overwrite'], $fullRule->getParams());
        $this->assertSame(['overwriteMethod', []], $fullRule->call());
    }
}
