<?php
ini_set("log_errors", 1);

ini_set("error_log", "/home/edwin114/public_html/admin/filemanager/cron_error.txt");

error_log( "Hello, This is Cron Trash File" );
 
$con = new mysqli("localhost", "edwin114_rosnyc", "+-L7Ab[Spave", "edwin114_rosnyc");

 if(mysqli_connect_errno())
	   {
		  print_f("Connection Failed: %s\n",mysqli_connect_error());
	   }
	   
	$result=$con->query("SELECT * from filemanager_trash");
	
    $row_cnt = $result->num_rows;	
    
	if($row_cnt > 0){
		
		while($data=mysqli_fetch_assoc($result)){
			
			$modif=$data['modif'];
			
			$modifdate=date('Y-m-d');
			
			$fulltrashpath=$data['fulltrashpath'];
			
			// get 30 days date  after fixed date //
			
		    $nextdate= date('Y-m-d', strtotime($modifdate. ' + 30 days'));
			
			      $nextdate= strtotime($nextdate);
				  
				  $currentdate=date('Y-m-d');
				  
				  $currentdate=strtotime($currentdate);
				  
				  if($nextdate == $currentdate){
					   unlink($data['fulltrashpath']);
					  // After Unlink Delete Row
					  $result=$con->query("DELETE from filemanager_trash WHERE Id=".$data['id']);
					 error_log("Trash Data Delete from id=".$data['id']);
					}
		        }
            }
?>