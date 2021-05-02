<?php
session_start();
$servername = "us-cdbr-east-03.cleardb.com";
$username = "b474b95ea4f970";
$password = "46b36be7";
$db = "heroku_989d675bc42ca01";
$conn = new mysqli($servername, $username, $password, $db);

//
// $startdate = $_POST["startdate"];
// $enddate = $_POST["enddate"];

$startdate = $_SESSION['startdate'] ;
$enddate = $_SESSION['enddate'] ;

echo "<head>
					<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
						<title></title>
						<style>
							.centered {
					  position: fixed;
					  top: 50%;
					  left: 50%;
					  margin-top: -50px;
					  margin-left: -100px;
					}
					.hero-image {
					  background-image: url(\"/images/shipping.jpeg\");
					  background-color: #cccccc;
					  height: 180px;
					  background-position: center;
					  background-repeat: no-repeat;
					  background-size: cover;
					  position: relative;
					}
					
					.hero-text {
					  text-align: center;
					  position: absolute;
					  top: 50%;
					  left: 50%;
					  transform: translate(-50%, -50%);
					  color: white;
					}
					.heading {
						text-align: center;
						background-color: cadetblue;
						padding: 5px;
						margin: 3px;
						color: white;
					}
						</style>
					</head>";
					echo '<body style="overflow-x:none">
					<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
							  <a class="navbar-brand" href="#">
								Shipping Port
							  </a>
							  <button
								class="navbar-toggler"
								type="button"
								data-toggle="collapse"
								data-target="#navbarNav"
								aria-controls="navbarNav"
								aria-expanded="false"
								aria-label="Toggle navigation"
							  >
								<span class="navbar-toggler-icon"></span>
							  </button>
							  <div class="collapse navbar-collapse" id="navbarNav">
								<ul class="navbar-nav">
								  <li class="nav-item active">
									<a class="nav-link" href="#">
									  Home 
									</a>
								  </li>
								  <li class="nav-item">
									<a class="nav-link" href="#">
									  Features
									</a>
								  </li>
								  <li class="nav-item">
									<a class="nav-link" href="#">
									  Pricing
									</a>
								  </li>
								</ul>
								<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
								  <li class="nav-item">
									<a class="nav-link" href="#">
									  Hi Kesiya Raj
									</a>
								  </li>
								  <li class="nav-item">
									<a class="nav-link" href="/">
									  Logout
									</a>
								  </li>
								</ul>
							  </div>
							</nav>
							<div class="hero-image">
					  <div class="hero-text">
						<h1 style="font-size:40px">Shipping Port Management System</h1>
					  </div>
					</div>';
echo "<h3> Start Date: ".$startdate." </h3>";
echo "<h3> End Date: ".$enddate."</h3>";


if ($conn->connect_error){
	die("Connection failed: ". $conn->connect_error);
}

$c_id ="India";
$sql_c= " select Id from COUNTRY where Name = '".$c_id."'";
$id_res = mysqli_query($conn, $sql_c);
$r = $id_res->fetch_assoc();

$Id=$r["Id"];


$sql_p= " select distinct Port_number from PORTS where Id = '".$Id."'";
$p_res = mysqli_query($conn, $sql_p);
$p = $p_res->fetch_assoc();
$port=$p["Port_number"];




$sql= "select Port_number, coalesce (count(*),0) as Total_ships,coalesce(sum(No_of_Containers),0) as Total_containers
						from SHIPS
						where DATE(Arrival_Date) >=  '".$startdate."' and DATE(Departure_Date) <= '".$enddate."' and Port_number ='".$port."'
								and Number in (select Number from SHIPS_Operating_Seq where Operating_Seq like 'AU%')
						group by Port_number;";

 $sql1 ="select Port_number,count(*) as Total_ships,sum(No_of_Containers) as Total_containers
					from SHIPS
					where DATE(Arrival_Date) >= '".$startdate."' and DATE(Departure_Date) <= '".$enddate."'
							and Number in (select Number from SHIPS_Operating_Seq where Operating_Seq like 'AL%') and Port_number ='".$port."'
					group by Port_number;";

					
echo "<h4>Export Report </h4>";
echo "<table border='1' class='table'>
    <tr>
    <th>Port Number</th>
    <th>Total Ships Export</th>
		<th>Total Containers Export</th>

    </tr>";
    $result1 = mysqli_query($conn, $sql1);
		if($result = mysqli_query($conn, $sql))
         {
             if((mysqli_num_rows($result) > 0)){

                 while( $row = $result->fetch_assoc()) {
						       echo "<tr>";
						       echo "<td>". $row["Port_number"] . "</td>";
						       echo "<td>". $row["Total_ships"] . "</td> " ;
									 echo "<td>". $row["Total_containers"] . "</td> " ;
						       echo "</tr>";
						 }
         }
				 else {
					 echo "<tr>";
					 echo "<td>". $port. "</td>";
					 echo "<td>". "0" . "</td> " ;
					 echo "<td>". "0". "</td> " ;
					 echo "</tr>";
				 }
			 }
   else
      {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }

echo "</table>"."<br>";

echo "<h4>Import Report</h4>";

echo "<table border='1' class='table'>
    <tr>
    <th>Port Number</th>
		<th>Total Ships Import</th>
		<th>Total Containers Import</th>

    </tr>";
		if($result1 = mysqli_query($conn, $sql1))
         {
             if(( mysqli_num_rows($result1) > 0)){

                 while($row1 = $result1->fetch_assoc()) {
						       echo "<tr>";
						       echo "<td>". $row1["Port_number"] . "</td>";
									 echo "<td>". $row1["Total_ships"] . "</td> " ;
									 echo "<td>". $row1["Total_containers"] . "</td> " ;
						       echo "</tr>";
             }
         }
				 else {
					 echo "<tr>";
					 echo "<td>". $port. "</td>";
					 echo "<td>". "0" . "</td> " ;
					 echo "<td>". "0". "</td> " ;
					 echo "</tr>";
				 }

			 }
   else
      {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }

echo "</table>"."<br>";
echo '</body>';


?>

<style>
table {
  border-collapse: collapse;
  width: 80%;
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
  background-color: #4CAF50 !important;
  color: white;
}
</style>