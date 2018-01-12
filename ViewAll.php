<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

// Start of the output
$OUTPUT->header();

include("tool-header.html");
include("tool-js.html");

$OUTPUT->bodyStart();    

include("menu.php");
echo ('
<div style="margin-left:30px;"><h2>Quick Write</h2>');

	

$SetID = $_SESSION["SetID"];
$StudentList = $QW_DAO->Report($SetID);		

if(count($StudentList)){echo ('<div style="margin-bottom:20px;">View All Results.</div>'); }


echo('<div id="Btn02"> <a class="btn btn-default" href="instructor-home.php?Add=0" >Back to Main Page</a><a href="actions/ExportToFile.php" id="Btn02_1">Export Results</a></div><br>');


foreach ( $StudentList as $row ) {
	
	echo('
	    <div class="panel-body" style="border:1px solid gray; ">           
			<div class="col-sm-3 " style="width:200px"><b>'.$row["FirstName"].' '.$row["LastName"].'</b>');
			
		$UserID = 	$row["UserID"];
		$questions = $QW_DAO->getQuestions($SetID);	
		$Date1 = $QW_DAO->getUserData($SetID, $UserID);	
		$dateTime1 = new DateTime($Date1["Modified"]);	
		$D1 =$dateTime1->format("m-d-y")." at ".$dateTime1->format("h:i A");

					echo('<br><i>'.$D1.'</i> </div>
			<div class="col-sm-9 noPadding">
				
			
			');
					foreach ( $questions as $row1 ) {

								$A="";	
								$QID = $row1["QID"];	

								$Data = $QW_DAO->Review($QID, $UserID);	
								foreach ( $Data as $row2 ) {

									$A= $row2["Answer"];
									$Date1 = $row2["Modified"];


								}





						echo ('<table style="width:100%;background-color:#E6E6E6; margin-bottom:10px; border-bottom:1px solid gray;"><tr ><td width="90" valign="top" style="padding-left:5px;"><b> Question '.$row1["QNum"].'</b></td><td>'.$A.'</td></tr></table>'); 

				 }


			
			
			
		
			
			
			

			echo ('</div>
		</div>
		
           
        '); 
	
	
}

echo ('</div>');
	
$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();


?>