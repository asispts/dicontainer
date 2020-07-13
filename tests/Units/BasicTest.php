<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Xynha\Container\DiContainer;

final class BasicTest extends TestCase
{

    /** @var DiContainer */
    private $dic;

    protected function setUp()
    {
        $this->dic = new DiContainer();
    }

    public function testNoConstructor()
    {
        $this->getMockBuilder('NoConstructorClass')->getMock();
        $this->assertTrue($this->dic->has('NoConstructorClass'));

        $obj = $this->dic->get('NoConstructorClass');
        $this->assertInstanceOf('NoConstructorClass', $obj);
    }
}
