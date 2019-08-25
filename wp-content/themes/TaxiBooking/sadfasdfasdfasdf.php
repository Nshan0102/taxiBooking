<?php ob_start();
get_header();
session_start();
$prepare_data =  get_template_directory_uri().'/prepare_data.php';
$js_data =  get_template_directory_uri().'/assets/js/date.js';
global $wpdb;
$GLOBALS['errorCount'] = 0; 
$last_id = $wpdb->query("SELECT LAST_INSERT_ID() FROM `wpe_booking_orders`");
if(isset($_POST)){
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
    


  // If user set the start time higher then end time replace values!
    if((int)$startDay === (int)$endDay){
      if((int)$startTime > (int)$endTime){
          $temp = $startTime;
          $startTime = $endTime;
          $endTime = $temp;
      }
    }

  // Validate start day & end day
    $today = date('d-m');
    $startParts = explode('/', $startDay);
    $endParts = explode('/', $endDay);
    $todayParts = explode('/', $today);
    
    // check if start day isn't before today
    if((int)$startParts[1] < (int)$todayParts[1]){
      $GLOBALS['errorCount'] += 1;
    }elseif((int)$startParts[1] === (int)$todayParts[1]){
      if((int)$startParts[0] < (int)$todayParts[0]){
        $GLOBALS['errorCount'] += 1;
      }
    }

    // check if start day isn't after end day
    if((int)$startParts[1] > (int)$endParts[1]){
       $GLOBALS['errorCount'] += 1;
    }elseif((int)$startParts[1] === (int)$endParts[1]){
      if((int)$startParts[0] > (int)$endParts[0]){
        $GLOBALS['errorCount'] += 1;
      }
    }

    // check if startpoint isn't the same as endpoint
    if($startPoint === $endParts){
       $GLOBALS['errorCount'] += 1;
    }


    if($GLOBALS['errorCount'] < 1){
      if($wpdb->query("INSERT IGNORE INTO `booking_orders` SET 
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
                    )
      ){
        $check_last_id = $wpdb->query("SELECT LAST_INSERT_ID() FROM `booking_orders`");
        if ($check_last_id > $last_id) {
          echo ('<script>alert("Thank you! Your order is registered for '.$startDay.' at '.$startTime.' o`clock")</script>');
          $_SESSION['lastID'] = $last_id;
        }else{
          echo ('<script>var div = document.createElement("div");
                          div.id = "myDiv";
                          div.className = "alert alert-success";
                          div.innerHTML = "Please fill the form correctly to order your Taxi in our page.<strong> Thank you!</strong>";
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
        echo ('<script>var div = document.createElement("div");
                          div.id = "myDiv";
                          div.className = "alert";
                          div.innerHTML = "Please fill the form correctly to order your Taxi in our page.<strong> Thank you!</strong>";
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
      echo ('<script>var div = document.createElement("div");
                          div.id = "myDiv";
                          div.className = "alert";
                          div.innerHTML = "Please fill the form correctly to order your Taxi in our page.<strong> Thank you!</strong>";
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
    
}else{
  echo ('<script>var div = document.createElement("div");
                      div.id = "myDiv";
                      div.className = "alert";
                      div.innerHTML = "Please fill the form correctly to order your Taxi in our page.<strong> Thank you!</strong>";
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
?>
<style>
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
                  <span class="form-label">Անուն</span>
                  <input id="username" name="username" class="form-control" type="text" placeholder="Գուրգեն" required>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <span class="form-label">Էլ. հասցե</span>
                  <input name="useremail" class="form-control" type="email" placeholder="example@gmail.com" required>
                </div>
              </div>
            </div>
            <div class="form-group">
              <span class="form-label">Հեռախոսահամար</span>
              <input name="userphone" class="form-control" type="tel" placeholder="+(374)-93-664-341" required>
            </div>
            <div class="form-group">
              <span class="form-label">Սկզբնակետ</span>
              <input name="startPoint" class="form-control" type="text" placeholder="Սայաթ Նովա 9/9ա" required>
            </div>
            <div class="form-group">
              <span class="form-label">Վերջնակետ</span>
              <input name="endPoint" class="form-control" type="text" placeholder="Կորյունի 8/9ա" required>
            </div>
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group">
                  <span class="form-label">Սկսած</span>
                  <input class="form-control" id="startDay" name="startDay" type="text" maxlength="5" placeholder=" ՕՐ / ԱՄԻՍ " required>
                </div>
              </div>
              <div class="col-sm-7">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <span class="form-label">Ժամը</span>
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
              <div class="col-sm-5">
                <div class="form-group">
                  <span class="form-label">Մինչև</span>
                  <input class="form-control" id="endDay" name="endDay" type="text" maxlength="5" placeholder=" ՕՐ / ԱՄԻՍ " required>
                </div>
              </div>
              <div class="col-sm-7">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <span class="form-label">Ժամը</span>
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
            <input type="submit" name="submitl" class="w3-btn w3-round w3-orange" value="Book Now">
          </form>
        </div>
        <div id="ordered" style="display: none;">
          <h1> You have booked Taxi for <?php echo $_POST['date'] ?></h1>
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
<script>
  var startinput = document.getElementById('startDay');
  var endinput = document.getElementById('endDay');
  var dateInputMask = function dateInputMask(elm) {
    elm.addEventListener('keypress', function (e) {
      if (e.keyCode < 48 || e.keyCode > 57) {
        e.preventDefault();
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
        if (ldata > 2) {
          console.log(llastChar);
          if (llastChar > 1) {
            elm.value = ldata;
          }
          else {
            elm.value += '/';
          }
        }
        else {
          elm.value += '/';
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
        console.log(datal);
        var lastCharl = vall.substr(vall.length - 1);
        parseInt(lastCharl);
        parseInt(firstCharl);
        if (firstCharl > 0) {
          if (lastCharl > 2) {
            elm.value = datal;
            elm.style = "background: red";
          }
          else {
            elm.style = "background: green";
          }
        }
      }
    });
  };

  dateInputMask(startinput);
  dateInputMask(endinput); 
</script>
<?php get_footer(); ?>