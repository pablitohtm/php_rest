<?php

namespace Classes\Models;

class Vendedor extends Model
{
    public function __construct($con){
        $this->table = 'vendedor';
        parent::__construct( $con );
    }

    public function post($data){
        try {

            if($data['nome'] === ''){
                http_response_code(400);
                return json_encode( array( 'status' => 'error',  'data' => 'Campo nome informado incorretamente!'));
            }
            if($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                http_response_code(400);
                return json_encode( array(   'status' => 'error',  'data' => 'Campo e-mail informado incorretamente!'));
            }

            $query = $this->con->prepare('INSERT INTO ' . $this->table . ' (nome, email) VALUES(:nome,:email)');
            $query = $query->execute( array(':nome' => $data['nome'], ':email' => $data['email'] ));
            
            if($query){

                $query = "SELECT * FROM " . $this->table . ' ORDER BY ID DESC LIMIT 1';
                $stm = $this->con->query($query);

                if($stm->rowCount() > 0){
                    $rest = $stm->fetchAll();    
                }

                return json_encode( array( 'status' => 'success', 'data' => [ 'message' => 'Vendedor inserido com sucesso', 'res' => $rest] ) );
            }

            http_response_code(404);
            return json_encode( array( 'status' => 'error', 'data' => 'Erro ao inserir um novo Vendedor!' ) );

        }catch(PDOException $e){
            http_response_code(404);
            return json_encode( array( 'status' => 'error', 'data' => $e ) );
        }
    }

    public function put($array){
        try{

			$query = $this->con->prepare('UPDATE ' . $this->table . ' SET nome = "' . $array['nome'] . '", email = "' . $array['email'] . '" WHERE id = :id');
            $query = $query->execute( array(':id' => $array['id']) );
            
            if($query){
                return json_encode( array( 'status' => 'success', 'data' => [ 'message' => 'Vendedor alterado com sucesso', 'res' => $array['id']] ) );
            }
			
        }catch(PDOException $e){
            http_response_code(404);
            return json_encode(array('status' => 'error', 'data' => $e));
        }
    }
	
	public function delete($array){
        try{

			$query = $this->con->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
            $query = $query->execute( array(':id' => $array['id']) );
            
            if($query){
                return json_encode( array( 'status' => 'success', 'data' => [ 'message' => 'Vendedor excluido com sucesso', 'res' => $array['id']] ) );
            }
			
        }catch(PDOException $e){
            http_response_code(404);
            return json_encode(array('status' => 'error', 'data' => $e));
        }
    }
	
	 public function getVendas($id){
        try{

            $vendedor = 'SELECT * FROM ' . $this->table . ' WHERE id = '.$id;
            $res_vendedor = $this->con->query($vendedor);

            if($res_vendedor->rowCount() > 0){

                $vendas = 'SELECT v.*, (v.valor * v.comissao) as valor_comissao FROM vendas v WHERE vendedor_id = '.$id;
                $res_vendas = $this->con->query($vendas);

                if($res_vendas->rowCount() > 0){
                    return json_encode(['vendedor' => $res_vendedor->fetch(), 'vendas' => $res_vendas->fetchAll()]);  
                }else{
                    return json_encode(array('status' => 'warning', 'data' => 'nenhuma Venda para este Vendedor encontrada!'));
                }  
            }else{
                return json_encode(array('status' => 'warning', 'data' => 'nenhum Vendedor encontrado!'));
            }
        }catch(PDOException $e){
            http_response_code(404);
            return json_encode(array('status' => 'error', 'data' => $e));
        }
    }

}
