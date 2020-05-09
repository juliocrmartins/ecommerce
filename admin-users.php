<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app->get("/admin/users/:iduser/password", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-password", array(
		"user"=>$user->getValues(),
		"msgSuccess"=>User::getMsgSuccess(),
		"msgError"=>User::getMsgError()		
	));	
});

$app->post("/admin/users/:iduser/password", function($iduser){

	User::verifyLogin();

	if (!isset($_POST["despassword"]) || $_POST["despassword"] === '')
	{
		User::setMsgError("Digite a nova senha.");
		header("Location: /admin/users/$iduser/password");
		exit;
	}

	if (!isset($_POST["despassword_confirm"]) || $_POST["despassword_confirm"] === '')
	{
		User::setMsgError("Confirme a nova senha.");
		header("Location: /admin/users/$iduser/password");
		exit;
	}	

	if ($_POST["despassword"] != $_POST["despassword_confirm"])
	{
		User::setMsgError("A confirmação da senha deve ser igual a nova senha.");
		header("Location: /admin/users/$iduser/password");
		exit;
	}

	$user = new User();

	$user->get((int)$iduser);		

	$user->setPassword(User::getPasswordHash($_POST["despassword"]));

	User::setMsgSuccess("Senha alterada com sucesso.");
	header("Location: /admin/users/$iduser/password");
	exit;
});

$app->get("/admin/users", function(){

	User::verifyLogin();

	$search = isset($_GET["search"]) ? $_GET["search"] : "";
	$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

	$pagination = User::getUsersPage($search, $page);

	$pages = [];

	for ($x = 1; $x <= $pagination["pages"]; $x++) { 
		
		array_push($pages, [
			"href"=>"/admin/users?" . http_build_query([
				"page"=>$x,
				"search"=>$search
			]),
			"text"=>$x
		]);
	}

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$pagination["data"],
		"search"=>$search,
		"pages"=>$pages
	));
});

$app->get("/admin/users/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");
});

$app->get("/admin/users/:iduser/delete", function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;
});

$app->get("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
});

$app->post("/admin/users/create", function() {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;	

	$user->setData($_POST);	

	$user->save();

	header("Location: /admin/users");
	exit;
});

$app->post("/admin/users/:iduser", function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;
});

 ?>