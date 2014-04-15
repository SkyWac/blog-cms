<?php

namespace Auth\Controller;

use ZfcUser\Controller\UserController as UserExtended;


class UserController extends UserExtended {



	public function loginAction() { 
		$test = parent::loginAction();
		die(var_dump($test));
		return new $test;
	}


}