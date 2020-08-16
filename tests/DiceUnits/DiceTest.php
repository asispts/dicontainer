<?php declare(strict_types=1);

namespace Xynha\Tests\DiceUnits;

use Xynha\Container\DiContainer;
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

    /** @var DiContainer */
    protected $dic;

    public function __construct()
    {
        parent::__construct();
        //  spl_autoload_register(array($this, 'autoload'));

        //Load the test classes for this test
        $name = str_replace('Test', '', get_class($this));
        require_once __DIR__ . '/TestData/Basic.php';

        $path = __DIR__ . '/TestData/' . $name . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    // @phpstan-ignore-next-line
    public function autoload($class)
    {
        //If Dice Triggers the autoloader the test fails
        //This generally means something invalid has been passed to
        //a method such as is_subclass_of or dice is trying to construct
        //an object from something it shouldn't.
        $this->fail('Autoload triggered: ' . $class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->rlist = new DiRuleList();
        $this->dic = new DiContainer($this->rlist);
    }

    protected function tearDown(): void
    {
        unset($this->dic);
        parent::tearDown();
    }
}
