<?php ob_start();
get_header();
session_start();
global $wpdb;

/*$isTaxiFree = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."booking_orders` WHERE ((`startDay` = ".$startDay."') OR (`startDay` = '".$endDay."')) AND ((`endDay` = '".$endDay."') OR (`endDay` = '".$startDay."')) AND ((`startTime` >= '".$startTime."' AND `startTime` <= '".$endTime."') or (`endTime` >= '".$startTime."' AND `endTime` <= '".$endTime."'));");

  var_dump($isTaxiFree);*/

// Make DB table if not exists
$wpdb->query("
  CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."booking_orders` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone` varchar(255) NOT NULL,
    `startPoint` varchar(255) NOT NULL,
    `endPoint` varchar(255) NOT NULL,
    `startDay` varchar(10) NOT NULL,
    `endDay` varchar(10) NOT NULL,
    `startTime` int(3) NOT NULL,
    `endTime` int(3) NOT NULL,
    `status` int(1) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`,`email`,`phone`,`startDay`,`endDay`,`startTime`,`endTime`,`status`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Define variable to count errors
$GLOBALS['errorCount'] = 0; 

// Detect User language and define fields and errors for each language //
  $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  $acceptLang = ['ru'];
  $lang = in_array($lang, $acceptLang) ? $lang : 'en';
  $langs = array(
                [
                  [
                    'Name',
                    'Email',
                    'Phone Number',
                    'Startpoint',
                    'Endpoint',
                    'Start date',
                    'End date',
                    'Start time',
                    'End time'
                  ],
                  [
                    'David',
                    'David.Addington.1990@example.com',
                    '093-093-093',
                    '658 Southampton Ave. New Brunswick, NJ 08901.',
                    '9044 E. Hartford Circle. New Orleans, LA 70115.',
                    '25/05',
                    '26/05',
                  ],
                  [ 'The Name should contain only letters!',
                    'The Phone number should contain only numbers!',
                    'The Endpoint and Startpoint cannot be the same!',
                    'The Start date cannot be before Today or after End date!',
                    'The End date cannot be before Today or before Start date!',
                  ]
                ],
                [
                  [
                    'Имя',
                    'Эл. почта',
                    'Телефон',
                    'Начальная точка',
                    'Конечная точка',
                    'Дата начала',
                    'Дата окончания',
                    'Время начала',
                    'Время окончания'
                  ],
                  [
                    'Егор',
                    'Egor.Bushuev.1990@example.com',
                    '098-098-098',
                    'Васильевская Ул, дом 5, кв. 72',
                    'Жукова Маршала Просп., дом 41/3, кв. 43',
                    '25/05',
                    '26/05',
                  ],
                  [ 'Имя должно содержать только буквы!',
                    'Номер телефона должен содержать только цифры!',
                    'Конечная точка и Начальная точка не могут быть одинаковыми!',
                    'Дата начала не может быть раньше сегодняшнего дня или позже даты окончания!',
                    'Дата окончания не может быть раньше сегодняшнего дня или раньше даты начала!',
                  ]
                ]
  );

  // Choosing the detected language and 
  if($lang === 'ru'){
    $GLOBALS['fields'] = $langs[1][0];
    $GLOBALS['fields_pl'] = $langs[1][1];
    $GLOBALS['errors'] = $langs[1][2];
    
  }else{
    $GLOBALS['fields'] = $langs[0][0];
    $GLOBALS['fields_pl'] = $langs[0][1];
    $GLOBALS['errors'] = $langs[0][2];
  }

  // Making a variables for each field and error

  $field_Name = $GLOBALS['fields'][0];
  $field_Email = $GLOBALS['fields'][1];
  $field_Phone = $GLOBALS['fields'][2];
  $field_Startpoint = $GLOBALS['fields'][3];
  $field_Endpoint = $GLOBALS['fields'][4];
  $field_StartDate = $GLOBALS['fields'][5];
  $field_EndDate = $GLOBALS['fields'][6];
  $field_StartTime = $GLOBALS['fields'][7];
  $field_EndTime = $GLOBALS['fields'][8];

  $field_Name_Pl = $GLOBALS['fields_pl'][0];
  $field_Email_Pl = $GLOBALS['fields_pl'][1];
  $field_Phone_Pl = $GLOBALS['fields_pl'][2];
  $field_Startpoint_Pl = $GLOBALS['fields_pl'][3];
  $field_Endpoint_Pl = $GLOBALS['fields_pl'][4];
  $field_StartDate_Pl = $GLOBALS['fields_pl'][5];
  $field_EndDate_Pl = $GLOBALS['fields_pl'][6];

  $error_Name = '';
  $error_Phone = '';
  $error_Endpoint = '';
  $error_StartDate = '';
  $error_EndDate = '';

$GLOBALS['last_id'] = $wpdb->query("SELECT LAST_INSERT_ID() FROM `".$wpdb->prefix."booking_orders`");

if(isset($_POST) and !empty($_POST)){
  $username = $_POST['username'];
  $email = $_POST['useremail'];
  $phone = $_POST['userphone'];
  $startPoint = $_POST['startPoint'];
  $endPoint = $_POST['endPoint'];
  $startDay = $_POST['startDay'];
  $endDay = $_POST['endDay'];
  $startTime = $_POST['startTime'];
  $endTime = $_POST['endTime'];
  $status = $_POST['status']; 

  $isTaxiFree = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."booking_orders` WHERE ((`startDay` = '".$startDay."') OR (`startDay` = '".$endDay."')) AND ((`endDay` = '".$endDay."') OR (`endDay` = '".$startDay."')) AND ((`startTime` >= '".(int)$startTime."' AND `startTime` <= '".(int)$endTime."') or (`endTime` >= '".(int)$startTime."' AND `endTime` <= '".(int)$endTime."'));");

  if(empty($isTaxiFree)){

    // check if userName is valid
      if (!ctype_alpha($username)) {
          $error_Name = $GLOBALS['errors'][0];
          $GLOBALS['errorCount']++;
      }

    // check if phone number is valid
      if (!ctype_digit($phone)) {
          $error_Phone = $GLOBALS['errors'][1];
          $GLOBALS['errorCount']++;
      }

    // check if startpoint isn't the same as endpoint
      if($startPoint === $endPoint){
        $error_Endpoint = $GLOBALS['errors'][2];
        $GLOBALS['errorCount']++;
      }

    // Validate start day & end day
      $today = date('d/m');
      $startParts = explode('/', $startDay);
      $endParts = explode('/', $endDay);
      $todayParts = explode('/', $today);

      // check if start day isn't before today
      if((int)$startParts[1] < (int)$todayParts[1]){
        $error_StartDate = $GLOBALS['errors'][3];
        $GLOBALS['errorCount']++;
      }elseif((int)$startParts[1] === (int)$todayParts[1]){
        if((int)$startParts[0] < (int)$todayParts[0]){
          $error_StartDate = $GLOBALS['errors'][3];
          $GLOBALS['errorCount']++;
        }
      }

      // check if start day isn't after end day
      if((int)$startParts[1] > (int)$endParts[1]){
          $error_StartDate = $GLOBALS['errors'][3];
         $GLOBALS['errorCount']++;
      }elseif((int)$startParts[1] === (int)$endParts[1]){
        if((int)$startParts[0] > (int)$endParts[0]){
          $error_StartDate = $GLOBALS['errors'][3];
          $GLOBALS['errorCount']++;
        }
      }

      // check if end day isn't before today
      if((int)$endParts[1] < (int)$todayParts[1]){
        $error_EndDate = $GLOBALS['errors'][4];
        $GLOBALS['errorCount']++;
      }elseif((int)$endParts[1] === (int)$todayParts[1]){
        if((int)$endParts[0] < (int)$todayParts[0]){
          $error_EndDate = $GLOBALS['errors'][4];
          $GLOBALS['errorCount']++;
        }
      }

      // check if end day isn't before start day
      if((int)$endParts[1] < (int)$startParts[1]){
          $error_EndDate = $GLOBALS['errors'][4];
         $GLOBALS['errorCount']++;
      }elseif((int)$endParts[1] === (int)$startParts[1]){
        if((int)$endParts[0] < (int)$startParts[0]){
          $error_EndDate = $GLOBALS['errors'][4];
          $GLOBALS['errorCount']++;
        }
      }

        // Check if start time isn't before current time or isn't after end Time or isn't same as end Time
      $currentTime = date('H');
      $currentTime = (int)$currentTime+4;
      if($currentTime === 24){
          $currentTime = 0;
      }elseif($currentTime === 25){
          $currentTime = 1;
      }elseif($currentTime === 26){
          $currentTime = 2;
      }elseif($currentTime === 27){
          $currentTime = 3;
      }

      if((int)$startParts[1] === (int)$todayParts[1]){
        if((int)$startParts[0] === (int)$todayParts[0]){
            if((int)$currentTime >= (int)$startTime){
              $error_StartDate = $GLOBALS['errors'][3];
              $GLOBALS['errorCount']++;
            }
        }
      }elseif((int)$startParts[1] === (int)$todayParts[1]){
        if((int)$startParts[0] === (int)$todayParts[0]){  
            if((int)$startTime >= (int)$endTime){
              $error_StartDate = $GLOBALS['errors'][3];
              $GLOBALS['errorCount']++;
            }
        }
      }

      // Check if end time isn't before current time or isn't before start Time or isn't same as start Time
      if((int)$startParts[1] === (int)$endParts[1]){
        if((int)$startParts[0] === (int)$endParts[0]){  
            if((int)$currentTime >= (int)$endTime){
              $error_EndDate = $GLOBALS['errors'][4];
              $GLOBALS['errorCount']++;
            }
        }
      }elseif((int)$endParts[1] === (int)$todayParts[1]){
        if((int)$endParts[0] === (int)$todayParts[0]){  
            if((int)$endTime <= (int)$startTime){
              $error_EndDate = $GLOBALS['errors'][4];
              $GLOBALS['errorCount']++;
            }
        }
      }

      // try to insert data in the DB if there is no error counted
      if($GLOBALS['errorCount'] < 1){
          if($wpdb->query("INSERT IGNORE INTO `".$wpdb->prefix."booking_orders` SET 
                                `username` = '$username',
                                `email` = '$email',
                                `phone` = '$phone',
                                `startPoint` = '$startPoint',
                                `endPoint` = '$endPoint',
                                `startDay` = '$startDay',
                                `endDay` = '$endDay',
                                `startTime` = '$startTime',
                                `endTime` = '$endTime',
                                `status` = '$status'"
                          ))
          {
            $check_last_id = $wpdb->query("SELECT LAST_INSERT_ID() FROM `".$wpdb->prefix."booking_orders`");
            if ($check_last_id > $GLOBALS['last_id']) {
                echo ('<script>alert("Thank you! Your order is registered for '.$startDay.' at '.$startTime.' o`clock")</script>');
            }else{
                echo ('<script> 
                          var userLang = navigator.language || navigator.userLanguage;
                          var alertMessage = "";
                          if(userLang === "ru"){
                              alertMessage = "Пожалуйста, заполните форму правильно, чтобы заказать такси на нашей странице.<strong> Спасибо!</strong>";
                          }else{
                              alertMessage = "Please fill the form correctly to order your Taxi in our page.<strong> Thank you!</strong>";
                          }
                          var div = document.createElement("div");
                          div.id = "myDiv";
                          div.className = "alert alert-success";
                          div.innerHTML = alertMessage;
                          var button = document.createElement("button");
                          button.className = "w3-btn w3-ripple w3-blue";
                          button.innerHTML = " X ";
                          button.style = "margin-left: 15px";
                          button.onclick = function() {
                            document.getElementById("myDiv").style = "display: none";
                          };
                          div.appendChild(button);
                          document.body.appendChild(div);
                      </script>');
            }
          }else{
              echo ('<script> 
                        var userLang = navigator.language || navigator.userLanguage;
                        var alertMessage = "";
                        if(userLang === "ru"){
                            alertMessage = "Пожалуйста, заполните форму правильно, чтобы заказать такси на нашей странице.<strong> Спасибо!</strong>";
                        }else{
                            alertMessage = "Please fill the form correctly to order your Taxi in our page.<strong> Thank you!</strong>";
                        }
                        var div = document.createElement("div");
                        div.id = "myDiv";
                        div.className = "alert alert-success";
                        div.innerHTML = alertMessage;
                        var button = document.createElement("button");
                        button.className = "w3-btn w3-ripple w3-blue";
                        button.innerHTML = " X ";
                        button.style = "margin-left: 15px";
                        button.onclick = function() {
                          document.getElementById("myDiv").style = "display: none";
                        };
                        div.appendChild(button);
                        document.body.appendChild(div);
                    </script>');
          }
      }else{
        echo ('<script> 
                  var userLang = navigator.language || navigator.userLanguage;
                  var alertMessage = "";
                  if(userLang === "ru"){
                      alertMessage = "Пожалуйста, заполните форму правильно, чтобы заказать такси на нашей странице.<strong> Спасибо!</strong>";
                  }else{
                      alertMessage = "Please fill the form correctly to order your Taxi in our page.<strong> Thank you!</strong>";
                  }
                  var div = document.createElement("div");
                  div.id = "myDiv";
                  div.className = "alert alert-success";
                  div.innerHTML = alertMessage;
                  var button = document.createElement("button");
                  button.className = "w3-btn w3-ripple w3-blue";
                  button.innerHTML = " X ";
                  button.style = "margin-left: 15px";
                  button.onclick = function() {
                    document.getElementById("myDiv").style = "display: none";
                  };
                  div.appendChild(button);
                  document.body.appendChild(div);
              </script>');
          }    
  }else{
    echo ('<script> 
              var userLang = navigator.language || navigator.userLanguage;
              var alertMessage = "";
              if(userLang === "ru"){
                  alertMessage = "<strong>Нам очень жаль!</strong>&nbsp;Но, за то время, которое вы упомянули, у нас нет свободного такси";
              }else{
                  alertMessage = "<strong>We are so sorry!</strong>&nbsp;But, for the time you mentioned, we do not have a free taxi";
              }
              var div = document.createElement("div");
              div.id = "myDiv";
              div.className = "alert alert-success";
              div.innerHTML = alertMessage;
              var button = document.createElement("button");
              button.className = "w3-btn w3-ripple w3-blue";
              button.innerHTML = " X ";
              button.style = "margin-left: 15px";
              button.onclick = function() {
                document.getElementById("myDiv").style = "display: none";
              };
              div.appendChild(button);
              document.body.appendChild(div);
          </script>');
  }
}
?>
<style>
::placeholder { 
  color: black!important;
}
.error{
  font-size: 12px;
  color: #e2c470;
}
.booking-form{
  max-width: 800px;
  width: 100%;
  margin: auto;
}
.alert {
  justify-content: center;
  align-items: center;
  width: 100%;
  display: flex;
  padding: 15px;
  margin-bottom: 0 !important;
  border: 1px solid red;
  border-radius: 4px;
  color: #000000;
  background-color: #f19727;
}
</style>
<script src="<?php echo($js_data); ?>"></script>
<div id="booking" class="section">
  <div class="section-center">
    <div class="container">
      <div class="row">
        <div class="booking-form">
          <div class="form-header">
            <h1>Taxi For You!</h1>
          </div>
          <form method="post">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <span class="form-label"><?php echo $field_Name; ?></span>
                  <input id="username" name="username" class="form-control" type="text" placeholder="<?php echo $field_Name_Pl; ?>" required>
                  <span class="error"><?php echo $error_Name;?></span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <span class="form-label"><?php echo $field_Email; ?></span>
                  <input name="useremail" class="form-control" type="email" placeholder="<?php echo $field_Email_Pl; ?>" required>
                </div>
              </div>
            </div>
            <div class="form-group">
              <span class="form-label"><?php echo $field_Phone; ?></span>
              <input id="userphone" name="userphone" class="form-control" type="text" maxlength="9" placeholder="<?php echo $field_Phone_Pl; ?>" required>
              <span class="error"><?php echo $error_Phone; ?></span>
            </div>
            <div class="form-group">
              <span class="form-label"><?php echo $field_Startpoint; ?></span>
              <input name="startPoint" class="form-control" type="text" placeholder="<?php echo $field_Startpoint_Pl; ?>" required>
            </div>
            <div class="form-group">
              <span class="form-label"><?php echo $field_Endpoint; ?></span>
              <input name="endPoint" class="form-control" type="text" placeholder="<?php echo $field_Endpoint_Pl; ?>" required>
              <span class="error"><?php echo $error_Endpoint; ?></span>
            </div>
            <div class="row">
              <div class="col-sm-9">
                <div class="form-group">
                  <span class="form-label"><?php echo $field_StartDate; ?></span>
                  <input class="form-control" id="startDay" name="startDay" type="text" maxlength="5" placeholder="<?php echo $field_StartDate_Pl; ?>" required>
                  <span class="error"><?php echo $error_StartDate; ?></span>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="row">
                  <div class="col-sm-4"  style="width: auto!important;">
                    <div class="form-group">
                      <span class="form-label"><?php echo $field_StartTime; ?></span>
                      <select class="form-control" name="startTime">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                        <option>13</option>
                        <option>14</option>
                        <option>15</option>
                        <option>16</option>
                        <option>17</option>
                        <option>18</option>
                        <option>19</option>
                        <option>20</option>
                        <option>21</option>
                        <option>22</option>
                        <option>23</option>
                        <option>24</option>
                      </select>
                      <span class="select-arrow"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-9">
                <div class="form-group">
                  <span class="form-label"><?php echo $field_EndDate; ?></span>
                  <input class="form-control" id="endDay" name="endDay" type="text" maxlength="5" placeholder="<?php echo $field_EndDate_Pl; ?>" required>
                  <span class="error"><?php echo $error_EndDate; ?></span>
                </div>
              </div>
              <div class="col-sm-3" >
                <div class="row">
                  <div class="col-sm-4" style="width: auto!important;">
                    <div class="form-group">
                      <span class="form-label"><?php echo $field_EndTime; ?></span>
                      <select class="form-control"  name="endTime">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                        <option>13</option>
                        <option>14</option>
                        <option>15</option>
                        <option>16</option>
                        <option>17</option>
                        <option>18</option>
                        <option>19</option>
                        <option>20</option>
                        <option>21</option>
                        <option>22</option>
                        <option>23</option>
                        <option>24</option>
                      </select>
                      <span class="select-arrow"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="status" value="1">
            <input type="submit" name="submit" class="w3-btn w3-round w3-orange" value="Book Now">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="myModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Do you want to save changes you made to document before closing?</p>
        <p class="text-warning"><small>If you don't save, your changes will be lost.</small></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id='emergency'></div>
<script>
  // define fields as variables
  var userName = document.getElementById('username');
  var telephone = document.getElementById('userphone');
  var startinput = document.getElementById('startDay');
  var endinput = document.getElementById('endDay');

  // delete Paste option for fields
  function noPaste(elm){   
    elm.addEventListener('paste', function (e) {
      e.preventDefault();
      var userLanguage = navigator.language || navigator.userLanguage;
      if(userLanguage === 'ru'){
        var alertNoPaste = 'Не будьте ленивым! Есть много других клавиш на клавиатуре, кроме Ctrl + V';      
      }else{
        var alertNoPaste = 'Do not be lazy! There are many other keys in the keyboard besides Ctrl + V';
      } 
      alert(alertNoPaste);
    });
  }
  noPaste(userName);
  noPaste(telephone);
  noPaste(startinput);
  noPaste(endinput);
  // Validate User Name
  function userNameMask(elm) {
    elm.addEventListener('keypress', function (e) {
      if (e.keyCode < 65 || e.keyCode > 90) {
        if (e.keyCode < 97 || e.keyCode > 122) {
          e.preventDefault();
        }
      }
    });

    elm.addEventListener('input', function (e) {
      if (e.keyCode < 65 || e.keyCode > 90) {
        if (e.keyCode < 97 || e.keyCode > 122) {
          e.preventDefault();
        }
      }
    });
  }
  userNameMask(userName);

  // Validate User Phone
  function telephoneMask(elm) {
    elm.addEventListener('keypress', function (e) {
      if (e.keyCode < 48 || e.keyCode > 57) {
        e.preventDefault();
      }
    });

    elm.addEventListener('input', function (e) {
      if (e.keyCode < 48 || e.keyCode > 57) {
        e.preventDefault();
      }
    });
  }  
  telephoneMask(telephone);
 
  // Validate startDay and endDay
  var dateInputMask = function dateInputMask(elm) {
    elm.addEventListener('keypress', function (e) {
      if (e.keyCode < 48 || e.keyCode > 57) {
        e.preventDefault();
      }
    });

    elm.addEventListener('keydown', function (e) {
      if (e.keyCode === 8 || e.keyCode === 46) {
        elm.value = '';
      }
    });

    elm.addEventListener('input', function (e) {
      if (e.keyCode < 48 || e.keyCode > 57) {
        e.preventDefault();
      }

      var len = elm.value.length;

      // If we're at a particular place, let the user type the slash
      // i.e., 12/12/1212
      if (len !== 1 || len !== 3) {
        if (e.keyCode == 47) {
          e.preventDefault();
        }
      }

      if (len === 1) {
        if (elm.value > 3) {
          elm.value = '';
        }
      }

      // If they don't add the slash, do it for them...
      if (len === 2) {
        var valu = elm.value;
        var ldata = valu.substr(0, valu.length - 1);
        var llastChar = valu.substr(valu.length - 1);
        parseInt(llastChar);
        parseInt(ldata);
        if (ldata > 2) {
          //console.log(llastChar);
          if (llastChar > 1) {
            elm.value = ldata;
          }else {
            elm.value += '/';
          }
        }else {
          elm.value += '/';
        }

        if(ldata < 1){
          if (llastChar < 1) {
            elm.value = ldata;
          }
        }

      }

      if (len === 4) {
        var val = elm.value;
        var data = val.substr(0, val.length - 1);
        var lastChar = val.substr(val.length - 1);
        parseInt(lastChar);
        if (lastChar > 1) {
          elm.value = data;
        }
      }


      if (len === 5) {
        var vall = elm.value;
        var datal = vall.substr(0, vall.length - 1);
        var firstCharl = datal.substr(datal.length - 1);
        var lastCharl = vall.substr(vall.length - 1);
        parseInt(lastCharl);
        parseInt(firstCharl);
        if (firstCharl > 0) {
          if (lastCharl > 2) {
            elm.value = datal;
          }
        }
        if(firstCharl === 0){
          if (lastCharl === 0) {
            elm.value = datal;
          }
        }
      }
    });
  };

  dateInputMask(startinput);
  dateInputMask(endinput); 

  window.addEventListener('load', function () {
    var lastElem = document.getElementsByTagName('div');
    var lastcount = lastElem.length-1;
    lastElem[lastcount].style = 'display: none!important;';
  });
</script>
<?php get_footer(); ?>