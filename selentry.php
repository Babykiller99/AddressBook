<?php 
include 'connect.php';
DB();



		if (!$_POST){

			$display_block="<h1>Please Select an Entry</h1>";

			$get_list_sql = "SELECT id,
							CONCAT_WS(' ', l_name, f_name) AS display_name
							FROM master_name ORDER BY l_name, f_name";

			$get_list_res = mysqli_query($mysqli,$get_list_sql)
								or die (mysqli_error($mysqli));


				if (mysqli_num_rows($get_list_res) < 1) {

						$display_block .= "<p><em>Sorry no Records to Select</em><p>";

				}

				else {

					$display_block .= "

						<form method=\"POST\" action=\" ".$_SERVER['PHP_SELF']."\">
						<p><label for=\"sel_id\">Select a Record:</label><br/>
						<select id=\"sel_id\" name=\"sel_id\" required=\"required\">
						<option value=\"\">-- Select One --</option>";


						while ($recs = mysqli_fetch_array($get_list_res)){

								$id = $recs['id'];
								$display_name = stripslashes($recs['display_name']);
								$display_block .= "<option value = \" ".$id." \">".$display_name."</option>";

						}
					$display_block .= "</select>

										<button type=\"submit\" name=\"submit\" value=\"view\">
										View Selected Entry</button>
										</form>";
						}

						mysqli_free_result($get_list_res);
		}

			else if ($_POST){

				if ($_POST['sel_id']==""){

					header("location: selentry.php");
					exit();
			
				}

				$safe_id = mysqli_real_escape_string($mysqli,$_POST['sel_id']);


				$get_master_sql= "SELECT CONCAT_WS(' ',f_name,l_name) as display_name from master_name
									Where id = '".$safe_id."'";
				$get_master_res= mysqli_query($mysqli,$get_master_sql)
										or die (mysqli_error($mysqli));

						while ($name_info = mysqli_fetch_array($get_master_res)){

							$display_name = stripslashes($name_info['display_name']);
						}
				
					$display_block = "<h1>Showing Record for ".$display_name."</h1>";

					mysqli_free_result($get_master_res);



					$get_addresses_sql="SELECT address,city from address
										WHERE master_id = ' ".$safe_id." ' ";
					$get_addresses_res=mysqli_query($mysqli,$get_addresses_sql)
										or die(mysqli_error($mysqli));


						if (mysqli_num_rows($get_addresses_res) > 0) {

								$display_block .= "<p><strong>Adress:</strong></p></br>
								  <ul>";


								  while ($add_info = mysqli_fetch_array($get_addresses_res)){

								  	$address = stripslashes($add_info['address']);
								  	$city = stripslashes($add_info['city']);

								  	$display_block .=  "<li>$address</li>
								  					   <li>$city</li>";

								  }

								  $display_block .= "</ul>";

								  mysqli_free_result($get_addresses_res);


						}



					$get_tel_sql = "SELECT tel_number, type FROM telephone
									WHERE master_id ='".$safe_id."' ";
					$get_tel_res = mysqli_query($mysqli,$get_tel_sql)
									or die(mysqli_error($mysqli));


						if (mysqli_num_rows($get_tel_res) > 0 ){

							$display_block .= "<p><strong>Telephone Number:</strong></br>
											<ul>";

							while($tel_info =   mysqli_fetch_array($get_tel_res)){
							$tel_number = stripslashes($tel_info['tel_number']);
							$tel_type = $tel_info['type'];


							$display_block .= "<li>$tel_number ($tel_type)</li>";

						}

						$display_block .="</ul>";
						mysqli_free_result($get_tel_res);
					}

					$get_email_sql = "SELECT email FROM email WHERE
						master_id = '".$safe_id."'";
					$get_email_res = mysqli_query($mysqli, $get_email_sql)
						or die(mysqli_error($mysqli));
						if (mysqli_num_rows($get_email_res) > 0) {
						$display_block .= "<p><strong>Email:</strong><br/>
						<ul>";
						while ($email_info = mysqli_fetch_array($get_email_res)) {
						
						$email = stripslashes($email_info['email']);
						$display_block .= "<li>$email</li>";


						}
							$display_block .= "</ul>";
							}
							
							mysqli_free_result($get_email_res);



				$get_notes_sql = "SELECT note FROM personal_notes
								WHERE master_id = '".$safe_id."'";
				$get_notes_res = mysqli_query($mysqli,$get_notes_sql)
							or die (mysqli_error($mysqli));


					if (mysqli_num_rows($get_notes_res)>0){

						$display_block .= "<p><strong>Notes:</strong></br>";

					}	
						while ($note_info =mysqli_fetch_array($get_notes_res)){

							$note = nl2br(stripslashes($note_info['note']));

						}		

							$display_block .="$note</p>";

					mysqli_free_result($get_notes_res);


					$display_block .= "<br/>
					<p style=\"text-align:center\">
					<a href=\"".$_SERVER['PHP_SELF']."\">select another</a></p>";


			}

	mysqli_close($mysqli);
?>


<!DOCTYPE html>
<html>
<head>
<title>Records</title>
</head>
<body>

<?php echo "$display_block"; ?>

</body>
<footer>
	<a href = "menu.html">Main</a>
	<a href = "selentry.php">Select</a>
	<a href ="delentry.php">Delete</a>
</footer>
</html>






