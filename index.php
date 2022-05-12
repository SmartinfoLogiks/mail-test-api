<?php
$arrParams = [
  "smtp_host"=> [

  ],
  "smtp_port"=> [

  ],
  "smtp_auth"=> [

  ],
  "smtp_secure"=> [

  ],
  "smtp_username"=> [

  ],
  "smtp_password"=> [

  ],
  "default_from"=> [

  ],
  "debug"=>[

  ],
  "to"=> [

  ],
  "subject"=> [

  ],
  "body"=> [

  ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Mailtest Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<style>
h2 {
  text-align: center;
  margin-bottom: 25px;
}
.form-group {
  height: 25px;
}
label {
  line-height: 34px;
}
</style>
</head>
<body>

<div class="container">
  <h2>MAIL Testing Form</h2>
  <form action="./push.php" method='POST' enctype="multipart/form">
    <?php
      foreach($arrParams as $key=>$conf) {
        $title = strtoupper(str_replace("_", " ", $key));
        $inputField = "<input type='text' class='form-control' name='{$key}' placeholder='ENTER {$title}' required>";

        switch($key) {
          case "subject":
            $inputField = "<input type='text' class='form-control' name='{$key}' placeholder='ENTER {$title}' required value='Test Email'>";
          break;
          case "body":
            $inputField = "<textarea class='form-control' name='{$key}' placeholder='ENTER {$title}'></textarea>";
          break;
          case "smtp_port":
            $inputField = "<input type='text' class='form-control' name='{$key}' placeholder='ENTER {$title}' list='portList' value='587' required>";
            $inputField.="<datalist id='portList'>";
            $inputField.="<option value='587'>";
            $inputField.="<option value='25'>";
            $inputField.="<option value='465'>";
            $inputField.="<option value='110'>";
            $inputField.="</datalist>";
          break;
          case "smtp_auth":
            $inputField = "<select class='form-control select' name='{$key}'>".
                "<option value='true'>True</option>".
                "<option value='false'>False</option>".
                "</select>";
          break;
          case "smtp_secure":
            $inputField = "<select class='form-control select' name='{$key}'>".
                "<option value='tls'>tls</option>".
                "<option value='tls2'>tls2</option>".
                "<option value='ssl'>ssl</option>".
                "<option value=''>none</option>".
                "</select>";
          break;
          case "debug":
            $inputField = "<select class='form-control select' name='{$key}'>".
                "<option value='false'>False</option>".
                "<option value='true'>True</option>".
                "</select>";
          break;
        }
      ?>
      <div class="form-group">
        <label class="control-label col-sm-2" for="email"><?=$title?>:</label>
        <div class="col-sm-10"><?=$inputField?></div>
      </div>
      <?php
      }
    ?>
    <div class="form-group" style='margin-top: 50px;'>
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>
  </form>
</div>

</body>
</html>
