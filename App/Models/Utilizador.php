<?php

namespace App\Models;
use MF\Model\Model;

class Utilizador extends Model {
	private $id; 
	private $nome; 
	private $email; 
	private $senha;

	public function __get($atributo) {
		return $this->$atributo; 
	}
    public function __set($atributo, $valor) {
    	$this->$atributo = $valor;
    }

	// salvar

    public function salvar() {
    	$query = "INSERT INTO utilizadores(nome, email, senha)
    	VALUES(:nome, :email, :senha)";
    	$stmt = $this->db->prepare($query);
    	$stmt->bindValue(':nome', $this->__get('nome'));
    	$stmt->bindValue(':email', $this->__get('email'));
    	$stmt->bindValue(':senha', $this->__get('senha'));
    	$stmt->execute(); 
    	return $this; 
    }

	// Verificar se o registo pode ser feito
	public function validarRegisto() {
		$valido = true;

		if(strlen($this->__get('nome')) < 3) {
			$valido = false; 
		}

		if(strlen($this->__get('email')) < 3) {
			$valido = false; 
		}
		if(strlen($this->__get('senha')) < 3) {
			$valido = false; 
		}
		return $valido; 
	}



	// Recuperar utilizador por e-mail
		public function getUtilizadorEmail()
		{
			$query = "SELECT nome, email 
			FROM utilizadores
			WHERE email = :email";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(':email', $this->__get('email'));
			$stmt->execute();
			
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		public function autenticar() {
			$query = "SELECT id, nome, email 
			FROM utilizadores WHERE email = :email AND senha = :senha";
			$stmt = $this->db->prepare($query); 
			$stmt->bindValue(':email', $this->__get('email'));
			$stmt->bindValue(':senha', $this->__get('senha')); 
			$stmt->execute(); 

			$utilizador = $stmt->fetch(\PDO::FETCH_ASSOC); 
			if($utilizador['id'] != '' && $utilizador['nome'] != '') {
				$this->__set('id', $utilizador['id']);
				$this->__set('nome', $utilizador['nome']);
			}
			return $this; 
		}
		public function getAll()
		{
			$query = "SELECT u.id, u.nome, u.email,
			(SELECT count(*) 
			FROM seguidores AS us 
				WHERE us.id_utilizador = :id_utilizador AND us.id_utilizador_seguidor = u.id) 
				AS seguir_sn FROM utilizadores AS u
		WHERE u.nome like :nome and u.id != :id_utilizador
		";
			$stmt = $this->db->prepare($query); 
			$stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
			$stmt->bindValue(':id_utilizador', $this->__get('id'));
			$stmt->execute(); 

			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		public function seguirUtilizador($id_utilizador_seguidor) {
			$query = "INSERT INTO seguidores (id_utilizador,id_utilizador_seguidor) 
			 VALUES(:id_utilizador, :id_utilizador_seguidor)";
			 $stmt = $this->db->prepare($query); 
			 $stmt->bindValue(':id_utilizador', $this->__get('id'));
			 $stmt->bindValue(':id_utilizador_seguidor', $id_utilizador_seguidor);
			 $stmt->execute(); 

			 return true;
		}
		public function deixarSeguirUtilizador($id_utilizador_seguidor) {
			$query = "DELETE FROM seguidores
			 WHERE id_utilizador = :id_utilizador AND 
			 id_utilizador_seguidor = :id_utilizador_seguidor";  
			$stmt = $this->db->prepare($query); 
			$stmt->bindValue(':id_utilizador', $this->__get('id'));
			$stmt->bindValue(':id_utilizador_seguidor', $id_utilizador_seguidor);
			$stmt->execute(); 

			return true; 
		}
		//Informações do utilizador
		public function getInfoUtilizador() {
			$query = "SELECT nome FROM utilizadores WHERE id = :id_utilizador";
			$stmt = $this->db->prepare($query); 
			$stmt->bindValue(':id_utilizador', $this->__get('id'));
			$stmt->execute(); 

			return $stmt->fetch(\PDO::FETCH_ASSOC);
		}

		// Total de tweets
		public function getTotalTweets() {
			$query = "SELECT COUNT(*) AS total_tweets FROM tweets WHERE id_utilizador = :id_utilizador";
			$stmt = $this->db->prepare($query); 
			$stmt->bindValue(':id_utilizador', $this->__get('id'));
			$stmt->execute(); 

			return $stmt->fetch(\PDO::FETCH_ASSOC);
		}

		// Total de pessoas a seguir
		public function getTotalASeguir() {
			$query = "SELECT COUNT(*) AS total_seguir FROM seguidores WHERE id_utilizador = :id_utilizador";
			$stmt = $this->db->prepare($query); 
			$stmt->bindValue(':id_utilizador', $this->__get('id'));
			$stmt->execute(); 

			return $stmt->fetch(\PDO::FETCH_ASSOC);
		}

		// Total de seguidores
		public function getTotalSeguidores() {
			$query = "SELECT COUNT(*) AS total_seguidores FROM seguidores WHERE id_utilizador_seguidor = :id_utilizador";
			$stmt = $this->db->prepare($query); 
			$stmt->bindValue(':id_utilizador', $this->__get('id'));
			$stmt->execute(); 

			return $stmt->fetch(\PDO::FETCH_ASSOC);
		}

}

?>