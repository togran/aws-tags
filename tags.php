<?php
/**
 * Created by PhpStorm.
 * User: Togran
 * Date: 20.06.2018
 * Time: 14:42
 */
require 'aws/aws-autoloader.php';
include 'creds.php';
use Aws\Ec2\Ec2Client;

$ec=Aws\Ec2\Ec2Client::factory([
  'version' => 'latest',
  'region'  => (isset($_REQUEST['region'])?$_REQUEST['region']:'eu-west-2'),
  'credentials' => $creds
]);

if($_REQUEST['action']=='list') {
  $res = $ec->describeInstances(['DryRun' => false])->get('Reservations');
  $instances = [];
  $keys = [];
  foreach($res as $v) {
//  var_dump($v['Instances'][0]['Tags']);
//  var_dump($v);
    foreach($v['Instances'] as $instance) {
      foreach($instance['Tags'] as $tag) {
        $instances[$instance['InstanceId']][$tag['Key']] = $tag['Value'];
        $keys[$tag['Key']] = 1;
      }
    }
  }
//var_dump($instances);
  echo(json_encode(['instances' => $instances, 'keys' => $keys]));
}else{
  $mysqli = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
  if (!$mysqli) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
  }
}

if($_REQUEST['action']=='newDate') {
  $ec->createTags([
    'Resources'=>[$_REQUEST['instance']],
    'Tags'=>[[
      'Key'=>'NeededUntil',
      'Value'=>$_REQUEST['newDate']
    ]]
  ]);
  $stmt = $mysqli->prepare("INSERT INTO logs(ip, instance_id, prev, new) VALUES (?, ?, ?, ?)");
  $stmt->bind_param('ssss', $_SERVER['REMOTE_ADDR'], $_REQUEST['instance'], $_REQUEST['oldDate'], $_REQUEST['newDate']);
  $stmt->execute();
  echo($stmt->error);
  $stmt->close();
  $mysqli->close();
}

if($_REQUEST['action']=='history') {
  $res=[];
  if ($stmt = $mysqli->prepare("SELECT ts, ip, prev, new FROM logs where instance_id=? ORDER BY ts DESC")) {
    $stmt->bind_param('s', $_REQUEST['instance']);
    $stmt->execute();
    $stmt->bind_result($ts, $ip, $prev, $new);
    while ($stmt->fetch()) {
      $res[]=[$ts, $ip, $prev, $new];
    }
    $stmt->close();
  }
  $mysqli->close();
  echo(json_encode($res));
}
?>