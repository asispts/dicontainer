<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Xynha\Container\DiRuleList;
use Xynha\Container\NotFoundException;

final class DiRuleListTest extends TestCase
{

    /** @var string */
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
            "constructParams" : ["Initial value"]
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

        $this->assertSame('$empty', $rule->key());
        $this->assertFalse($rule->isShared());
        $this->assertSame('$empty', $rule->classname());
        $this->assertSame([], $rule->substitutions());
        $this->assertSame([], $rule->params());
    }

    public function testCheckRule()
    {
        $rule = $this->rlist->getRule('$full');

        $subs = ['FullInterface' => 'FullClass', 'InterfaceOverwrite' => 'InitialClass'];

        $this->assertSame('$full', $rule->key());
        $this->assertFalse($rule->isShared());
        $this->assertSame('FullClassName', $rule->classname());
        $this->assertSame($subs, $rule->substitutions());
        $this->assertSame(['Initial value'], $rule->params());
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
                  'substitutions'   => $newSubs
                 ];

        $rlist = $this->rlist->addRule('$empty', $rules);
        $rlist = $rlist->addRule('$full', $rules);

        $emptyRule = $rlist->getRule('$empty');
        $this->assertSame('$empty', $emptyRule->key());
        $this->assertSame(true, $emptyRule->isShared());
        $this->assertSame('OverwriteInstance', $emptyRule->classname());
        $this->assertSame($newSubs, $emptyRule->substitutions());
        $this->assertSame(['overwrite'], $emptyRule->params());


        $fullSubs = [
                     'FullInterface'      => 'FullClass',
                     'InterfaceOverwrite' => 'ClassOverwrite',
                     'NewInterface'       => 'NewClass'
                    ];

        $fullRule = $rlist->getRule('$full');
        $this->assertSame('$full', $fullRule->key());
        $this->assertSame(true, $fullRule->isShared());
        $this->assertSame('OverwriteInstance', $fullRule->classname());
        $this->assertSame($fullSubs, $fullRule->substitutions());
        $this->assertSame(['overwrite'], $fullRule->params());
    }
}
