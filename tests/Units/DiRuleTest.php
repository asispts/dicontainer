<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Xynha\Container\DiRule;
use Xynha\Container\DiRuleList;

final class DiRuleTest extends TestCase
{

    /** @var DiRuleList */
    private $list;

    protected function setUp()
    {
        $this->list = new DiRuleList();
    }

    public function testGetNotExistRule()
    {
        $this->assertNull($this->list->getRule('key'));
    }

    public function testAddRuleObject()
    {
        $rule = new DiRule('key');
        $this->list->addRule($rule);

        $rule->setShared(true);

        $this->assertSame($rule, $this->list->getRule('key'));
    }

    public function testNewRule()
    {
        $rule = $this->list->newRule('key');
        $rule->setShared(true);

        $this->assertSame($rule, $this->list->getRule('key'));
    }
}
