<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
namespace Xynha\Tests\Units\Config;

use PHPUnit\Framework\TestCase;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRuleList;

abstract class AbstractConfigTestCase extends TestCase
{

    /** @var DiRuleList */
    protected $rlist;

    /** @var DiContainer */
    protected $dic;

    /** @var string[] */
    protected $files = [];

    protected function setUp()
    {
        $nspaces  = explode('\\', static::class);
        $filename = str_replace('Test', '', array_pop($nspaces)); // @phpstan-ignore-line

        $this->files = array_merge($this->files, [$filename . '.php']);
        foreach ($this->files as $file) {
            $path = DATA_DIR . '/' . $file;
            if (file_exists($path) === true) {
                require_once $path;
            }
        }

        $configPath = DATA_DIR . '/config/' . $filename . '.json';
        $json       = [];

        if (file_exists($configPath) === true) {
            $json = json_decode((string) file_get_contents($configPath), true);
        }

        $rlist       = new DiRuleList();
        $this->rlist = $rlist->addRules($json);
        $this->dic   = new DiContainer($this->rlist);
    }
}
