<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : ''; 
		$this->render('index');
	}

	public function inscreverse() {
		$this->view->utilizador = array(
			'nome' => '',
			'email' => '',
			'senha' => '',
		);

		$this->view->erroRegisto = false;

		$this->render('inscreverse');
	}

	public function registar(){
		// Receber dados do formulario

		$utilizador = Container::getModel('Utilizador'); 
		$utilizador->__set('nome', $_POST['nome']);
		$utilizador->__set('email', $_POST['email']);
		$utilizador->__set('senha', md5( $_POST['senha']));
		// Sucesso 
		if($utilizador->validarRegisto() && count($utilizador->getUtilizadorEmail()) == 0) {
	
			$utilizador->salvar(); 
			$this->render('registo');
	} else {
		$this->view->utilizador = array(
			'nome' => $_POST['nome'],
			'email' => $_POST['email'],
			'senha' => $_POST['senha'],
		);
		$this->view->erroRegisto = true;

		$this->render('inscreverse');
	}

	}

}


?>