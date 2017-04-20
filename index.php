<?php
print "<link rel=stylesheet href=mf.css>";
print "<title>Module Feedback Questionaire</title>";
print "<div class='head'>";
print "<h1>Module Feedback</h1>";
if (!array_key_exists('u',$_REQUEST)){
  print "Enter your matriculation number: <form><input name=u value='50200036'><input type=submit></form>";
  exit();
}
#Validate users matriculation number
$con = new mysqli('localhost','40298406','AmZUq4ju','40298406');
if ($con->connect_error){
  die('Connection failure');
}
$sql = "select SPR_FNM1,SPR_SURN from INS_SPR where SPR_CODE=?"; 
$stmt = $con->prepare($sql)
  or die($con->error);
$stmt->bind_param('s',$_REQUEST['u'])
  or die('Bind error');
$stmt->execute()
  or die('Execute error');
$cur = $stmt->get_result();
if (!($row = $cur->fetch_array())){
  echo 'Invalid Matriculation Number';
  exit();
}
$stmt->close();

#Obtain previously selected answers if applicable
$sql = "select MOD_CODE,QUE_CODE, RES_VALU from INS_RES where SPR_CODE=?";
$stmt = $con->prepare($sql)
  or die($con->error);
$stmt->bind_param('s',$_REQUEST['u'])
  or die('Bind error');
$stmt->execute()
  or die('Execute error');
$cur = $stmt->get_result();
$userAnswers = $cur -> fetch_all();
$stmt->close(); 
array_push($userAnswers, 0);
$answersArray = array();
$tempC = '';
$tempQArray = array();
foreach($userAnswers as $a){
  if(strcmp($tempC,'') == 0){
    $tempC = $a[0];
  }
  if(strcmp($tempC,$a[0]) != 0){
    $answersArray[$tempC] = $tempQArray;
    $tempC = $a[0]; 
    $tempQArray = array();
  }
  $tempQArray[$a[1]] = $a[2];
}  

print "<div class='welcome'>Welcome Student: ".$row[0].' '.$row[1]." </div>";
print "<img id='logo' src='http://staff.napier.ac.uk/services/corporateaffairs/marketing/PublishingImages/EdNapUni_Logo_RGB.jpg' alt='Napier logo'>";
print "</div>";
#Obtain questions from database
$sql = "select QUE_CODE,QUE_TEXT from INS_QUE"; 
$stmt = $con->prepare($sql)
  or die($con->error);
$stmt->execute()
  or die('Execute error');
$cur = $stmt->get_result();
$qlist = $cur -> fetch_all();
$stmt->close();
#Get a list of modules
$sql = "select CAM_SMO.MOD_CODE,MOD_NAME,INS_MOD.PRS_CODE,PRS_FNM1,PRS_SURN
  from CAM_SMO join INS_MOD ON (CAM_SMO.MOD_CODE=INS_MOD.MOD_CODE) 
      left join INS_PRS ON (INS_MOD.PRS_CODE=INS_PRS.PRS_CODE) 
 where SPR_CODE=? AND AYR_CODE='2016/7' AND PSL_CODE='TR1'
";
$stmt = $con->prepare($sql)
  or die('bad SQL'.$con->error);
$stmt->bind_param('s',$_REQUEST['u'])
  or die('Bind error');
$stmt->execute()
  or die('Execute error');
$cur = $stmt->get_result();
print "<form action=store_fb.php>";
#Create fields for user to respond
print "<div class='wrapper'>";
while ($row = $cur->fetch_row()){
  print "<div class=module>";
  print "<h3>Please answer the following questions about $row[0].</h3>";
  print "<h4>5 = Strongly Agree, 4 = Mostly Agree, 3 = Neutral, 2 = Mostly Disagree, 1 = Strongly Disagree</h4>";
  print "<ul>";
  foreach($qlist as $q){
    $checked = $answersArray[$row[0]][$q[0]];
    print"<div class='question'>";
    print"<li>$q[1]</li>";
    print"<div class='button'>";
    if($checked == 5){
      print "
        <input type='radio' name='$row[0]_$q[0]' value='5' id='button5$row[0]$q[0]' checked/>
          <label for='button5$row[0]$q[0]'>5</label>";
    }
    else{
      print " 
        <input type='radio' name='$row[0]_$q[0]' value='5' id='button5$row[0]$q[0]'/>
          <label for='button5$row[0]$q[0]'>5</label>";
    }
    print"</div>";
    print"<div class='button'>";
    if($checked == 4){
      print " 
        <input type='radio' name='$row[0]_$q[0]' value='4' id='button4$row[0]$q[0]' checked/>
          <label for='button4$row[0]$q[0]'>4</label>";
    }
    else{
      print " <input type='radio' name='$row[0]_$q[0]' value='4' id='button4$row[0]$q[0]'/>
          <label for='button4$row[0]$q[0]'>4</label>";
    }
    print"</div>";
    print"<div class='button'>";
    if($checked == 3){
      print " <input type='radio' name='$row[0]_$q[0]' value='3' id='button3$row[0]$q[0]' checked/>
          <label for='button3$row[0]$q[0]'>3</label>";
    }
    else{
      print " <input type='radio' name='$row[0]_$q[0]' value='3' id='button3$row[0]$q[0]'/>
          <label for='button3$row[0]$q[0]'>3</label>";
    }
    print"</div>";
    print"<div class='button'>";
    if($checked == 2){
      print " <input type='radio' name='$row[0]_$q[0]' value='2' id='button2$row[0]$q[0]' checked/>
          <label for='button2$row[0]$q[0]'>2</label>";
    }
    else{
      print " <input type='radio' name='$row[0]_$q[0]' value='2' id='button2$row[0]$q[0]'/>
          <label for='button2$row[0]$q[0]'>2</label>";
    }
    print"</div>";
    print"<div class='button'>";
    if($checked == 1){
      print " <input type='radio' name='$row[0]_$q[0]' value='1' id='button1$row[0]$q[0]' checked/>
          <label for='button1$row[0]$q[0]'>1</label>";
    }
    else{
      print " <input type='radio' name='$row[0]_$q[0]' value='1' id='button1$row[0]$q[0]'/>
          <label for='button1$row[0]$q[0]'>1</label>";
    }
    print"</div>";
    print"</div>";
  }
  print"</ul>";
  print "</div>";
}
print "</div>";
print "<input type=hidden name=u value=$_REQUEST[u]>";
print "<div class='submit'><input type=submit></div>";
print "</form>";
$stmt->close();
