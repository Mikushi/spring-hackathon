<?php
class Home extends \Tachyon\Controller
{
	public function get() {
		$this->render("index.tpl");
		$this->sendResponse();
	}
}
