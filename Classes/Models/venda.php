<?php

namespace Classes\Models;
use Classes\Models\Vendedor;

class Venda extends Model
{
    public function __construct($con){
        $this->table = 'vendas';
        parent::__construct( $con );
    }

    public function post($data){
        try {

            if(!isset($data['vendedor_id'])){
                http_response_code(400);
                return json_encode(array('status' => 'error','data' => 'Informe o Vendedor!'));
            }else{
                $vendedor = new Vendedor($this->con);
                $vendedor = json_decode($vendedor->get($data['vendedor_id']));

                if(isset($vendedor->status)){
                    http_response_code(400);
                    return json_encode(array('status' => 'error','data' => 'Vendedor não encotrado na Base de Dados!'));
                }
            }

            if(!isset($data['valor'])){
                http_response_code(400);
                return json_encode(array('status' => 'error','data' => 'Valor informado incorretamente!'));
            }else{
                if(isset($data['valor']) === '' || $data['valor'] <= 0){
                    http_response_code(400);
                    return json_encode(array('status' => 'error', 'data' => 'Valor informado incorretamente!'));
                }
            }

            $query = $this->con->prepare('INSERT INTO ' . $this->table . ' (vendedor_id, valor, comissao, data) VALUES(:vendedor_id,:valor,:comissao,NOW())');
            $query = $query->execute(
                        array(
                            ':vendedor_id' => $data['vendedor_id'], 
                            ':valor' => $data['valor'], 
                            ':comissao' => !isset($data['comissao']) || $data['comissao'] === '' ? 8.50 : $data['comissao'] 
                        )
                    );
            
            if($query){

                $query = "SELECT * FROM " . $this->table . ' ORDER BY ID DESC LIMIT 1';
                $stm = $this->con->query($query);

                if($stm->rowCount() > 0){
                    $rest = $stm->fetchAll();    
                }

                return json_encode( array( 'status' => 'success',  'data' => [ 'message' => 'Venda inserida com sucesso',  'res' => $rest ] ) );
            }
			http_response_code(404);
            return json_encode(
                array( 'status' => 'error', 'data' => 'Erro ao inserir um novo Vendedor!' )
            );
        }catch(PDOException $e){
            http_response_code(404);
            return json_encode( array( 'status' => 'error', 'data' => $e ) );
        }
    }
	
	public function put($array){
        try{

			$query = $this->con->prepare('UPDATE ' . $this->table . ' SET vendedor_id = "' . $array['vendedor_id'] . '", valor = "' . $array['valor'] . '", comissao = "' . $array['comissao'] . '" WHERE id = :id');
            $query = $query->execute( array(':id' => $array['id']) );
            
            if($query){
                return json_encode( array( 'status' => 'success', 'data' => [ 'message' => 'Venda alterado com sucesso', 'res' => $array['id']] ) );
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
                return json_encode( array( 'status' => 'success', 'data' => [ 'message' => 'Venda excluida com sucesso', 'res' => $array['id']] ) );
            }
			
        }catch(PDOException $e){
            http_response_code(404);
            return json_encode(array('status' => 'error', 'data' => $e));
        }
    }

}
