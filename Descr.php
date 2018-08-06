<?php 
header("refresh:60;");
?>

 <html>
<head><title>Description</title>

<style>
.subH
{
margin-left:180px;
}
</style>

</head>
<body>
<p>
<?php
$id =  $_GET['id'] ;
// echo $id;
echo '<img src="/image/'.$id.'/plot.png" alt="Bird" width="500" align="right" />';
$servername="localhost";
$username="rig";
$password="rig123";
$dbname="eqdb";
$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error)
{   echo "connection failed: ".$conn->connect_error; }
// else{ echo "\ngood";}
$sql = "select year,month,day,hour,min,sec,lat,lon,depth,strike,dip,rake from catalog where id= $id";

$result=$conn->query($sql);
if($conn->error){
	echo "error";
}
$row=$result->fetch_assoc();
	 $year=$row['year'];
	$month = $row['month'];
	$day = $row['day'];
	$hour = $row['hour'];
	$min = $row['min'];
	$sec = $row['sec'];
	$lat = $row['lat'];
	$lon = $row['lon'];
	$depth = $row['depth'];
	$strike = $row['strike'];
	$dip = $row['dip'];
	$rake = $row['rake'];
$sec = round($sec);


// DateTime::createFromFormat(
//     'G i s d Y',
    
//     new DateTimeZone('UTC')
// );

// $ist_date = clone $utc_date; // we don't want PHP's default pass object by reference here
// $ist_date->setTimeZone(new DateTimeZone('Asia/Calcutta'));

// echo 'UTC:  ' . $utc_date->format('D, g:i:s A g-m-Y');  // UTC:  2011-04-27 2:45 AM
// echo 'ACST: ' . $acst_date->format('D g:i:s A g-m-Y'); // ACST: 2011-04-27 12:15 PM
$utc = $hour.':'.$min.':'.$sec.' '.$day.'-'.$month.'-'.$year;
$date = DateTime::createFromFormat('G:i:s j-m-Y',
 		$utc,
  		new DateTimeZone('UTC'));


$idt = clone $date;
$idt->setTimeZone(new DateTimeZone('Asia/Calcutta'));



echo '<h4>Origin Time(GMT):'.$date->format('g:i:s A j-m-Y').'</h4>
	<h4>Origin Time(IST):'.$idt->format('g:i:s A j-m-Y').'</h4>
	<div class=subH>
	<h4>Lattitude:'.$lat.' </h4>
	<h4>Longitude:'.$lon.' </h4>
	<h4>Depth:'.$depth.'</h4>
	</div>';


echo '<h4> Focal Mechanism:</h4>
	<div class=subH>
	<h4>Strike:'.$strike.'</h4>	
	<h4>Dip:'.$dip.'</h4>
	<h4>Rake:'.$rake.'</h4>
	</div>';
$conn->close();
?>
<h3>Description:</h3>

Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. 
</p>

</html>
