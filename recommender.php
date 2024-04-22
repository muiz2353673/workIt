<!DOCTYPE html>
<html>
<head>
	<title>Workit - Your Reccommendation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
  <style>
    .wow:first-child {
      visibility: hidden;
    }
  </style>
  
</head>
<body>
 
 <!-- Start Header  -->
 <header>
 	 <div class="container">
 	 	<div class="logo">
 	 		 <a href="">WORK<span>.IT</span></a>
 	 	</div>
 	 	<a href="javascript:void(0)" class="ham-burger">
 	       <span></span>	
 	       <span></span>
 	 	</a>
 	 	<div class="nav">
 	 		<ul>
 	 			<li><a href="#home">Home</a></li>
 	 			<li><a href="#about">About</a></li>
 	 			<li><a href="#service">AI Recommender</a></li>
 	 			<li><a href="#classes">Classes</a></li>
 	 			<li><a href="#schedule">Schedule</a></li>
 	 			<li><a href="#login">Login</a></li>
 	 		</ul>
 	 	</div>
 	 </div>
 </header>
 <!-- End Header  -->

  <!-- AI Recommender -->
  <section class="service" id="service">
 	<div class="container">
 		 <div class="content">
 		 	  <div class="text box wow slideInLeft">
                  <h2>AI Recommender</h2>
                  <div class="ai-form">
					<label for="type">Workout Type:</label>
					<select name="workout-type" id="type">
						<option value="Strength">Strength</option>
						<option value="Plyometrics">Plyometrics</option>
						<option value="Cardio">Cardio</option>
						<option value="Stretching">Stretching</option>
						<option value="Powerlifitng">Powerlifting</option>
						<option value="Strongman">Strongman</option>
						<option value="Olympic Weightlifitng">Olympic Weightlifitng</option>
					</select>

					<label for="body-part">Body Part:</label>
					<select name="part" id="body-part">
						<option value="abdominals">Abdominals</option>
						<option value="abductors">Abductors</option>
						<option value="biceps">Biceps</option>
						<option value="Legs">Legs</option>
						<option value="Chest">Chest</option>
						<option value="Forearms">Forearms</option>
						<option value="lats">Lats</option>
						<option value="lower-back">Lower-Back</option>
						<option value="middle-back">Middle-Back</option>
						<option value="Traps">Traps</option>
						<option value="Neck">Necks</option>
						<option value="Shoulder">Shoulder</option>
						<option value="Triceps">Triceps</option>
					</select>

					<label for="eqipment">Equipment:</label>
					<select name="dquipment" id="equipment">
						<option value="bands">Bands</option>
						<option value="barbell">Barbell</option>
						<option value="Kettlebells">Kettlebells</option>
						<option value="Dumbbells">Dumbbells</option>
						<option value="Other">Other</option>
						<option value="Cable">Cable</option>
						<option value="Machine">Machine</option>
						<option value="Body-only">Body only</option>
						<option value="Medicine">Medicine Ball</option>
						<option value="exercises">Exercise</option>
						<option value="Foam">Foam Roll</option>
						<option value="E-Z Curl">E-Z Curl Bar</option>
					</select>

					<label for="level">Difficulty:</label>
					<select name="levels" id="level">
						<option value="begin">Beginner</option>
						<option value="inter">Intermediate</option>
						<option value="Expert">Expert</option>
					</select>

					<label for="ratings">Rating:</label>
					<select name="rating" id="ratings">
						<option value="begin">High-to-Low</option>
						<option value="inter">Low-to-High</option>
						
					</select>



				  </div>
                  <a href="" class="btn">Search</a>
 		 	  </div>
 		 	  
 		 </div>
 	</div>
 </section>
 <!-- End Service -->

<!-- Start Results -->
<section class="price-package" id="price">
    <div class="container">
        <h2>Choose Your Workout</h2>
        <p class="title-p">Select a workout from the following results below</p>
        <div class="content">
            <?php
            // SSH credentials
            $ssh_host = 'selene.hud.ac.uk';  // SSH host
            $ssh_port = 22;  // SSH port
            $ssh_user = 'workit';  // SSH username
            $ssh_pass = 'umbra(despair>Quartz218';  // SSH password

            // Create SSH tunnel
            $connection = ssh2_connect($ssh_host, $ssh_port);
            if (ssh2_auth_password($connection, $ssh_user, $ssh_pass)) {
                // Forward a local port to the remote MySQL server
                $tunnel = ssh2_tunnel($connection, 'localhost', 3306);
                if ($tunnel) {
                    // Connect to the MySQL server over the SSH tunnel
                    $conn = new mysqli('127.0.0.1', 'workit', 'umbra(despair>Quartz218', 'workit');
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Array of 10 IDs
                    $ids = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

                    // Fetch data from the database for the specified IDs
                    $sql = "SELECT Title, Type, Level, Equipment, Rating FROM megaGymDataset WHERE ID IN (" . implode(',', $ids) . ")";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output each price package dynamically
                        $package_count = 0; // Initialize counter for number of packages

                        while ($row = $result->fetch_assoc()) {
                            // Start a new row before outputting each package if needed
                            if ($package_count % 3 == 0) {
                                echo '<div class="row">';
                            }

                            echo '<div class="col-md-4">';
                            echo '<div class="box wow bounceInUp">';
                            echo '<div class="inner">';
                            echo '<div class="price-tag">' ."Rating: ". $row["Rating"]."/10". '</div>';
                            echo '<div class="img">';
                            echo '<img src="images/price1.jpg" alt="price" />';
                            echo '</div>';
                            echo '<div class="text">';
                            echo '<h3>' . $row["Title"] . '</h3>';
                            echo '<p>Type: ' . $row["Type"] . '</p>';
                            echo '<p>Level: ' . $row["Level"] . '</p>';
                            echo '<p>Equipment: ' . $row["Equipment"] . '</p>';
                            echo '<a href="" class="btn">Start Workout</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';

                            // Increment package count
                            $package_count++;

                            // Close the row after every 3 packages
                            if ($package_count % 3 == 0) {
                                echo '</div>'; // Close row
                            }
                        }

                        // Close the row if the total number of packages is not a multiple of 3
                        if ($package_count % 3 != 0) {
                            echo '</div>'; // Close row
                        }
                    } else {
                        echo "0 results";
                    }

                    // Close the connection
                    $conn->close();
                } else {
                    echo "SSH tunnel creation failed.";
                }
            } else {
                echo "SSH authentication failed.";
            }
            ?>
        </div>
    </div>
</section>
<!-- End Results -->


<!-- Start Contact -->
<section class="contact" id="login">
    <div class="container">
        <div class="content">
            <div class="box form wow slideInLeft">
                <h3 class="login-heading">Login</h3>
                <form>
                    <input type="text" placeholder="Email or Phone">
                    <input type="password" placeholder="Password">
                    <button type="submit">Login</button>
                </form>
            </div>
            <div class="box text wow slideInRight">
                <h2>Get Connected with Gym</h2>
                <p class="title-p">Create an account to further enhance your workout and gain more suited experience.</p>
                <div class="info">
                    <ul>
                        <li><span class="fa fa-map-marker"></span>Sparck Jones Building, University of Huddersfield, HD1 3BZ</li>
                        <li><span class="fa fa-phone"></span> 0123456789</li>
                        <li><span class="fa fa-envelope"></span> workit@gym.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Contact -->




 <!-- jquery -->

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
	$(document).ready(function(){

      $(".ham-burger, .nav ul li a").click(function(){
       
        $(".nav").toggleClass("open")

        $(".ham-burger").toggleClass("active");
      })      
      $(".accordian-container").click(function(){
      	$(".accordian-container").children(".body").slideUp();
      	$(".accordian-container").removeClass("active")
      	$(".accordian-container").children(".head").children("span").removeClass("fa-angle-down").addClass("fa-angle-up")
      	$(this).children(".body").slideDown();
      	$(this).addClass("active")
      	$(this).children(".head").children("span").removeClass("fa-angle-up").addClass("fa-angle-down")
      })

       $(".nav ul li a, .go-down").click(function(event){
         if(this.hash !== ""){

              event.preventDefault();

              var hash=this.hash; 

              $('html,body').animate({
                scrollTop:$(hash).offset().top
              },800 , function(){
                 window.location.hash=hash;
              });

              // add active class in navigation
              $(".nav ul li a").removeClass("active")
              $(this).addClass("active")
         }
      })
})

</script>
<script src="js/wow.min.js"></script>
<script>
    wow = new WOW(
      {
        animateClass: 'animated',
        offset:       0,
      }
    );
    wow.init();

	

	  

  </script>
</body>
</html>