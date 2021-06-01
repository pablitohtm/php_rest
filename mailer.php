<?php
require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/autoload.php';

use Classes\Connection\Dbc;

if(!isset($con)){
    $con = new Dbc();
    $con = $con->getConnection();
}
       
$query = 'SELECT SUM(v.valor) AS valor, SUM(v.valor * v.comissao) AS comissao  FROM vendas v WHERE DATE_FORMAT(v.data,"%Y%m%d") = DATE_FORMAT(NOW(), "%Y%m%d")';
$stm = $con->query($query)->fetch();

if(count($stm) > 0){
  $vendas_string = "Valores recebidos ".$stm['valor'].", pago ".$stm['comissao']." de comissão, valores computados no dia ".date('d/m/Y');
}else{
  $vendas_string = "Nenhuma venda computada no dia ".date('d/m/Y');
}

$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587))
  ->setUsername('pablito.htm.dev@gmail.com')
  ->setPassword('hugodev!@##')
  ->setEncryption('tls')
;

$mailer = new Swift_Mailer($transport);

$message = (new Swift_Message('Balancete'))
  ->setFrom(['pablito.htm@gmail.com' => 'Dr. Hugo'])
  ->setTo(['pablito.htm@gmail.com', 'pablito.htm@gmail.com' => 'Hugo Pablo'])
  ->setBody($vendas_string);

$result = $mailer->send($message);
