<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
use Xynha\Container\ContainerException;
use Xynha\Container\DiContainer;
use Xynha\Container\DiRuleList;
use Xynha\Tests\Data\ScalarAllowsNull;
use Xynha\Tests\Data\ScalarRequired;
use Xynha\Tests\Units\Config\AbstractConfigTestCase;

final class ConstructParamsInvalidTest extends AbstractConfigTestCase
{

    protected function setUp()
    {
        $this->files = ['BasicClass.php', 'ConstructParams.php'];
        parent::setUp();
    }

    public function testMissingConstructParams()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Missing required argument $%s passed to %s::__construct()',
                'bool',
                ScalarRequired::class
            )
        );

        $dic = new DiContainer(new DiRuleList);
        $dic->get(ScalarRequired::class);
    }

    public function testPassInvalidScalarType()
    {
        $type = 'bool or null';
        if (PHP_VERSION_ID >= 70100 && PHP_VERSION_ID < 70300) {
            $type = 'boolean or null';
        }

        $msg = sprintf(
            'Argument 1 passed to %s::__construct() must be of the type %s, array given',
            ScalarAllowsNull::class,
            $type
        );

        if (PHP_MAJOR_VERSION >= 8) {
            $msg = sprintf(
                '%s::__construct(): Argument #1 ($bool) must be of type ?bool, array given',
                ScalarAllowsNull::class
            );
        }

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage($msg);

        $rules['constructParams'] = [[]];

        $rlist = new DiRuleList();
        $rlist = $rlist->addRule(ScalarAllowsNull::class, $rules);
        $dic = new DiContainer($rlist);
        $dic->get(ScalarAllowsNull::class);
    }
}
