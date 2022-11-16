<?php
$conn=new mysqli("localhost","root","","codeigniter_db");
$query="select * from users";
$start=microtime(true);
$result =$conn->query($query);
$file='cache.php';

if(file_exists($file) && filemtime($file) > time()-10){
	 echo " coming from cache..";
	 include($file);
}else{
	echo " coming from database..";
$str = "<table border='2'>";
while($row=$result->fetch_assoc()){
	
	$str .='<tr><td>'.$row['name'].'</td></tr>';
}
$str .='</table>';
$handle=fopen($file,'w');
fwrite($handle,$str);
fclose($handle);
echo $str;
}
$end=microtime(true);
$calculate=round($end-$start);
echo $calculate;

?>