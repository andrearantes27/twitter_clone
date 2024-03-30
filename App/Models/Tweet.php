<?php

namespace App\Models;
use MF\Model\Model;

class Tweet extends Model {
    private $id; 
    private $id_utilizador; 
    private $tweet; 
    private $data;

    public function __get($atributo) {
		return $this->$atributo; 
	}
    public function __set($atributo, $valor) {
    	$this->$atributo = $valor;
    }
    // Salvar
    public function salvar() {
        $query = "INSERT INTO tweets(id_utilizador, tweet)  VALUES (:id_utilizador, :tweet)";
       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':id_utilizador', $this->__get('id_utilizador'));
       $stmt->bindValue(':tweet', $this->__get('tweet'));  
       $stmt->execute(); 
    	return $this; 
    }

    // Recuperar
    public function getAll() {
        $query = "SELECT t.id, t.id_utilizador, u.nome, t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') AS data
        FROM tweets AS t 
        LEFT JOIN utilizadores as u ON(t.id_utilizador = u.id)
        WHERE t.id_utilizador = :id_utilizador
        OR t.id_utilizador IN (SELECT id_utilizador_seguidor 
        FROM seguidores
        WHERE :id_utilizador)
        ORDER BY t.data DESC"; 
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_utilizador', $this->__get('id_utilizador'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }
    /*
    // Recuperar com paginação
    public function getPorPagina($limit, $offset) {
        $query = "SELECT t.id, t.id_utilizador, u.nome, t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') AS data
        FROM tweets AS t 
        LEFT JOIN utilizadores as u ON(t.id_utilizador = u.id)
        WHERE t.id_utilizador = :id_utilizador
        OR t.id_utilizador IN (SELECT id_utilizador_seguidor 
        FROM seguidores
        WHERE :id_utilizador)
        ORDER BY t.data DESC
        limit 
        $limit 
        offset 
        $offset"; 
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_utilizador', $this->__get('id_utilizador'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }
    */

      // Recuperar total de tweets
      /*
      public function getTotalRegistos() {
        $query = "SELECT count(*) as total FROM tweets as t
        LEFT JOIN utilizadores as u ON(t.id_utilizador = u.id)
        WHERE t.id_utilizador = :id_utilizador
        OR t.id_utilizador IN (SELECT id_utilizador_seguidor 
        FROM seguidores
        WHERE :id_utilizador)"; 
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_utilizador', $this->__get('id_utilizador'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }
    */
    public function remover(){
        $query = "DELETE FROM tweets WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id',$this->__get('id'));
        $stmt->execute();
        return true;
    }
    

       }

?>