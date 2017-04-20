<?php
print "<title>Thank you for your feedback!</title>";
if (!array_key_exists('u',$_REQUEST)){
  die("Who are you");
}

$con = new mysqli('localhost','40298406','AmZUq4ju','40298406');
if ($con->connect_error){
  die('Connection failure');
}
# Delete all records before inserting new ones - probaby a bad decision
$sql = "delete from INS_RES where SPR_CODE=? AND AYR_CODE='2016/7' AND PSL_CODE='TR1'";
$stmt = $con->prepare($sql)
  or die($con->error);
$stmt->bind_param('s',$_REQUEST['u'])
  or die('Bind error');
$stmt->execute()
  or die('Failed to delete'.$con->error);
$stmt -> close();
$sql = "insert into INS_RES VALUES(?,?,'2016/7','TR1',?,?)"; 
$stmt = $con->prepare($sql)
  or die($con->error);
$stmt->bind_param('sssi',$_REQUEST['u'],$mc,$qn,$res)
  or die('Bind error');
foreach($_REQUEST as $k => $v){
  if($k == 'u'){
    continue;
  }
  $mc_qn = preg_split('/_/',$k);
  $mc = $mc_qn[0];
  $qn = $mc_qn[1].'.'.$mc_qn[2];
  $res = $v;
  $stmt->execute()
    or die("Execute error: $qn ".$con->error);
}
$stmt -> close();
print "<div> Thank you for your feedback! </div>";
