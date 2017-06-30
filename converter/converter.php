<?php
require_once 'vendor/autoload.php';
require_once __DIR__.'/config_map.php';
use Symfony\Component\Yaml\Yaml;

/**
 * Class converter
 */
class converter
{
	/**
	 * @var array
	 */
	protected $credentials_source;
	/**
	 * @var array
	 */
	protected $credentials_destination;
	/**
	 * @var \Doctrine\DBAL\Connection
	 */
	protected $con_source;
	/**
	 * @var \Doctrine\DBAL\Connection
	 */
	protected $con_destination;
	/**
	 * @var \Doctrine\DBAL\Configuration
	 */
	protected $config;
	/**
	 * @var array
	 */
	protected $yamlQ;

	/**
	 * converter constructor.
	 *
	 * @param        $dbname_source
	 * @param        $dbname_destination
	 * @param        $username
	 * @param        $dbpass
	 * @param string $dbdriver
	 * @param string $host
	 */
	function __construct($dbname_source, $dbname_destination, $username, $dbpass, $dbdriver='pdo_mysql', $host='localhost')
	{
		$this->config = new Doctrine\DBAL\Configuration();
		$this->yamlQ = array();
		$this->credentials_source=array( //Set up the credentianls and generate a connection object for future use.
		  'dbname'=>$dbname_source,
		  'user'=>$username,
		  'password'=>$dbpass,
		  'host'=>$host,
		  'driver'=>$dbdriver,
		);
		$this->credentials_destination=array( //Set up the credentianls and generate a connection object for future use.
		  'dbname'=>$dbname_destination,
		  'user'=>$username,
		  'password'=>$dbpass,
		  'host'=>$host,
		  'driver'=>$dbdriver,
		);
		$this->con_source = \Doctrine\DBAL\DriverManager::getConnection($this->credentials_source,$this->config);
		$this->con_destination = \Doctrine\DBAL\DriverManager::getConnection($this->credentials_destination,$this->config);
		//var_dump($this->con);
		$this->begin_conversion('user_to_phpBB_user.yml');
	}

	/**
	 *Queue Builder Function to get a Queue of YAML files to process.
	 */
	function build_process_queue()
	{

		/*Hardcode file name as conversionQ.yml*/
		$this->yamlQ = Yaml::parse(file_get_contents('conversionQ.yml'));
		print_r($this->yamlQ);
	}

	/**
	 * @param $file
	 * Function responsible for starting the conversion by generating the configMap object.
	 * This function will be wrapped over by another function to process every yaml class from yamlQ.
	 * Since we havent created a Q system, we will just be using this function for now.
	 */
	function begin_conversion($file)
	{

		$cf = new config_map($this->con_source, $this->con_destination ,$file);
		$total_records = $cf->get_total_records();
		$length = ($total_records/100);
		echo $total_records;
		echo '<br/>';
		for($i=0; $i<$length; $i++)
		{
			$cf->copy_data($i);
		}
		print_r("Succesfully completed");


	}
}

