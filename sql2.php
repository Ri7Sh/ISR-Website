<?php
//$page = $_SERVER['PHP_SELF'];
//$sec = "10";
//echo "refresh";
/*<meta http-equiv="refresh" content="<?php echo $sec?>'">*/
 header( "refresh:60;" );
?>

<html>

<head>
<title>Earthquakes Data</title>
	<style>
		table {
    			font-family: arial, sans-serif;
    			border-collapse: collapse;
    			width: 960px;
                margin-left:auto;
                margin-right: auto;
			}

		td, th {
    			border: 1px solid #dddddd;
    			text-align: left;
    			padding: 8px;
				}

		tr:nth-child(even) {
    						background-color: #dddddd;
							}
		h2 {text-align: center;}

        img 
        {   border: 1px solid #ddd; /* Gray border */
            border-radius: 4px;  /* Rounded border */
            padding: 5px; /* Some padding */
            width: 15px; /* Set a small width */
        }
        img:hover 
        {   box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);}

        div.polaroid        
         {       
           width: 50%;       
           background-color: white;      
           /*box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);*/     
           margin-bottom: 25px;      
           margin-left:auto;     
           margin-right: auto;       
         }
	</style>
</head>

<body>
	<p>&nbsp;</p>
	<h2>Earthquakes Data</h2>
    	
    <table>
  		 <tr>
            <!-- <th>Event ID</th> -->
    		<th>Year</th>
   			<th>Month</th>
    		<th>Day</th>
    		<th>Hour</th>
    		<th>Min</th>
    		<th>Sec</th>
    		<th>Latitude</th>
    		<th>Longitude</th>
    		<th>Depth</th>
    		<th>Ml</th>
            <th>Mw</th>
            <th>Strike</th>
            <th>Dip</th>
            <th>Rake</th>
            <th>Description</th>
    	 </tr>

          <?php

            $servername="localhost";
            $username="rig";
            $password="rig123";
            $dbname="eqdb";
            //$GMTdir="/home/kantm/project/image";
            $GMTdir="image";

            $results_per_page = 2; // number of results per page

            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 

            $start_from = ($page-1) * $results_per_page;

            //system('php source2sql.php');
            // system('php sql2GMT.php');

            $conn = new mysqli($servername,$username,$password,$dbname);
            $sql="SELECT id, year, month,day,hour,min,sec,lat,lon,depth,Ml,Mw,strike,dip,rake FROM catalog ORDER BY id ASC LIMIT $start_from, ".$results_per_page;
            $result=$conn->query($sql);
            while($row=$result->fetch_assoc())
            {   
                $evtDIR=$GMTdir."/".$row['id'];
            // <td id="#id">'.$row['id'].'</td>
            echo '<tr>
             
             <td>'.$row['year'].'</td>
             <td>'.$row['month'].'</td>
             <td>'.$row['day'].'</td>
             <td>'.$row['hour'].'</td>
             <td>'.$row['min'].'</td>
             <td>'.$row['sec'].'</td>
             <td>'.$row['lat'].'</td>
             <td>'.$row['lon'].'</td>
             <td>'.$row['depth'].'</td>
             <td>'.$row['Ml'].'</td>
             <td>'.$row['Mw'].'</td>
             <td>'.$row['strike'].'</td>
             <td>'.$row['dip'].'</td>
             <td>'.$row['rake'].'</td>
            <td><a href="Descr.php?id='.$row['id'].'">Description</a></td></tr>';
            }



        //     $GMTdir="/home/rigvita/php/project/image";
        //     $sql = "SELECT id, lat, lon, depth, strike, dip, rake, Ml FROM catalog";
        //     $mapData = $conn->query($sql);
        //     while($mapData=$result->fetch_assoc())
        // {       
            
        //     $myfile = $evtDIR."/epicenter.txt";
        //     $txt1=$mapData['lon'].' '.$mapData['lat'];
        //     file_put_contents($myfile,$txt1,FILE_APPEND); 
            
        //     $myfile = $evtDIR."/fps.txt";
        //     if($mapData['Ml']!==NULL)
        //     {   $txt2=$mapData['lon'].' '.$mapData['lat'].' '.$mapData['depth'].' '.$mapData['strike'].' '.$mapData['dip'].' '.$mapData['rake'].' '.$mapData['Ml'].' '.($mapData['lon']+1.5).' '.($mapData['lat']+1.5).' M'.$mapData['Ml']; }
        //     else
        //         {   $txt2=$mapData['lon'].' '.$mapData['lat'].' '.$mapData['depth'].' '.$mapData['strike'].' '.$mapData['dip'].' '.$mapData['rake'].' '.$null.' '.($mapData['lon']+1.5).' '.($mapData['lat']+1.5).' M'.$null;   }
        
        //         file_put_contents($myfile, $txt2, FILE_APPEND);
                
        //         system('chmod 777 prog');
        //         system('./prog');
        // }
            

            
            $sql1 = "SELECT COUNT(id) AS total FROM catalog"; 
            $result = $conn->query($sql1);
            $row = $result->fetch_assoc();
            $total_pages = ceil($row["total"] / $results_per_page);


            
            for ($i=1; $i<=$total_pages; $i++) 
            {   echo "_";
                echo "<a href='sql2.php?page=".$i."'>".$i."</a> "; 
            }
            
            
            $conn->close();
            ?>
    </table>	
    <div class="polaroid">
    <a target="_blank" href="image/plot.png"><img src="image/plot.png" alt="ALL EPICENTER" style="width:100%"></a></div>	 

    
</body>
</html> 
