<?php 
include 'connect.php';



if (!$_POST){
	$display_block = <<<END_OF_TEXT



<form method="POST" action="$_SERVER[PHP_SELF]">

	<fieldset>
		<legend>First/Last Names</legend></br>

		<input type="text" name="f_name" size="30" 
		maxlength="75" required="required" />
		<input type="text" name="l_name" size="30"
		maxlength="75" required="required" />
	</fieldset>


	<fieldset>

		<legend>Adress</legend></br>

	
		<label for="address">Street Address:</label></br>
		<input type="text" id="address" name="address" 
		size="30" /> 
	

	<p>
		<label for="city">City:</label></br>
		<input type="text" name="city" size="30" maxlength="25" />
	</p>

	</fieldset>



	<fieldset>

		<legend>Telephone Number</legend></br>

		<input name="tel_number" type="text" size="30" maxlength="30" /> 

		<input name="tel_type" type="radio" id="tel_type_h" value="home" checked>
		<label for="tel_type_h">home</label>

		<input name="tel_type" type="radio" id="tel_type_w" value="work" >
		<label for="tel_type_w">work</label>

		<input name="tel_type" type="radio" id="tel_type_o" value="other" >
		<label for="tel_type_o">other</label>

	</fieldset>


	<fieldset>
		
			<legend>Email</legend></br>
			<input name="email" type="email" size="30" maxlength="155" />

		

	</fieldset>


	
		<p><label for ="note">Personal Note</label></br>
			<textarea id="note" name="note" rows="3" cols="35"></textarea>

		</p>
	
		<button type="submit" name="submit" value="send" >Add Entry</button>


</form>
END_OF_TEXT;
}
		else if($_POST){	

			if (($_POST['f_name']=="") || ($_POST['l_name']=="")){

					header ("location: addentry.php");
					exit;

			}

		DB();

			$safe_f_name = mysqli_real_escape_string($mysqli,$_POST['f_name']);
			$safe_l_name = mysqli_real_escape_string($mysqli,$_POST['l_name']);
			$safe_address = mysqli_real_escape_string($mysqli,$_POST['address']);
			$safe_city = mysqli_real_escape_string($mysqli,$_POST['city']);
			$safe_tel_number = mysqli_real_escape_string($mysqli,$_POST['tel_number']);
			$safe_email = mysqli_real_escape_string($mysqli,$_POST['email']);
			$safe_note = mysqli_real_escape_string($mysqli,$_POST['note']);
			

		//add to master_name table

			$add_master_sql = "INSERT INTO master_name (date_added,date_modified,f_name,l_name) 
				VALUES ( now(),now(),'".$safe_f_name."','".$safe_l_name."')";

			$add_master_res = mysqli_query($mysqli,$add_master_sql)
						or die (mysqli_error($mysqli));

				$master_id = mysqli_insert_id($mysqli);



		if((!$_POST['address']=="") || (!$_POST['city']=="")){

					$add_address_sql = "INSERT INTO address (master_id,date_added,date_modified,address,city)
					VALUES ('".$master_id."',now(),now(),'".$safe_address."','".$safe_city."')";


				$add_address_res = mysqli_query($mysqli,$add_address_sql)
						or die (mysqli_error($mysqli));


			}			

		if ($_POST['tel_number']){

					$add_tel_number_sql = "INSERT INTO telephone (master_id,date_added,date_modified,
											tel_number,type)
										VALUES 
										('".$master_id."',now(),now(),'".$safe_tel_number."','".$_POST['tel_type']."')";

							$add_tel_number_res = mysqli_query($mysqli,$add_tel_number_sql)
											or die (mysqli_error($mysqli));

			}



		if($_POST['email']){

				$add_email_sql = "INSERT INTO email (master_id,date_added,date_modified,email)
							VALUES
							('".$master_id."',now(),now(),'".$safe_email."')";
				$add_email_res = mysqli_query($mysqli,$add_email_sql)
								or die (mysqli_error($mysqli));

		}	



		if ($_POST['note']){

				$add_note_sql = "INSERT INTO personal_notes (master_id,date_added,date_modified,note)
									VALUES
									('".$master_id."',now(),now(),'".$safe_note."')";

				$add_note_res = mysqli_query($mysqli,$add_note_sql)	
							or die (mysqli_error($mysqli));

		}

		mysqli_close($mysqli);

		$display_block = "<p>Your entry has been added!</br>
						Would you like to <a href=\"addentry.php\">add another</a>?</p>";
	}

?>

<!DOCTYPE html>
<head>
<title>Add</title>
</head>
<body>
<h1>Add an Entry</h1>
<?php echo $display_block; ?>
</body>
<footer>
	<a href = "menu.html">Main</a>
	<a href = "selentry.php">Select</a>
	<a href ="delentry.php">Delete</a>
</footer>
</html>