<?php declare(strict_types=1);

namespace Xynha\Tests\DiceUnits;

use Xynha\Container\DiContainer;
use Xynha\Container\DiRule;
use Xynha\Container\DiRuleList;

/**
 * Dice - A minimal Dependency Injection Container for PHP
 *
 * @author Tom Butler tom@r.je
 * @copyright 2012-2018 Tom Butler <tom@r.je> | https:// r.je/dice.html
 * @license http:// www.opensource.org/licenses/bsd-license.php BSD License
 * @version 3.0
 */
abstract class DiceTest extends \PHPUnit\Framework\TestCase
{

    /** @var DiRuleList */
    protected $rlist;

    public function __construct()
    {
        parent::__construct();

        //Load the test classes for this test
        $name = str_replace('Test', '', get_class($this));
        require_once __DIR__ . '/TestData/Basic.php';

        $path = __DIR__ . '/TestData/' . $name . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->rlist = new DiRuleList();
    }
}
