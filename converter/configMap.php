<?php
require_once 'vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;
//Get the YAML component ready to use
class configMap
{

  protected $map_obj; //Allows you to interface with your YAML map
  protected $map_arr; //It is recommended to use the map_obj object to interact with your obejct
  protected $db_con; //Please use this object to fire your SQL Queries.
  //All Query processing is done as for as possible via Query Builder Objects, rather than firing direct SQL queries.
  //As for as possible please adhere to the same.
  //A Query Utilities file would be included for convinience.
  public $source_col=array();
  public $dest_col=array();
  protected $table_source;
  protected $table_destination;



  function __construct($con,$file)
  {
    //The constructor will initialize the constructor object, and intiitalize the mapping object map_obj to begin conversion
    $this->db_con = $con;
    //$con and thus $db_con are Doctrine\DBAL\DriverManager::getConnection() object. Basically the DBAL connection object.
    $file_link = $file;
    $this->map_arr = Yaml::parse(file_get_contents($file));
    $this->map_obj = (object)$this->map_arr;
    $this->set_source_and_dest_table();
    $this->set_source_and_dest_col();
    $this->copy_data_source_to_dest();


  }
  function set_source_and_dest_table()
  {
    $this->table_source=$this->map_obj->table_def['table_source'];
    $this->table_destination=$this->map_obj->table_def['table_destination'];
  }
  function set_source_and_dest_col()
  {
    for($i = 0; $i < count($this->map_obj->col_def); $i++)
    {
        array_push($this->source_col, $this->map_obj->col_def[$i]['col1']);
        array_push($this->dest_col, $this->map_obj->col_def[$i]['col2']);
    }
  }
  function get_source_and_dest_col()
  {
    print_r($this->source_col);
    echo '<br/>';
    print_r($this->dest_col);
  }
  function get_source_col()
  {
    return $this->source_col;
  }
  function get_dest_col()
  {
    return $this->dest_col;
  }

  function copy_data_source_to_dest()
  {
    $qb = $this->db_con->createQueryBuilder(); //Query Builder object;
    $qb->select($this->source_col)->from($this->table_source);
    $stmt = $qb->execute();
    while($each_row = $stmt->fetch())
    { //As every row is fetched keep inserting
      $values_row = array_values($each_row); //we just want the values and not coloumn names from row
      $insert_array = array_combine($this->dest_col, $values_row); //An array of dest-col names as keys and corresponding to be inserted values as pairs.
      $this->db_con->insert($this->table_destination, $insert_array);
      print_r("Succesfully completed"); //Debug

    }

  }






}


 ?>
