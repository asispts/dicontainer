<?php declare(strict_types=1);

namespace Xynha\Tests\Units\Config;

use PHPUnit\Framework\TestCase;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRuleList;

abstract class AbstractConfigTest extends TestCase
{

    /** @var DiContainer */
    protected $dic;

    protected function setUp()
    {
        $nspaces = explode('\\', static::class);
        $filename = str_replace('Test', '', array_pop($nspaces)); // @phpstan-ignore-line

        // Load unit tests
        $unit = DATA_DIR . '/' . $filename . '.php';
        if (file_exists($unit)) {
            require_once $unit;
        }

        $rlist = $this->loadList(strtolower($filename));
        $this->dic = new DiContainer($rlist);
    }

    protected function loadList(string $filename) : DiRuleList
    {
        $content = (string)file_get_contents(DATA_DIR . '/config/' . $filename . '.json');

        $rlist = new DiRuleList();
        return $rlist->addRules(json_decode($content, true));
    }
}
