<?php
require_once __DIR__.'/converter/Converter.php';
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
$converter_obj = new Converter("phpBBgsoc","root", "123");
$converter_obj->begin_conversion('user_to_phpBB_user.yml');

?>
