<?php declare(strict_types=1);

namespace Xynha\Tests\Units;

use PHPUnit\Framework\TestCase;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRuleList;

abstract class AbstractTestCase extends TestCase
{

    /** @var DiRuleList */
    protected $rlist;

    /** @var DiContainer */
    protected $dic;

    protected function setUp()
    {
        $this->rlist = new DiRuleList();
        $this->dic = new DiContainer($this->rlist);
    }
}
