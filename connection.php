<?php
require_once __DIR__.'/vendor/autoload.php';
$config=new Doctrine\DBAL\Configuration();
$cred=array(
  'dbname'=>'dbExam1',
  'user'=>'root',
  'password'=>'123',
  'host'=>'localhost',
  'driver'=>'pdo_mysql',
);
$con= \Doctrine\DBAL\DriverManager::getConnection($cred,$config);
$qb=$con->createQueryBuilder();
$col=array('ssn','profname');
$qb->select($col)->from('Professor');
$stmt=$qb->execute();
//$prof=$con->fetchAll('SELECT * FROM Professor');
$qb2=$con->createQueryBuilder();

//$qb2->insert('copy')->values(array('id'=>'?','name'=>'?'))->setParameter(0,1)->setParameter(1,'Bala');
//$qb2->execute();

while($row=$stmt->fetch()){
  $qb2=$con->createQueryBuilder();
  $col2=array('id','name');
  $values=array_values($row);
  $insertArray=array_combine($col2,$values);
  $con->insert('copy',$insertArray);
  print_r($insertArray);
  echo '<br/>';
  // $qb2->insert('copy')->values($insertArray);
  // $qb2->execute();

}


 ?>
