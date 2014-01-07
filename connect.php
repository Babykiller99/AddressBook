<?php 

		function DB() {

			global $mysqli;

			$mysqli = mysqli_connect('localhost','root','','AB');

			if (mysqli_connect_errno()){
				printf("connection failed: %s \n", mysqli_errno());
				exit();
			}
			
			
		}

?>