<?php
require_once __DIR__.'/converter/converter.php';
require_once __DIR__.'/converter/converter_logger.php';
require_once __DIR__.'/converter/util_conversion_functions.php';
// require_once __DIR__.'/vendor/autoload.php';
// use Symfony\Component\Yaml\Yaml;
// $map=Yaml::parse(file_get_contents('user_to_phpBB_user.yml'));
// $map_obj=(object)$map;
// $source_col=array();
// $dest_col=array();
// for($i=0; $i<count($map_obj->col_def); $i++)
// {
//     array_push($source_col,$map_obj->col_def[$i]['col1']);
// }
// print_r($source_col);
// echo '<br/>'.$map_obj->table_def['table_source'];
$converter_obj = new converter("phpBBgsoc", "phpBBgsoc_dest", "root", "123");
//$converter_obj->build_process_queue();
// $logger = new converter\cl\converter_logger();
// $logger->conversion_start();
// $logger->conversion_end();


//echo '['.date('j-n-Y \a\t h:i:s A').']';

?>
