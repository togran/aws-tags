<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="main.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
</head>
<body class="p-3">
<div class="row">
  <div class="col"></div>
  <div class="col-8 main-content">
<div>
Select region:
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
</div>
<input type="checkbox" id="allTags">All tags<br>
  <div class="row">
<!--    <div class="col col-md-1">-->
<!--Sort:-->
<!--    </div>-->
<!--  <div class="col">-->
<!--  <div class="btn-group py-1">-->
<!--    <button type="button" id="byName" class="btn btn-primary">By Name</button>-->
<!--    <button type="button" id="byNU" class="btn btn-primary">By NeededUntil</button>-->
<!--  </div>-->
<!--  </div>-->
<!--<div class="w-100"></div>-->
    <div class="col col-md-1">
Show:
    </div>
    <div class="col">
  <div class="btn-group py-1">
<button type="button" id="validDates" class="btn btn-primary">Valid Dates</button>
<button type="button" id="invalidDates" class="btn btn-primary">Invalid Dates</button>
<button type="button" id="allDates" class="btn btn-primary">All Records</button>
  </div>
    </div>
</div>
<div id="dateDialog" title="ChangeDate">
  Instance: <span id="instanceId"></span><br>
  New Date:<input type="text" id="newDate">
  <input type="hidden" id="oldDate">
  <button type="button" id="changeDate">OK</button>
</div>
<div id="historyDialog" title="History of instance">
  <div id="historyLoader">Loading...</div>
  <table id="historyTable" class="table-bordered">
    <thead>
      <tr>
        <td>DateTime</td>
        <td>IP</td>
        <td>Old</td>
        <td>New</td>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
</div>
  <div class="col"></div>
</div>
</body>
</html>
