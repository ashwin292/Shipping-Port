<?php

function DeductTariff($debit_id, $port)
{
    $servername = "us-cdbr-east-03.cleardb.com";
 	$username = "b474b95ea4f970";
 	$password = "46b36be7";
 	$db = "heroku_989d675bc42ca01";
 	$conn = new mysqli($servername, $username, $password, $db);

 	if ($conn->connect_error){
 		die("Connection failed: ". $conn->connect_error);
 	}

     $accnt_bal;
     $tariff;

     $sql = "select * from country where (Id = '$debit_id')";

     if($result = mysqli_query($conn, $sql))
 	{
 		if(mysqli_num_rows($result) > 0)
 		{
 			while($row = mysqli_fetch_array($result))
 			{
 				$accnt_bal = $row['Accnt_Balance'];
 			}

 		}
         else
         {
             echo "<br>"."Country with debit id doesn't exist";
         }
 	}
     else
     {
        echo "Error: " . $sql . "<br>" . $conn->error;
     }

     $sql = "select * from country where (Id = (select Id from ports where(Port_Number = '$port')))";

     if($result = mysqli_query($conn, $sql))
 	{
 		if(mysqli_num_rows($result) > 0)
 		{
 			while($row = mysqli_fetch_array($result))
 			{
 				$tariff = $row['Tarrif'];
 			}

 		}
 	}
     else
     {
        echo "Error: " . $sql . "<br>" . $conn->error;
     }

     $accnt_bal = $accnt_bal - $tariff;

     $sql = "UPDATE country SET Accnt_Balance='$accnt_bal' WHERE Id='$debit_id'";

     if($result = mysqli_query($conn, $sql))
 	{
 		if($result)
 		{
 			echo "<br>"."Tariff Updated";

 		}
 	}
     else
     {
        echo "Error: " . $sql . "<br>" . $conn->error;
     }

}

function updatePortStatus($port_no)
 {
 	$servername = "us-cdbr-east-03.cleardb.com";
 	$username = "b474b95ea4f970";
 	$password = "46b36be7";
 	$db = "heroku_989d675bc42ca01";
 	$conn = new mysqli($servername, $username, $password, $db);

 	if ($conn->connect_error){
 		die("Connection failed: ". $conn->connect_error);
 	}
    
 	$sql = "select count(*) from ships where (Port_Number = '$port_no')";

 	$ships;
 	$containers;

 	if($result = mysqli_query($conn, $sql))
 	{
 		if(mysqli_num_rows($result) > 0)
 		{
 			while($row = mysqli_fetch_array($result))
 			{
 				//Number of ships present
 				$ships = $row[0];
 			}

 		}
 	}

 	$sql = "select count(*) from container where (Port_Number = '$port_no')";

 	if($result = mysqli_query($conn, $sql))
 	{
 		if(mysqli_num_rows($result) > 0)
 		{
 			while($row = mysqli_fetch_array($result))
 			{
 				//Number of ships present
 				$containers = $row[0];
 			}

 		}
 	}

 	$sql = "select * from ports where (Port_Number = '$port_no')";

 	$max_cont;
 	$max_ships;

 	if($result = mysqli_query($conn, $sql))
 	{
 		if(mysqli_num_rows($result) > 0)
 		{
 			while($row = mysqli_fetch_array($result))
 			{
 				//Number of ships present
 				$max_cont = $row['Max_Container_Capacity'];
 				$max_ships = $row['Max_No_of_Ships'];
 			}

 		}
 	}

	 if($ships >= $max_ships)
 	{
 		$sql = "UPDATE ports SET Status = 'NA' WHERE Port_Number = '$port_no'";
 	}

 	else if($containers >= $max_cont)
 	{
 		$sql = "UPDATE ports SET Status = 'AS' WHERE Port_Number = '$port_no'";
 	}

 	else
 	{
 		$sql = "UPDATE ports SET Status = 'AV' WHERE Port_Number = '$port_no'";
 	}

 	if(mysqli_query($conn, $sql))
    {
	}
   
   else
   {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }

 }

// $port = 3;

// updatePortStatus($port);

function sharesPort($country_id)
{
    $servername = "us-cdbr-east-03.cleardb.com";
 	$username = "b474b95ea4f970";
 	$password = "46b36be7";
 	$db = "heroku_989d675bc42ca01";
 	$conn = new mysqli($servername, $username, $password, $db);

 	if ($conn->connect_error){
 		die("Connection failed: ". $conn->connect_error);
 	}
    
 	//Check for shared port
     $sql = "select * from shares where (Id='$country_id')";

     if($result = mysqli_query($conn, $sql))
     {
        if(mysqli_num_rows($result) > 0)
        {
            return True;
        }
        else return False;
     }
     else
     {
        echo "Error: " . $sql . "<br>" . $conn->error;
     }
}

function checksharedPort($country_id)
 {
 	$servername = "us-cdbr-east-03.cleardb.com";
 	$username = "b474b95ea4f970";
 	$password = "46b36be7";
 	$db = "heroku_989d675bc42ca01";
 	$conn = new mysqli($servername, $username, $password, $db);

 	if ($conn->connect_error){
 		die("Connection failed: ". $conn->connect_error);
 	}
    
 	//Check for shared port
     $sql = "select * from shares where (Id='$country_id')";
     $sts;

     $result = $conn->query($sql);

        if($result->num_rows > 0)
         {
             //Country shares other port

             $pn;

             while($row = mysqli_fetch_array($result))
             {

                 $pn = $row['Port_Number'];
             }

             updatePortStatus($pn);

             $sql = "select * from ports where (Port_Number = '$pn')";

             if($result = mysqli_query($conn, $sql))
             {
                 //Shared port available
                 while($row = mysqli_fetch_array($result))
                 {
                     $sts = $row['Status'];
                 }
                 
                 return $sts;
             }
         }

         else
         {
            echo "Error: " . $sql . "<br>" . $conn->error;
         }

 }

?>