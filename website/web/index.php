<?php
set_include_path(
	get_include_path() .
	PATH_SEPARATOR .
	"../lib/");

require_once "Tachyon/Application.php";

$urls = array(
			  "/"=> "Home",
              "/:postcode" => "Result",
			  "404" => function() {
							echo "Page not found, sad story :'(";
			  		   }
			);

$app = new \Tachyon\Application($urls);
$app->setTemplateDir(getcwd() . "/../views/")
	->setControllerDir(getcwd() . "/../controllers/")
	->run();
