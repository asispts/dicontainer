<?php
/**
 * Dice - A minimal Dependency Injection Container for PHP
 *
 * @author Tom Butler tom@r.je
 * @copyright 2012-2018 Tom Butler <tom@r.je> | https:// r.je/dice.html
 * @license http:// www.opensource.org/licenses/bsd-license.php BSD License
 * @version 3.0
 */
class TestSharedInstancesTop {
	public $share1;
	public $share2;

	public function __construct(SharedInstanceTest1 $share1, SharedInstanceTest2 $share2) {
		$this->share1 = $share1;
		$this->share2 = $share2;
	}
}




class SharedInstanceTest1 {
	public $shared;

	public function __construct(Shared $shared) {
		$this->shared = $shared;
	}
}


class SharedInstanceTest2 {
	public $shared;

	public function __construct(Shared $shared) {
		$this->shared = $shared;
	}
}



class M1 {
	public $f;
	public function __construct(F $f) {
		$this->f = $f;
	}
}

class M2 {
	public $e;
	public function __construct(E $e) {
		$this->e = $e;
	}
}

class A4 {
	public $m1;
	public $m2;
	public function __construct(M1 $m1, M2 $m2) {
		$this->m1 = $m1;
		$this->m2 = $m2;
	}
}

class Shared {
	public $uniq;

	public function __construct() {
		$this->uniq = uniqid();
	}
}
