<?php

class Foo77 {
	public $bar;

	public function __construct(Bar77 $bar) {
		$this->bar = $bar;
	}
}

class Bar77 {
	public $a;

	public function __construct($a) {
		$this->a = $a;
	}
}

class Baz77 {
	public static function create() {
		return new Bar77('Z');
	}
}

class Foo
{

    public $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}

class Foo2
{

    public function bar()
    {
        return new Baz;
    }
}

interface Bar
{

}

class Baz implements Bar
{

}
