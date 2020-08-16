<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;
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
        $this->expectException(NotFoundExceptionInterface::class);
        $this->expectExceptionMessage('Rule key is not found');

        $this->list->getRule('key');
    }
}
