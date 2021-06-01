<?php

use Classes\Tools\Routes;
use Classes\Models\Vendedor;
use Classes\Models\Venda;

class Rest
{
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    public function consomeRest(){

        if(count(Routes::getUrls()) > 0){

            $urls = Routes::getUrls();
            if(file_exists(__DIR__ . '/Models/' . $urls[1]).'.php'){
                $model_name = ucfirst(Routes::getUrls()[1]);
                $model = new ('Classes\\Models\\' . $model_name)($this->con);     
                
                switch ( strtoupper(Routes::getMethod()) ){
                        case 'GET':
                            if(isset($urls[2])){
                                if(isset($urls[3])){
                                    if(filter_var($urls[2], FILTER_VALIDATE_INT)){
                                        echo $model->getVendas($urls[2]);
                                    }else{
                                        http_response_code(404);
                                        return json_encode(array('status' => 'error', 'data' => 'endpoint não encontrado!'));
                                    }
                                }else if(filter_var($urls[2], FILTER_VALIDATE_INT)){
                                    echo $model->get($urls[2]);
                                }else{
                                    http_response_code(404);
                                    return json_encode(array('status' => 'error', 'data' => 'endpoint não encontrado!'));
                                }
                            }else{
                                echo $model->getAll();
                            }
                            break;

                        case 'POST':
                            echo $model->post($_POST);
                            break;
						case 'PUT':
							if($model_name == "Venda"){
								echo $model->put(Rest::getParams(array('valor','comissao','vendedor_id','id')));
							}else if ($model_name = "Vendedor"){
								echo $model->put(Rest::getParams(array('nome','email','id')));
							}
							break;
						case 'DELETE':
							if($model_name == "Venda"){
								echo $model->delete(Rest::getParams(array('id')));
							}else if ($model_name == "Vendedor"){
								echo $model->delete(Rest::getParams(array('id')));
							}
							
							break;	
                }

            }else{
                http_response_code(404);
                return json_encode(array('status' => 'error', 'data' => 'endpoint não encontrado!'));
            }
        }
    }
	
	public function getParams($arr){
		$inputFileSrc = 'php://input';
		$lines = file($inputFileSrc);
		$data = [];

		foreach($arr as $k =>  $arrr){
			foreach($lines as $i =>  $line){
				$search = 'Content-Disposition: form-data; name="'.$arrr.'"';
				if(strpos($line, $search) !== false){
					$data[$arrr]  = trim($lines[$i+2]);
				}
			}
		}
		return $data;
	}
}