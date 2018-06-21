<?php
/**
 * Created by PhpStorm.
 * User: Togran
 * Date: 21.06.2018
 * Time: 10:31
 */
require 'aws/aws-autoloader.php';
include 'creds.php';
use Aws\Ec2\Ec2Client;

$mysqli = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
if (!$mysqli) {
  echo "Error: Unable to connect to MySQL." . PHP_EOL;
  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
  exit;
}

function say($text)
{
  global $slack_url;
  $data = array(
    'text' => $text
  );
  $data_string = json_encode($data);
  $ch = curl_init($slack_url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data_string))
  );
//Execute CURL
  $result = curl_exec($ch);
}

$ec=Aws\Ec2\Ec2Client::factory([
  'version' => 'latest',
  'region'  => (isset($_REQUEST['region'])?$_REQUEST['region']:'eu-west-2'),
  'credentials' => $creds
]);

$res = $ec->describeInstances(['DryRun' => false, 'Filters'=>[['Name'=>'tag-key','Values'=>['NeededUntil']]]])->get('Reservations');
foreach($res as $v) {
  foreach($v['Instances'] as $instance) {
    foreach($instance['Tags'] as $tag) {
//      echo($instance['InstanceId'].": ".$tag['Key']."=".$tag['Value']."\n");
      if($tag['Key']=='NeededUntil'){
//        var_dump(date(strtotime($tag['Value'])));
        $t=date(strtotime($tag['Value']));
        if($t!='' && $t<=date(strtotime('tomorrow'))){
          $stmt=$mysqli->prepare('SELECT id FROM alerts WHERE instance_id=? and date=?');
          $stmt->bind_param('ss', $instance['InstanceId'], $tag['Value']);
          $stmt->execute();
          $stmt->store_result();
          var_dump($stmt->num_rows());
          if($stmt->num_rows()==0) {
            $stmt->prepare('INSERT INTO alerts set instance_id=?, date=?');
//            echo $mysqli->sqlstate;die();
            $stmt->bind_param('ss', $instance['InstanceId'], $tag['Value']);
            $stmt->execute();
//            echo($instance['InstanceId'] . ": " . $tag['Key'] . "=" . $tag['Value'] . "\n");
            say('Warning! Instance '.$instance['InstanceId'].' will expire on '.$tag['Value'].'. Please update "NeededUntil" tag if needed ');
          }
        }
      }
    }
  }
}
