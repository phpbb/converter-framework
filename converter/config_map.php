<?php
require_once 'vendor/autoload.php';
require_once 'util_conversion_functions.php';
use Symfony\Component\Yaml\Yaml;

//Get the YAML component ready to use

class config_map
{
    protected $data_map; //Allows you to interface with your YAML map
    protected $data_map_arr; //It is recommended to use the data_map object to interact with your obejct
    protected $db_source; //Please use this object to fire your SQL Queries.
    //All Query processing is done as for as possible via Query Builder Objects, rather than firing direct SQL queries.
    //As for as possible please adhere to the same.
    protected $db_destination;
    //A Query Utilities file would be included for convinience.
    public $source_col = array();
    public $dest_col = array();
    public $conversion_function = array(); //Array to hold function to be applied on source data. ie $dest=func($source)
    protected $table_source;
    protected $table_destination;

    public function __construct($con_source, $con_destination, $file)
    {
        //The constructor will initialize the constructor object, and intiitalize the mapping object data_map to begin conversion
        $this->db_source = $con_source;
		$this->db_destination = $con_destination;
        //$con and thus $db are Doctrine\DBAL\DriverManager::getConnection() object. Basically the DBAL connection object.
        $file_link = $file;
        $this->data_map_arr = Yaml::parse(file_get_contents($file));
        $this->data_map = (object)$this->data_map_arr;
        $this->set_source_and_dest_table();
        $this->set_source_and_dest_col();
        //$this->get_conversion_function();
        $this->copy_data_source_to_dest();
    }

    public function set_source_and_dest_table()
    {
        $this->table_source = $this->data_map->table_def['table_source'];
        $this->table_destination = $this->data_map->table_def['table_destination'];
    }

    public function set_source_and_dest_col()
    {
        for ($i = 0; $i < count($this->data_map->col_def); $i++) {
            array_push($this->source_col, $this->data_map->col_def[$i]['col1']);
            array_push($this->dest_col, $this->data_map->col_def[$i]['col2']);
            //Check if 'function' key exists
            if (array_key_exists('function', $this->data_map->col_def[$i])) {
                array_push($this->conversion_function, $this->data_map->col_def[$i]['function']);
            } else {
                array_push($this->conversion_function, null);//simulates a NULL
            }
        }
    }

    public function get_source_and_dest_col()
    {
        print_r($this->source_col);
        echo '<br/>';
        print_r($this->dest_col);
    }

    public function get_source_col()
    {
        return $this->source_col;
    }

    public function get_dest_col()
    {
        return $this->dest_col;
    }

    public function get_conversion_function()
    {
        print_r($this->conversion_function);
        echo '<br/>';
        for ($i=0; $i < count($this->conversion_function); $i++) {
            if ($this->conversion_function[$i]!=null) {
                print_r($this->conversion_function[$i]('dummy_val'));
                echo '<br/>';
            }
        }
    }

    public function copy_data_source_to_dest()
    {
        $query_source = $this->db_source->createQueryBuilder(); //Query Builder object;
        $query_source->select($this->source_col)->from($this->table_source);
        $stmt_source = $query_source->execute();
        while ($each_row = $stmt_source->fetch()) { //As every row is fetched keep inserting
           $values_row = array(); //Holds final converted values
           $values_orig_row = array_values($each_row); //we just want the values and not coloumn names from row
          //Apply conversion functions to $values_orig_row
           for ($i = 0; $i < count($values_orig_row); $i++) {
               if ($this->conversion_function[$i] == null) { // == used since 0, '', NULL must all get treated same
                  array_push($values_row, $values_orig_row[$i]);
               } else {
                   array_push($values_row, $this->conversion_function[$i]($values_orig_row[$i]));
               }
           }
            $insert_array = array_combine($this->dest_col, $values_row); //An array of dest-col names as keys and corresponding to be inserted values as pairs.
           $this->db_destination->insert($this->table_destination, $insert_array);
            print_r("Succesfully completed"); //Debug
        }
    }
}
