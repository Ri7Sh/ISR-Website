<?php

$servername="localhost";
$username="rig";
$password="rig123";
$dbname = "eqdb";

$conn = new mysqli($servername, $username, $password, $dbname);
$GMTdir="/home/rigvita/php/project/image";
$null=1.1;
if($conn->connect_error)
{   die("connection failed: ".$conn->connect_error); }

//echo "connection established successfully\n";

$SeiDir="/home/rigvita/04";
$DBase="isr__";
$Year="2018";
$Month="04";
$dir = $SeiDir."/REA/".$DBase."/".$Year."/".$Month;


if (is_dir($dir))
{
  if ($dh = opendir($dir))
  {
    file_put_contents($GMTdir."/ALLepicenter.txt", "");
        
    $fileArray = scandir($dir);
    $numOFfiles = sizeof($fileArray);
    $i =0;
    while($i<$numOFfiles)
    {
    	if($fileArray[$i]=="."||$fileArray[$i]=="..")  //??
        {
    		$i++;
    		continue;
    	}
    	
        $evt = $dir."/".$fileArray[$i];
    	$rowNUM = file($evt);
    	$filetime=filemtime($evt);
        
    	$row = $rowNUM[0];
        //echo substr($row,63,4)."\n";
        
        $oYear=substr($row,1,4);
        $oMonth=substr($row,6,2);
        $oDay=substr($row,8,2);
        $oHour=substr($row,11,2);
        $oMin=substr($row,13,2);
        $oSec=substr($row,16,4);
        $hLat=substr($row,23,7);
        $hLon=substr($row,30,8);
        $hDep=substr($row,38,5);
        $Ml=substr($row,56,3);
    	$Mw=substr($row,64,3);
     //   echo $Mw;
    	$evtID=0;
    	for($k = 1; $k <count($rowNUM);$k++)
        {
    		if(substr($rowNUM[$k],1,6) == "ACTION")
            {
    			$pos = strpos($rowNUM[$k],"ID:");
    			$evtID = substr($rowNUM[$k], $pos+3,14);
    			break;
    		}
    	}

        for($j = 1; $j <count($rowNUM);$j++)
        {
            if(substr($rowNUM[$j],79,1) == "F")
            {
                $strike = substr($rowNUM[$j],2,8);
                $dip = substr($rowNUM[$j],13,7);
                $rake = substr($rowNUM[$j],22,8);
                break;
            }
        }
        $flag = 0; 
        $i++;
        $s = "select lon, lat, mTime from catalog where id=$evtID";
        $result = $conn->query($s);
	if($result){        
		$result = $result->fetch_assoc();
        	if($result["mTime"] == $filetime) {
                       	file_put_contents($GMTdir."/ALLepicenter.txt", $result['lon'].' '.$result['lat']."\n", FILE_APPEND);     
			continue;
            }
	}
        
        
        if($Ml == "   " || $Mw=="   ")
        {   $sql = "Insert into catalog values ($evtID,$oYear,$oMonth,$oDay,$oHour,$oMin,$oSec,$hLat,$hLon,$hDep, NULL,NULL,$strike,$dip,$rake, $filetime) ON DUPLICATE KEY UPDATE year=$oYear, month=$oMonth, day=$oDay, hour=$oHour, min=$oMin, sec=$oSec, lat=$hLat, lon=$hLon, depth=$hDep, Ml=NULL, Mw=NULL, strike=$strike, dip=$dip, rake=$rake, mTime=$filetime" ;
            if($Ml != "   ")
            {   $flag = 1;
                $sql1 = "Update catalog set Ml = $Ml where id = $evtID" ;
            }
            else if($Mw !="   ")
            {   $flag = 1;
                $sql1 = "Update catalog set Mw = $Mw where id = $evtID";
            }
        
        } 
        else
            {   $sql = "Insert into catalog values ($evtID,$oYear,$oMonth,$oDay,$oHour,$oMin,$oSec,$hLat,$hLon,$hDep,$Ml,$Mw,$strike,$dip,$rake, $filetime) ON DUPLICATE KEY UPDATE year=$oYear, month=$oMonth, day=$oDay, hour=$oHour, min=$oMin, sec=$oSec, lat=$hLat, lon=$hLon, depth=$hDep, Ml=$Ml, Mw=$Mw, strike=$strike, dip=$dip, rake=$rake, mTime=$filetime";
            }
        
        
        if($conn->query($sql)==TRUE){echo "good\n";}
        if ($flag == 1 && $conn->query($sql1)){echo "very very good\n";}



        //sq2GMT begin here
        
        
        $sql= "SELECT id, lat, lon, depth, strike, dip, rake, Ml FROM catalog where id = $evtID";
        if(!($result=$conn->query($sql))){continue;}
	else{echo "it's good";}	
	$la = "Select min(lat), max(lat), min(lon), max(lon) from catalog";		
        $test = $conn->query($la);		
        while($row = $test->fetch_assoc()){		
        $testte = $row['min(lat)']. ' '.$row['max(lat)']. ' '.$row['min(lon)']. ' '.$row['max(lon)'];		
        file_put_contents($GMTdir."/min_max_latLON.txt", $testte);		
        }		
        //
        while($row=$result->fetch_assoc())
        {   $evtDIR=$GMTdir."/".$row['id'];
    
            if(!is_dir($evtDIR)) 
                mkdir($evtDIR);
			file_put_contents($GMTdir."/ALLepicenter.txt", $row['lon'].' '.$row['lat']."\n", FILE_APPEND);
            $myfile = fopen($evtDIR."/epicenter.txt", "w");
            $txt1=$row['lon'].' '.$row['lat'];
            fwrite($myfile,$txt1);
            fclose($myfile);
			
            $myfile = fopen($evtDIR."/fps.txt", "w");
            if($row['Ml']!==NULL)
            {   $txt2=$row['lon'].' '.$row['lat'].' '.$row['depth'].' '.$row['strike'].' '.$row['dip'].' '.$row['rake'].' '.$row['Ml'].' '.($row['lon']+1.5).' '.($row['lat']+1.5).' M'.$row['Ml']; }
            else
                {   $txt2=$row['lon'].' '.$row['lat'].' '.$row['depth'].' '.$row['strike'].' '.$row['dip'].' '.$row['rake'].' '.$null.' '.($row['lon']+1.5).' '.($row['lat']+1.5).' M'.$null;   }
        
                fwrite($myfile,$txt2);
                fclose($myfile);

                copy($GMTdir."/prog",$evtDIR."/prog");
		

                chdir($evtDIR);
                system('chmod 777 prog');
                system('./prog');
        }
	        
        //sq2GMT ends here
    }
	chdir($GMTdir);		
  	system('chmod 777 progFepi');		
  	system('./progFepi');		
	
    //closedir($dh);
  }
  closedir($dh);
}
$conn->close();

?>
