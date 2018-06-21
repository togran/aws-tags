# aws-tags

This simple webpage shows instances in a selected region among with their tags.
You can edit "NeededUntil" tag and see its history.
Also script will send a message in a Slack channel if an instance is about to expire

##Requirements
Some web-server(tested with apache but nginx should be fine too)

PHP > 5.6

MySQL

##Installation
Just put the files in your web-folder, import .sql file to your MySQL and create creds.php with your credentials.

Sample creds.php:
```
<?php
$creds=[
  'key'    => getenv('AWS_KEY'),
  'secret' => getenv('AWS_SECRET'),
];
$slack_url=getenv('slack_url');
$mysql_host=getenv('mysql_host');
$mysql_user=getenv('mysql_user');
$mysql_db=getenv('mysql_db');
$mysql_pass=getenv('mysql_pass');
?>
```

For Slack warnings you should also add the line to your /etc/crontab:
```
0 12    * * *   www-data    /usr/bin/php /path/to/your/cron.php
```
This will run checks every noon 