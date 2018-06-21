<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="main.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
  <style>
    table,tr,td{
      border: solid black 1px;
    }
  </style>
</head>
<body>
<select id="region">
  <option value="us-east-1">US East (N. Virginia)</option>
  <option value="us-east-2">US East (Ohio)</option>
  <option value="us-west-1">US West (N. California)</option>
  <option value="us-west-2">US West (Oregon)</option>
  <option value="ca-central-1">Canada (Central)</option>
  <option value="eu-central-1">EU (Frankfurt)</option>
  <option value="eu-west-1">EU (Ireland)</option>
  <option value="eu-west-2" selected>EU (London)</option>
  <option value="eu-west-3">EU (Paris)</option>
  <option value="ap-northeast-1">Asia Pacific (Tokyo)</option>
  <option value="ap-northeast-2">Asia Pacific (Seoul)</option>
  <option value="ap-northeast-3">Asia Pacific (Osaka-Local)</option>
  <option value="ap-southeast-1">Asia Pacific (Singapore)</option>
  <option value="ap-southeast-2">Asia Pacific (Sydney)</option>
  <option value="ap-south-1">Asia Pacific (Mumbai)</option>
  <option value="sa-east-1">South America (São Paulo)</option>
</select><br>
<input type="checkbox" id="allTags">All tags<br>
Sort:
<button type="button" id="byName">By Name</button>
<button type="button" id="byNU">By NeededUntil</button>
<br>
Show:
<button type="button" id="validDates">Valid Dates</button>
<button type="button" id="invalidDates">Invalid Dates</button>
<button type="button" id="allDates">All Records</button>
<div id="dateDialog" title="ChangeDate">
  Instance: <span id="instanceId"></span><br>
  New Date:<input type="text" id="newDate">
  <input type="hidden" id="oldDate">
  <button type="button" id="changeDate">OK</button>
</div>
<div id="historyDialog" title="History of instance">
  <div id="historyLoader">Loading...</div>
  <table id="historyTable">
    <thead>
      <tr>
        <td>DateTime</td>
<!--        <td>IP</td>-->
        <td>Old</td>
        <td>New</td>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
</body>
</html>
