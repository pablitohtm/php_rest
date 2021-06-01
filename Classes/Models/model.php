<?php

namespace Classes\Models;
use \PDO;

class Model
{
    protected $con;
    protected $table;
    
    public function __construct($con){
        $this->con = $con;
    }

    public function getAll(){
        try{
            
            $query = "SELECT * FROM " . $this->table;
            $stm = $this->con->query($query);

            if($stm->rowCount() > 0){
                return json_encode($stm->fetchAll());    
            }else{
				http_response_code(400);
                return json_encode(['status' => 'warning', 'data' => 'nenhum registro encontrado!']);
            }
        }catch(PDOException $e){
            http_response_code(404);
            return json_encode(array('status' => 'error', 'data' => $e));
        }
    }

    public function get($id){
        try{
            
            $query = "SELECT * FROM " . $this->table . ' WHERE id = '.$id;
            $stm = $this->con->query($query);

            if($stm->rowCount() > 0){
                return json_encode($stm->fetch());    
            }else{
                return json_encode(array('status' => 'warning', 'data' => 'nenhum registro encontrado!'));
            }
        }catch(PDOException $e){
            http_response_code(404);
            return json_encode(array('status' => 'error', 'data' => $e));
        }
    }


}
?>