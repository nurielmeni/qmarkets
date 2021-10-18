<?php

require_once('Model.php');

$model = new Model();
echo "Connected successfully";
$res = $model->execute('SELECT * FROM `playground.demo_profile_values` LIMIT 10');
printf("Select returned %d rows.\n", $res->num_rows);

?>