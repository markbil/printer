<?php


	$checkinId = $_GET['checkinId'];

	require_once ('../API/mysql_connection.php');
	require_once ('../API/timezone.php');
	
	$query_date = "SELECT * FROM view_list_distinctusercheckins_all";
	$result_date = mysql_query($query_date) or die ("Could not execute query" . mysql_error());
	
	$lastpost_date = '';
	$row_date = mysql_fetch_array($result_date);
	$lastpost_date = $row_date['check_in_time'];

	
	$tmz = mysql_query ("SET time_zone = " . $timezone) or die("mysql error setting timezone: " . mysql_error());
	
	//firstname, lastname, skills, help, catagories, mainlocation, sublocation, check_in_time, months_since_checkin, days_since_checkin, hours_since_checkin, minutes_since_checkin
//	$query = "SELECT * FROM view_list_distinctusercheckins_all";
	$query = "SELECT * FROM (view_list_distinctusercheckins_all vc JOIN check_in ci ON vc.rfid = ci.ThirdPartyId) WHERE ci.CheckInID =" . $checkinId;
	$result = mysql_query($query) or die ("Could not execute query: ". mysql_error());
	
	while($row = mysql_fetch_array($result)) {
		extract($row);
		 
		//         $user_id = $obj['user_id'];
		$avatarurl = "";
		$userstatus = "";
		$email = "";
		$twitter = "";
		$goodreadsurl = "";
		$blog = "";
		$wp_user_login = "";
	
		//get email
		$email_query = "SELECT email FROM user_email WHERE user_id = " . $user_id;
		$rs_email = mysql_query ($email_query) or die("mysql error: " . mysql_error());
		while ($get_field = mysql_fetch_assoc($rs_email)){
			$email = $get_field['email'];
		}
		//get twitter
		$twitter_query = "SELECT twitter FROM user_twitter WHERE user_id = " . $user_id;
		$rs_twitter = mysql_query ($twitter_query) or die("mysql error: " . mysql_error());
		while ($get_field = mysql_fetch_assoc($rs_twitter)){
			$twitter = $get_field['twitter'];
		}
		//get blog
		$blog_query = "SELECT blog FROM user_blog WHERE user_id = " . $user_id;
		$rs_blog = mysql_query ($blog_query) or die("mysql error: " . mysql_error());
		while ($get_field = mysql_fetch_assoc($rs_blog)){
			$blog = $get_field['blog'];
		}
		//get avatarurl
		$avatarurl_query = "SELECT avatarurl FROM user_avatarurl WHERE user_id = " . $user_id;
		$rs_avatarurl = mysql_query ($avatarurl_query) or die("mysql error: " . mysql_error());
		while ($get_field = mysql_fetch_assoc($rs_avatarurl)){
			$avatarurl = $get_field['avatarurl'];
		}
		//get status
		$status_query = "SELECT status FROM user_status WHERE user_id = " . $user_id;
		$rs_status = mysql_query ($status_query) or die("mysql error: " . mysql_error());
		while ($get_field = mysql_fetch_assoc($rs_status)){
			$status = $get_field['status'];
		}
		//get goodreadsurl
		$goodreadsurl_query = "SELECT goodreadsurl FROM user_goodreadsurl WHERE user_id = " . $user_id;
		$rs_goodreadsurl = mysql_query ($goodreadsurl_query) or die("mysql error: " . mysql_error());
		while ($get_field = mysql_fetch_assoc($rs_goodreadsurl)){
			$goodreadsurl = $get_field['goodreadsurl'];
		}
		 
		//get aboutme
		$aboutme_query = "SELECT aboutme FROM user_aboutme WHERE user_id = " . $user_id;
		$rs_aboutme = mysql_query ($aboutme_query) or die("mysql error: " . mysql_error());
		while ($get_field = mysql_fetch_assoc($rs_aboutme)){
			$aboutme = $get_field['aboutme'];
		}

		//get userid
		$userid_query = "SELECT user_login FROM wp_users WHERE id = " . $user_id;
		$rs_userid = mysql_query ($userid_query) or die("mysql error: " . mysql_error());
		while ($get_field = mysql_fetch_assoc($rs_userid)){
			$wp_user_login = $get_field['user_login'];
		}
		
		$timepassed = "";
		if($months_since_checkin > 0) $timepassed = $months_since_checkin . " months";
		else if($days_since_checkin > 0) $timepassed = $days_since_checkin . " days";
		else if($hours_since_checkin > 0) $timepassed = $hours_since_checkin . " hours";
		else if($minutes_since_checkin >= 0) $timepassed = $minutes_since_checkin . " minutes";

		
		
		$allinterests_keywords = array();
		$pieces_interests = str_replace("\n", ",", $categories);
		$pieces_interests = explode(",", $pieces_interests);
		$pieces_interests = array_map('trim',$pieces_interests);
		for($i = 0, $size = count($pieces_interests); $i < $size; ++$i) {
			if ($pieces_interests[$i] == ""){
				unset($pieces_interests[$i]);
			}
		}
		
		$allskills_keywords = array();
		$pieces_skills = str_replace("\n", ",", $skills);
		$pieces_skills = explode(",", $pieces_skills);
		$pieces_skills = array_map('trim',$pieces_skills);
		for($i = 0, $size = count($pieces_skills); $i < $size; ++$i) {
			if ($pieces_skills[$i] == ""){
				unset($pieces_skills[$i]);
			}
		}
		
		$allhelp_keywords = array();
		$pieces_help = str_replace("\n", ",", $help);
		$pieces_help = explode(",", $pieces_help);
		$pieces_help = array_map('trim',$pieces_help);
		for($i = 0, $size = count($pieces_help); $i < $size; ++$i) {
			if ($pieces_help[$i] == ""){
				unset($pieces_help[$i]);
			}
		}
		
		$allhelp_keywords = array_merge($allhelp_keywords, $pieces_help);
		$allinterests_keywords = array_merge($allinterests_keywords, $pieces_interests);
		$allskills_keywords = array_merge($allskills_keywords, $pieces_skills);
		
		$help = implode(", ", $allhelp_keywords);
		$categories = implode(", ", $allinterests_keywords);
		$skills = implode(", ", $allskills_keywords);
		
		
		
	}
	
?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <!-- 1.  <link rel="stylesheet" href="http://printer.gofreerange.com/stylesheets/print.css" type="text/css" media="screen" title="no title" charset="utf-8">--> 
    <!-- 1. --> <link rel="stylesheet" href="print.css" type="text/css" media="screen" title="no title" charset="utf-8">
    <!-- 2. --> <script src="http://printer.gofreerange.com/javascripts/printer.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
      $(function() {
        $("#previewPage").click(Printer.previewPage);
        $("#printPage").click(function() {
          var printerID = prompt("Enter the ID of the printer to target");
          Printer.printPage(printerID, function(result) {
            if (result.response == "ok") {
              alert("Page successfully sent for printing");
            } else {
              alert("There was a problem sending this content");
              console.log("Error response", result);
            }
          });
        });
      })
    </script>
  </head>
  <body class="preview"> <!-- 3. -->
    <div class="controls">
      <a id="previewPage" href="#">Preview</a>
      <a id="printPage" href="#">Print</a>
    </div>
    <div class="paper"> <!-- 4. -->
      <div class="content">
            
        <h1><?php echo $firstname . ' '  . $lastname . ' just checked in at </br>' . $sublocation . ',</br> say hi! ' ?></h1>
        <div class="textright"><?php echo $check_in_time ?></div>

        <div class="status">
			<?php echo 'mood today:' ?>
	        <?php echo ' ' . $status ?>
        </div>
        <p class="section">
        	<span class="label"><?php echo 'about '. $firstname . ':'?></span>
        	<span class="keywords"><?php echo ' ' . $aboutme ?></span>
        </p>
        <p>
        	<span class="label"><?php echo 'interests:' ?></span>
        	<span class="keywords"><?php echo ' ' . $categories ?></span>
        </p>
        <p>
        	<span class="label"><?php echo 'skills:' ?></span>
        	<span class="keywords"><?php echo ' ' . $skills ?></span>
		</p>
		<p>
        	<span class="label"><?php echo 'needs help with:' ?></span>
        	<span class="keywords"><?php echo ' ' . $help ?></span>
        </p>
        
        <p>
			<div class="status"><?php if ($email != '' || $twitter != '' || $blog != '' || $wp_user_login != '') echo 'Contact:' ?></div>
			<div class="textright"><?php if ($email != '') echo $email ; ?></div>
			<div class="textright"><?php if ($twitter != '') echo $twitter; ?></div>
			<div class="textright"><?php if ($blog != '') echo $blog; ?></div>
			<div class="textright"><?php //if ($wp_user_login != '') echo 'http://theedge.checkinsystem.net/author/' . $wp_user_login; ?></div>
			
        </p>
        </br>
        </br>
        </br>
        <p><?php //echo $blog ?></p>
        <p><?php //echo $twitter ?></p>
        <p><?php //echo $goodreadsurl ?></p>
        <p><?php //echo $avatarurl ?></p>
        <p><?php //echo $timepassed ?></p>
        <p class="endnote"> find out more at http://theedge.checkinsystem.net/</p>
                        
        </div>
    </div>
  </body>
</html>


