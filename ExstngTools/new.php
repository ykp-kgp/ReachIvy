<?php
// $result = exec("/usr/bin/python main.py /tmp");
// echo $result;
// $result_array = json_decode($result);
// foreach ($result_array as $key => $value) {
// 	echo '<br>'.$key.' : '.$value;
// }

//another way
$command = escapeshellcmd('python main.py');
$output = shell_exec($command.' 2>&1');
echo $output;

$result_array = json_decode($output);
foreach ($result_array as $key => $value) {
	echo '<br>'.$key.' : '.$value;
}

?>