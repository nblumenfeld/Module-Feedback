<?php
if(!array_key_exists('MOD_CODE', $_REQUEST)){
  print"<form><input name=MOD_CODE value=SET08108></form>";
  exit();
}
$con = new mysqli('localhost','40298406','AmZUq4ju','40298406');
if($con->connect_error){
  die('Connection failure');
}
$sql = "
SELECT QUE_CODE, 
   100 * AVG(CASE WHEN RES_VALU IN (4,5) THEN 1 ELSE 0 END) AS v
  FROM INS_RES
  WHERE MOD_CODE = ?
GROUP BY QUE_CODE
ORDER BY QUE_CODE
";
$stmt = $con->prepare($sql)
  or die($con->error);
$stmt->bind_param('s',$_REQUEST['MOD_CODE'])
  or die('Bind error');
$stmt->execute();
$res = $stmt->get_result()
  or die('get_result failed: '.$con->error);
print json_encode($res->fetch_all());

