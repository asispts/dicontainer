<?php declare(strict_types=1);

use Psr\Container\NotFoundExceptionInterface;
use Xynha\Tests\AbstractTestCase;

final class CheckExistTest extends AbstractTestCase
{

    public function testCreateNotExistClass()
    {
        $msg = sprintf('Class or rule `%s` is not found or it is an interface', 'NotExistClass');
        $this->expectException(NotFoundExceptionInterface::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get('NotExistClass');
    }

    public function testCreateNotExistRule()
    {
        $msg = sprintf('Class or rule `%s` is not found or it is an interface', 'TestRule');
        $this->expectException(NotFoundExceptionInterface::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get('TestRule');
    }

    public function testCheckExistClass()
    {
        $this->getMockBuilder('TestCreate')->getMock();
        $this->assertTrue($this->dic->has('TestCreate'));
    }

    public function testCheckExistRule()
    {
        $this->rule->newRule('TestRule');
        $this->assertTrue($this->dic->has('TestRule'));
    }
}
