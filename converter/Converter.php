<?php
require_once 'vendor/autoload.php';
require_once __DIR__.'/configMap.php';

class Converter{
  protected $credentials;
  protected $con;
  protected $config;
  protected $yamlQ;

  function __construct($dbname, $username, $dbpass, $dbdriver='pdo_mysql', $host='localhost')
  {
    $this->config = new Doctrine\DBAL\Configuration();
    $this->yamlQ = array();
    $this->credentials=array( //Set up the credentianls and generate a connection object for future use.
      'dbname'=>$dbname,
      'user'=>$username,
      'password'=>$dbpass,
      'host'=>$host,
      'driver'=>$dbdriver,
    );
   $this->con = \Doctrine\DBAL\DriverManager::getConnection($this->credentials,$this->config);
   //var_dump($this->con);
  // $this->begin_conversion('user_to_phpBB_user.yml');
  }

  function buildProcessQueue()
  { //Quee Builder Function to get a Quee of YAML files to process.
    $this->yamlQ = new yamlQueue();
  }

  function begin_conversion($file)
  {
    //Function responsible for starting the conversion by generating the configMap object.
    //This function will be wrapped over by another function to process every yaml class from yamlQ;
    //Since we havent created a Q system, we will just be using this function for now.
    $cf = new configMap($this->con,$file);

  }





}

 ?>
