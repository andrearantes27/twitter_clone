<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    public function timeline()
    {
            $this->validarAutenticacao();
            // Recuperar tweets
            $tweet = Container::getModel('Tweet'); 
            $tweet->__set('id_utilizador', $_SESSION['id']);
            $tweets = $tweet->getAll();
            $this->view->tweets = $tweets;  

        $utilizador = Container::getModel('Utilizador'); 
        $utilizador->__set('id', $_SESSION['id']); 

        $this->view->info_utilizador = $utilizador->getInfoUtilizador();
        $this->view->total_tweets = $utilizador->getTotalTweets();
        $this->view->total_a_seguir = $utilizador->getTotalASeguir();
        $this->view->total_seguidores =  $utilizador->getTotalSeguidores();

            $this->render('timeline');
    }
    public function tweet() {
          $this->validarAutenticacao();

          $tweet = Container::getModel('Tweet');
          
          $tweet->__set('tweet', $_POST['tweet']);
          $tweet->__set('id_utilizador', $_SESSION['id']);

          $tweet->salvar(); 
            header('Location: /timeline');
    }

    public function validarAutenticacao() {
        session_start(); 
        if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '' ) {
            header('Location: /?login=erroAutenticacao=false');
        } 

     }

     public function quemSeguir() {
         $this->validarAutenticacao();
        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
        
        $utilizadores = array(); 
        

         if($pesquisarPor != '') {
            $utilizador = Container::getModel('Utilizador'); 
             $utilizador->__set('nome', $pesquisarPor);
             $utilizador->__set('id', $_SESSION['id']);
             $utilizadores = $utilizador->getAll(); 
         }
        $this->view->utilizadores = $utilizadores; 
        $utilizador = Container::getModel('Utilizador'); 
        $utilizador->__set('id', $_SESSION['id']); 

        $this->view->info_utilizador = $utilizador->getInfoUtilizador();
        $this->view->total_tweets = $utilizador->getTotalTweets();
        $this->view->total_a_seguir = $utilizador->getTotalASeguir();
        $this->view->total_seguidores =  $utilizador->getTotalSeguidores();

        $this->render('quemSeguir'); 
    }
    public function acao() {
        $this->validarAutenticacao();
       
        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_utilizador_seguidor = isset($_GET['id_utilizador']) ? $_GET['id_utilizador'] : '';
        
        $utilizador = Container::getModel('Utilizador'); 
        $utilizador->__set('id', $_SESSION['id']); 

        if($acao == 'seguir') {
            $utilizador->seguirUtilizador($id_utilizador_seguidor);
        } else if($acao == 'deixar_seguir') {
            $utilizador->deixarSeguirUtilizador($id_utilizador_seguidor); 
        }
        header('Location: /quem_seguir');
     }

     public function removerTweet(){
        $this->validarAutenticacao();
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id', $id);
        $tweet->remover();
        header('location: /timeline');
    }
    
    }
    


?>