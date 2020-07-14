<?php declare(strict_types=1);

use Xynha\Tests\AbstractTestCase;

final class BasicTest extends AbstractTestCase
{

    public function testNoConstructor()
    {
        $this->assertTrue($this->dic->has('NoConstructor'));

        $obj = $this->dic->get('NoConstructor');
        $this->assertInstanceOf('NoConstructor', $obj);
    }
}
