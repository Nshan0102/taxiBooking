<?php get_header() ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

<style type="text/css">
div i{
	padding: 5px;
}
.card-title{
	margin-bottom: 0;
}
</style>

<?php 
global $wpdb;
$all_datas = $wpdb->get_results( "SELECT * FROM `".$wpdb->prefix."booking_orders` WHERE `status`=1");
$t=date('d-m-y');
$parts = explode('-', $t);
$timecheck = date('H');
$timecheck = (int)$timecheck+4;
// var_dump($parts);
foreach ($all_datas as $value) {
	$thisID = $value->id;
	$endcheck = $value->endDay;
	$endcheckparts = explode('/', $endcheck);
	if((int)$endcheckparts[1] <= (int)$parts[1]){
		if((int)$endcheckparts[0] <= (int)$parts[0]){
			if((int)$value->endTime <= $timecheck){
				$wpdb->query("UPDATE `".$wpdb->prefix."booking_orders` SET `status` = 0 where `id` = '$thisID' ");
			}
		}
	}
}

$all_data = $wpdb->get_results( "SELECT * FROM `".$wpdb->prefix."booking_orders` WHERE `status`=1");
// var_dump($all_data);
?>

<div class="flex-wrap content" style="display: flex;justify-content: center;">
<?php if(!empty($all_data)){ foreach ($all_data as $data) { ?>
<div class="card border-dark mb-3" style="max-width: 30rem;margin-right: 15px;padding: 5px">
  <div class="card-header">Name: <b><?php echo $data->username ?></b></div>
  <div class="card-body text-dark " style="display: flex;flex-direction: column;">
    <span class="card-title"><i class="fas fa-envelope"></i>Email: <?php echo $data->email ?></span>
    <span class="card-text"><i class="fas fa-phone-volume"></i>Phone: <?php echo $data->phone ?></span>
    <span class="card-text"><i class="fas fa-map-marker"></i>StartPoint: <?php echo $data->startPoint ?></span>
    <span class="card-text"><i class="fas fa-map-marker-alt"></i>EndPoint: <?php echo $data->endPoint ?></span>
    <span class="card-text"><i class="fas fa-calendar-alt">&nbsp;</i>StartDay: <?php echo $data->startDay ?><i class="fas fa-clock"></i><?php echo $data->startTime ?> o'clock</span>
    <span class="card-text"><i class="far fa-calendar-alt">&nbsp;</i>EndDay: <?php echo $data->endDay ?><i class="far fa-clock"></i><?php echo $data->endTime ?> o'clock</span>
  </div>
</div>

<?php } }else{echo '<h2>There is no order to show!</h2>'; }?>
</div>
<?php get_footer() ?>

