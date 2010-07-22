<?php
/***************************************************************************
File Name 	: index.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Brings together all the elements of the Support Tickets app.
Date Created	: Friday 23 April 2004 20:30:29
File Version	: 1.8
\\||************************************************************************/

	session_start();

	// INCLUDE THE CONFIG AND FUNCTIONS AND LANGUAGE FILE

	include ('modules/'.$_GET[name].'/config.php');
	include ('modules/'.$_GET[name].'/class/functions.php');
	include ('modules/'.$_GET[name].'/language/lang-english.php');

#############################################################################################
####################     DISPLAY THE PAGE TITLE AND NAVIGATION    ###########################
#############################################################################################
function DoMenu()
{
	include("header.php");
	OpenTable();
?>
	<table width="100%" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
	  <tr bgcolor="<?php echo $backout ?>">
		<td class="boxborder" bgcolor="<?php echo $background ?>" onmouseover="window.status='Home';return true;" onmouseout="window.status=' ';return true;">
			<p><a href="<?php echo $_SERVER['PHP_SELF'] ?>?name=<?php echo $_GET[name]?>">Home</a> - Support Tickets Manager</p></td>
		<td class="boxborder" width="15%" onmouseover="this.style.background='<?php echo $backover ?>';window.status='New Ticket';return true;" onmouseout="this.style.background='<?php echo $backout ?>';window.status=' ';return true;" align="center">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?name=<?php echo $_GET[name]?>&amp;caseid=NewTicket"><span class="toplink">New Ticket</span></a></td>
		<td class="boxborder" width="15%" onmouseover="this.style.background='<?php echo $backover ?>';window.status='Open Tickets';return true;" onmouseout="this.style.background='<?php echo $backout ?>';window.status=' ';return true;" align="center">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?name=<?php echo $_GET[name]?>&amp;caseid=home&amp;order=Open"><span class="toplink">Open Tickets</span></a></td>
		<td class="boxborder" width="15%" onmouseover="this.style.background='<?php echo $backover ?>';window.status='Closted Tickets';return true;" onmouseout="this.style.background='<?php echo $backout ?>';window.status=' ';return true;" align="center">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?name=<?php echo $_GET[name]?>&amp;caseid=home&amp;order=Closed"><span class="toplink">Closed Tickets</span></a></td>
	  </tr>
	</table>

<?php
}
#############################################################################################
##########     HOME DEFAULT CASE THIS DEALS WITH THE DISPLAYING OF ANY TICKETS    ###########
###########################d##################################################################

		function home()
		{
			DoMenu();
			global $db, $maintablewidth, $backover, $backout, $background, $maintablealign, $sess_uid, $prefix, $tr_color1;

			IF (!isset($_GET['order']) && !isset($_POST['keywords']))
				$_GET['order'] = 'Open';

	// PROCESS THE FUNCTIONS WHEN THE CHECKBOXES ARE CHECKED - IE OPEN CLOSE TICKET

			IF (isset($_POST['status']))
				{
				IF (isset($_POST['ticket']))
					{
					FOREACH ($_POST['ticket'] AS $ticketid)
						{
						$query = "	UPDATE ".$prefix."_hosting_tickets_tickets
								SET tickets_status = '".$_POST['status']."'
								WHERE tickets_id = '".$ticketid."'";

						IF ($db->sql_query($query))
							$msg = 'Ticket '.$_POST['status'];
							ELSE
								$msg = 'This could not be done at this time';
						}
					}
					ELSE
						$msg = 'Please select a Ticket.';
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AACCEE">
					<td><p><?php echo $msg ?></p></td>
				  </tr>
				</table>
<?php
				}

	// QUERY TO SELECT THE TICKETS LISTING - THIS CAN BE CHANGED TO OPEN OR CLOSED ONLY
	// DEPENDING ON THE LINK THAT IS HIT ON THE NAV BAR - HOME PAGE DEFAULTS TO BOTH.

			$query = "	SELECT tickets_id, tickets_subject, tickets_timestamp, tickets_status, tickets_status_name, tickets_status_color, tickets_categories_name
					FROM ".$prefix."_hosting_tickets_tickets a, ".$prefix."_hosting_tickets_status b, ".$prefix."_hosting_tickets_categories c
					WHERE a.tickets_uid = '".$sess_uid."'
					AND a.tickets_child = '0'
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id";

			IF (isset($_GET['order']))
				{
				$query .= "	AND a.tickets_status = '".$_GET['order']."'";
				$addon = '&amp;order='.$_GET['order'];
				}
			ELSEIF (isset($_POST['keywords']))
				{
				$query .= "	AND a.tickets_subject LIKE '%".$_POST['keywords']."%'";
				$addon = '';
				}

			$query .= '	ORDER BY a.tickets_id DESC, a.tickets_timestamp DESC';

			$result = $db->sql_query($query);
			$totaltickets = $db->sql_numrows($result);
?>
			<div style="padding-top:5"></div>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr bgcolor="<?php echo $background ?>">
				<td class="boxborder"><p>Recent Tickets
<?php
			IF ($totaltickets > '0')
				echo ' '.$totaltickets.' - Click on the Ticket Number to read the ticket.';
				ELSE
					echo ' 0';
?>
				</p></td>
			  </tr>
			</table>
<?php
			IF ($totaltickets > '0')
				{
?>
				<script language="javascript">
				<!--
				function check_all()
					{
					for (var c = 0; c < document.myform.elements.length; c++)
					  	{
				  		if (document.myform.elements[c].type == 'checkbox')
						    	{
							if(document.myform.elements[c].checked == true)
								{
								document.myform.elements[c].checked = false;
								}
								else
									{
									document.myform.elements[c].checked = true;
									}
							}
						}
					}
				// -->
				</script>

				<form name="myform" action="modules.php?name=<?php echo $_GET[name]?>&amp;caseid=home<?php echo $addon ?>" method="post">
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr align="center" bgcolor="<?php echo $background ?>">
					<td class="boxborder"><p><b>Ticket ID</b></p></td>
					<td class="boxborder"><p><b>Replies</b></p></td>
					<td class="boxborder"><p><b>Subject</b></p></td>
					<td class="boxborder"><p><b>Date / Time</b></p></td>
					<td class="boxborder"><p><b>Urgency</b></p></td>
					<td class="boxborder"><p><b>Department</b></p></td>
					<td class="boxborder"><p><b>Status</b></p></td>
				  </tr>
<?php
	// LOOP THROUGH THE REQUESTS FOR THE USERS ACCOUNT

				WHILE ($row = $db->sql_fetchrow($result))
					{
	// QUERY TO GET THE AMOUNT OF REPLIES TO A CERTAIN TICKET AND DATE OF LAST ENTRY

					$queryA = "	SELECT COUNT(*) FROM ".$prefix."_hosting_tickets_tickets WHERE tickets_child = '".$row[tickets_id]."'";

					$resultA = $db->sql_query($queryA);
					$rowA = $db->sql_fetchrow($resultA);

					IF ($rowA['0'] <= '0')
						$rowA['0'] = '0';
//	508		<td class="boxborder" onclick="check_all();" style="cursor:hand"><p><b><u>All</u></b></p></td>
//	540		<td class="boxborder"><input type="checkbox" name="ticket[]" value="<?php echo $tickets_id 
?>
					<tr align="center" bgcolor="<?php echo UseColor() ?>">
						<td class="boxborder" bgcolor="<?php echo $backout ?>" onmouseover="this.style.background='<?php echo $backover ?>';window.status='Read Ticket';return true;" onmouseout="this.style.background='<?php echo $backout ?>';window.status=' ';return true;"><p>
							<a href="<?php echo $_SERVER['PHP_SELF'] ?>?name=<?php echo $_GET[name]?>&amp;caseid=view&amp;ticketid=<?php echo $row[tickets_id] ?>" style="width:100%"><?php echo $row[tickets_id] ?></a>
						</p></td>
						<td class="boxborder"><p>[<?php echo $rowA['0'] ?>]</p></td>
						<td class="boxborder"><p><?php echo $row[tickets_subject] ?></p></td>
						<td class="boxborder"><p><?php echo $row[tickets_timestamp] ?></p></td>
						<td class="boxborder" bgcolor="#<?php echo $row[tickets_status_color] ?>"><p><?php echo $row[tickets_status_name] ?></p></td>
						<td class="boxborder"><p><?php echo $row[tickets_categories_name] ?></p></td>
						<td class="boxborder"><p>
<?php
					IF ($row[tickets_status] == 'Closed')
						echo '<span style="color:#FF0000">';
						ELSE
							echo '<span style="color:#000000">';

					echo 		$row[tickets_status].'</span></p></td>
						  </tr>';
					}
?>

				</table>
				</form>
<?php
				}

	// IF THERE ARE NO TICKETS TO SHOW THEN PLACE A DEFAULT MESSAGE

				ELSE
					{
					IF (isset($_POST['keywords']))
						$msg = 'Sorry but the search returned Zero results please try again.';
						ELSE
							$msg = 'You have no recent tickets for your account.';
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
					  <tr>
						<td><p><?php echo $msg ?></p></td>
					  </tr>
					</table>
<?php
					}
		}


#############################################################################################
######   VIEW A TICKET - VALID IF THE USER CLICKS LINK FROM SEARCH OR HOME LISTINGS   #######
#############################################################################################

		function view()
		{
			DoMenu();
	global $db, $socketfrom, $socketfromname, $maintablewidth, $maintablealign, $sess_uid, $prefix, $cookie, $tr_color1;

	// CLOSE OR REOPEN A TICKET

			IF (isset($_GET['closesub']))
				{
				$query = "	UPDATE ".$prefix."_hosting_tickets_tickets
						SET tickets_status = '".$_GET['closesub']."'
						WHERE tickets_id = '".$_GET['ticketid']."'";

				IF ($db->sql_query($query))
					$msg = 'Ticket '.$_GET['closesub'];
					ELSE
						$msg = 'This could not be done at this time';
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AACCEE">
					<td><p><?php echo $msg ?></p></td>
				  </tr>
				</table>
<?php
				}

	// INSERT THE TICKET INTO THE DATABASE AND SEND THE EMAIL

			IF (isset($_GET['sub']))
				{
				IF ($_POST['message'] == '')
					$msg = 'Please complete all the fields';
					ELSE
						{
						$urgency = explode('|', $_POST['posturgency']);
						$category = explode('|', $_POST['postcategory']);
						$date = getdate();
						$query = "	INSERT INTO ".$prefix."_hosting_tickets_tickets
								SET
								tickets_uid  = '".$sess_uid."',
								tickets_subject = '".$_POST['postsubject']."',
								tickets_timestamp = '".$date[mon]."/".$date[mday]."/".$date[year]." - ".$date[hours].":".$date[minutes].":".$date[seconds]."',
								tickets_urgency = '".$urgency['0']."',
								tickets_category = '".$category['0']."',
								tickets_child = '".$_GET['ticketid']."',
								tickets_admin = '".$cookie[1]."',
								tickets_question  = '".addslashes($_POST['message'])."'";

						IF ($result = $db->sql_query($query))
							{

	// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS
								$result = $db->sql_query("SELECT tickets_id FROM ".$prefix."_hosting_tickets_tickets WHERE tickets_uid = '".$sess_uid."'");
								while($row=$db->sql_fetchrow($result))
									$lastinsertid = $row[tickets_id];

							IF ($allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
								{
								FileUploadsVerification("$_FILES(userfile)", $lastinsertid);
								}

	// EMAIL ADMINISTRATOR THE TICKET NOTIFICATION

							$message  = "Ticket ID:\t ".$_GET['ticketid']."\n";
							$message .= "Name:\t\t ".$_POST['PostName']."\n";
							$message .= "Subject:\t ".$_POST['postsubject']."\n";
							$message .= "Urgency:\t ".$urgency['1']."\n";
							$message .= "Department:\t ".$category['1']."\n";
							$message .= "Post Date:\t ".$date[mon]."/".$date[mday]."/".$date[year]." - ".$date[hours].":".$date[minutes].":".$date[seconds]."\n";
							$message .= "----------------------------------------------------------------------\n";
							$message .= "Message:\n";
							$message .= stripslashes($_POST['message'])."\n";
							$message .= "----------------------------------------------------------------------\n\n\n";
							$message .= "Previous Thread Messages (Latest First):\n";
							$message .= "----------------------------------------------------------------------\n";

	// LOOP THROUGH THE PREVIOUS MESSAGES AND ADD DATA REGARDING QUESTION TIME AND ATTACHMENT

							FOR ($i = count($_POST['ticketquestion']) - 1; $i >= 0; $i--)
								{
								$message .= $_POST['postedby'][$i]." - ".$_POST['postdate'][$i]."\n";
								$message .= stripslashes($_POST['ticketquestion'][$i]);

								IF (isset($_POST['attachment'][$i]) && $_POST['attachment'][$i] != '')
									{
									$message .= "\nAttachment - ".$_POST['attachment'][$i];
									}

								$message .= "\n----------------------------------------------------------------------\n";
								}

							$message .= "\nRegards\n\n";
							$message .= $socketfromname;
							$mailheaders = "From: ".$_POST[email]."\n";
							$mailheaders .= "Reply-To: ".$_POST['email']."\n\n";
							$subject = "Response to Ticket: ".$_GET[ticketid]."\n\n";
							mail($socketfrom, $subject, $message, $mailheaders);

							}
						}
				}

	// QUERY TO GET THE TICKET IN QUESTION AND ANY REPLIES TO THAT TICKET

			$query = "	SELECT tickets_id, tickets_subject, tickets_timestamp, tickets_status, tickets_name, tickets_email, tickets_admin, tickets_child, tickets_question, tickets_status_id, tickets_status_name, tickets_status_color, tickets_categories_id, tickets_categories_name
					FROM ".$prefix."_hosting_tickets_tickets a, ".$prefix."_hosting_tickets_status b, ".$prefix."_hosting_tickets_categories c
					WHERE (a.tickets_id = '".$_GET['ticketid']."'
					OR tickets_child = '".$_GET['ticketid']."')
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id
					AND tickets_uid = '".$sess_uid."'
					ORDER BY tickets_id ASC";

			$result = $db->sql_query($query);
			$totaltickets = $db->sql_numrows($result);
			$row = $db->sql_fetchrow($result);

	// PRINT OUT THE TABLES TO HOLD THE TICKET INFO - REPLY SUBMISSION AND TOPIC AND ANY REPLIES AND ATTACHMENTS
?>
			<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?name=<?php echo $_GET[name]?>&amp;caseid=view&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;sub=add" method="post">
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="4" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td valign="top" width="40%">

				<table width="100%" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="center">
				  <tr bgcolor="<?php echo $tr_color1;?>">
					<td class="boxborder" colspan="3"><p><b>Ticket #<?php echo $_GET['ticketid'] ?> Information</b></p></td>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Username:</b></p></td>
					<td class="boxborder" colspan="2"><p><?php echo $row[tickets_name] ?></p></td>
					
				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Email:</b></p></td>
					<td class="boxborder" colspan="2"><p><?php echo $row[tickets_email] ?></p></td>

				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Subject:</b></p></td>
					<td class="boxborder" colspan="2"><p><?php echo $row[tickets_subject] ?></p></td>
				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Department:</b></p></td>
					<td class="boxborder" colspan="2"><p><?php echo $row[tickets_categories_name] ?></p></td>
				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Urgency:</b></p></td>
					<td class="boxborder" colspan="2" bgcolor="#<?php echo $row[tickets_status_color] ?>"><p><b><?php echo $row[tickets_status_name] ?></b></p></td>
				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Status:</b></p></td>
					<td class="boxborder" colspan="2"><p>
<?php
			IF ($row[tickets_status] == 'Closed')
				echo '<span style="color:#FF0000">';
				ELSE
					echo '<span style="color:#000000">';

			echo		$row[tickets_status];
?>
					</span></p></td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?php 
	IF ($row[tickets_status] != 'Closed')
	{
?>
				<table width="100%" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="center">
				  <tr bgcolor="<?php echo $tr_color1;?>">
					<td class="boxborder"><p><b>Respond</b></p></td>
				  </tr>
				  <tr>
					<td align="right"><p>
					<textarea name="message" cols="45" rows="10"></textarea>
					<input type="hidden" name="PostName" value="<?php echo $row[tickets_name] ?>">
					<input type="hidden" name="email" value="<?php echo $row[tickets_email] ?>">
					<input type="hidden" name="postsubject" value="<?php echo $row[tickets_subject] ?>">
					<input type="hidden" name="posturgency" value="<?php echo $row[tickets_status_id] ?>|<?php echo $row[tickets_status_name] ?>">
					<input type="hidden" name="postcategory" value="<?php echo $row[tickets_categories_id] ?>|<?php echo $row[tickets_categories_name] ?>">
					<input type="submit" value="Submit"/>
					</p></td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?php
	}
		
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

			IF ($allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
				FileUploadForm();
?>
				<br></td>
				<td width="80%" valign="top">
<?php
			$j = '0';
			$result = $db->sql_query($query);

	// LOOP THROUGH THE QUESTIOSN AND RESPONSES TO THIS QUESTION

			WHILE ($row = $db->sql_fetchrow($result))
				{
?>
				<table width="100%" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="center">
				  <tr bgcolor="<?php echo $tr_color1;?>">
					<td width="50%" class="boxborder"><p><b>
<?php
				IF ($j == '0')
					echo '	Dialog Question';
					ELSE
						echo '	Response '.$j;
?>
					</b></p></td>
					<td class="boxborder" bgcolor="<?php echo $tr_color1;?>" width="50%" align="right"><p><?php echo $row[tickets_timestamp] ?></p></td>
				  </tr>
<?php
				IF ($tickets_admin == "".$_SESSION[hosting_uname]."")
					$bgcolor = '#009CE8';
				ELSE
					$bgcolor = '#FFF000';
?>
				  <tr>
					<td class="boxborder" colspan="2"><p><?php echo nl2br($row[tickets_question]) ?></p></td>
				  </tr>
				  <tr>
					<td class="boxborder" align="left" colspan="2"><p>Posted By: <?php echo $row[tickets_admin] ?></p></td>
					
<?php
	// SCAN THE UPLOAD DIRECTORY FOR ATTACHMENTS TO THIS POST IF ATTACHMENTS ARE OFF THEN THIS WONT DO IT

				IF ($allowattachments == 'TRUE')
					{
					$d = dir($uploadpath);
					?><td><p align="right"><?php
					WHILE (false !== ($files = $d -> read()))
						{
						$files = explode('.', $files);

						IF ($files['0'] == $row[tickets_id])
							{
?>
						  	<b>Attachment:</b> <?php echo $files['0'] ?>.<?php echo $files['1'] ?>
							<a href="<?php echo $relativepath.$files['0'] ?>.<?php echo $files['1'] ?>" target="_blank">
							<img src="support/images/download.gif" width="13" height="13" align="absmiddle" border="0" /></a>
<?php
							$filename = $files['0'].'.'.$files['1'];
?>
							<input type="hidden" name="attachment[<?php echo $_GET['ticketid'] - 1 ?>]" value="<?php echo $filename ?>" />
<?php
							}
							ELSE
								$filename = '';
						}

					$d -> close();
					?></p></td><?php
					}
?>
					
				  </tr>
				</table><div style="padding-top:5px"></div>
<?php
				$j ++;
?>
				<input type="hidden" name="ticketquestion[]" value="<?php echo $row[tickets_question] ?>" />
				<input type="hidden" name="postedby[]" value="<?php echo $row[tickets_admin] ?>" />
				<input type="hidden" name="postdate[]" value="<?php echo $row[tickets_timestamp] ?>" />
<?php
				}
?>
				</td>
			  </tr>
			</table>
			</form>
<?php
		}


#############################################################################################
##################################   CREATE A NEWTICKET   ###################################
#############################################################################################

		function NewTicket()
		{
			DoMenu();
			global $db, $socketfrom, $socketfromname, $maintablewidth, $maintablealign, $sess_uid, $prefix, $cookie, $tr_color1;

	// IF THE FORM IS SUBMITTED THEN VERIFY SOME CONTENTS

			IF (isset($_POST[submit]))
				{

	// IF FORM IS NOT FILLED OUT CORRECTLY THEN SHOW ERROR MESSAGES

				IF ($_POST['message'] == '' || $_POST['ticketsubject'] == '')
					{
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
					  <tr>
						<td><br /><blockquote><p>Please complete all the fields.</p></blockquote></td>
					  </tr>
					</table>
<?php
					}

	// IF FORM IS OK THEN INSERT INTO THE DATABASE

					ELSE
						{
						$urgency = explode('|', $_POST['urgency']);
						$category = explode('|', $_POST['category']);
						$date = getdate();
						$query = "INSERT INTO ".$prefix."_hosting_tickets_tickets
								SET
								tickets_uid = '".$sess_uid."',
								tickets_subject = '".$_POST[ticketsubject]."',
								tickets_timestamp = '".$date[mon]."/".$date[mday]."/".$date[year]." - ".$date[hours].":".$date[minutes].":".$date[seconds]."',
								tickets_name = '".$_POST[PostName]."',
								tickets_email = '".$_POST[email]."',
								tickets_urgency = '".$urgency[0]."',
								tickets_category = '".$category[0]."',
								tickets_admin = '".$cookie[1]."',
								tickets_question = '".addslashes($_POST[message])."'";

						IF ($db->sql_query($query))
							{
								$result = $db->sql_query("SELECT tickets_id FROM ".$prefix."_hosting_tickets_tickets WHERE tickets_uid = '".$sess_uid."' ORDER BY tickets_id ASC");
								while($row=$db->sql_fetchrow($result))
									$lastinsertid = $row[tickets_id];

	// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS

							IF ($allowattachments == 'TRUE')
								FileUploadsVerification("$_FILES(userfile)", $lastinsertid);

	// EMAIL ADMINISTRATOR THE TICKET NOTIFICATION

							$message  = "Ticket ID:\t ".$lastinsertid."\n";
							$message .= "Name:\t\t ".$_POST['PostName']."\n";
							$message .= "Email:\t ".$_POST['email']."\n";
							$message .= "Subject:\t ".$_POST['ticketsubject']."\n";
							$message .= "Urgency:\t ".$urgency['1']."\n";
							$message .= "Department:\t ".$category['1']."\n";
							$message .= "Post Date:\t ".date($dformatemail)."\n";
							$message .= "----------------------------------------------------------------------\n";
							$message .= "Message:\n";
							$message .= stripslashes($_POST['message'])."\n";
							$message .= "----------------------------------------------------------------------\n";
							
							$mailheaders = "From: ".$_POST['email']."\n";
							$mailheaders .= "Reply-To: ".$_POST['email']."\n\n";
							$subject = "New Ticket Created: ".$lastinsertid."\n\n";
							mail($socketfrom, $subject, $message, $mailheaders);

							}
						else
						 echo "error";
						 ?><meta http-equiv="refresh" content="0;URL=<?php echo $_SERVER['PHP_SELF'] ?>?name=<?php echo $_GET[name]?>&amp;caseid=view&amp;ticketid=<?php echo $lastinsertid ?>" /><?php
						}
						
				}
				else
				{
	// PRODUCE THE FORM SO THE PERSON CAN WRITE THE NEW TICKET

				$query = "	SELECT user_email, username FROM ".$prefix."_users WHERE user_id = '".$sess_uid."' LIMIT 0,1";
				$result = $db->sql_query($query);
				$row = $db->sql_fetchrow($result);

?>
			<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?name=<?php echo $_GET[name]?>&amp;caseid=NewTicket" method="post">
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="4" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="boxborder" width="50%" valign="top"><p>

				<table width="100%" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="center">
				  <tr bgcolor="<?php echo $tr_color1;?>">
					<td class="boxborder" colspan="2"><p><b>New Support Ticket - All Fields Required</b></p></td>
				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Username:</b></p></td>
					<td class="boxborder"><p><input type="hidden" name="PostName" size="40" value="<?php echo $row[username] ?>" /><?php echo $row[username] ?></p></td>
				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Email:</b></p></td>
					<td class="boxborder"><p><input type="hidden" name="email" size="40" value="<?php echo $row[user_email] ?>" /><?php echo $row[user_email] ?></p></td>
				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Subject:</b></p></td>
					<td class="boxborder"><p><input name="ticketsubject" size="40"
<?php
			IF (isset($_POST['ticketsubject']) && $_POST['ticketsubject'] != '')
				echo ' value="'.$_POST['ticketsubject'].'"';
?>
					></p></td>
				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Department:</b></p></td>
					<td class="boxborder"><p>
					<select name="category">
<?php
			$query = "	SELECT tickets_categories_id, tickets_categories_name
					FROM ".$prefix."_hosting_tickets_categories
					ORDER BY tickets_categories_name ASC";

			$result = $db->sql_query($query);

			WHILE ($row = $db->sql_fetchrow($result))
				{
				echo '<option value="'.$row['tickets_categories_id'].'|'.$row['tickets_categories_name'].'">'.$row['tickets_categories_name'].'</option>';
				}
?>
					</select>
					</p></td>
				  </tr>
				  <tr>
					<td bgcolor="<?php echo $tr_color1;?>" class="boxborder"><p><b>Urgency:</b></p></td>
					<td class="boxborder"><p>
					<select name="urgency">
<?php
			$query = "	SELECT tickets_status_id, tickets_status_name, tickets_status_color
					FROM ".$prefix."_hosting_tickets_status
					ORDER BY tickets_status_order ASC";

			$result = $db->sql_query($query);

			WHILE ($row = $db->sql_fetchrow($result))
				{
				echo '<option style="background-color:#'.$row['tickets_status_color'].'" value="'.$row['tickets_status_id'].'|'.$row['tickets_status_name'].'">'.$row['tickets_status_name'].'</option>';
				}
?>
					</select></p></td>
				  </tr>
				</table><div style="padding-top:5px"></div>

				<table width="100%" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="center">
				  <tr bgcolor="<?php echo $tr_color1;?>">
					<td class="boxborder"><p><b>Question</b></p></td>
				  </tr>
				  <tr>
					<td align="right">
					<textarea name="message" cols="65" rows="10">
<?php
			IF (isset($_POST['message']) && $_POST['message'] != '')
				echo $_POST['message'].'</textarea>';
				ELSE
					echo '</textarea>';
?>
					<input type="submit" name="submit" value="Submit" />
					</td>
				  </tr>
				</table>
<?php
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

			IF ($allowattachments == 'TRUE')
				FileUploadForm();
?>
				</p></td>
				</p></td>
				<td class="boxborder" width="50%" valign="top">

				<table width="100%" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="center">
<?php
	// IF ATTACHMENTS ARE TRUE THEN SHOW ALLOWED FILETYPES

			IF ($allowattachments == 'TRUE')
				{
?>
				  <tr>
					<td><p><b>Allowed FILE TYPES for attachments:</b><br />
<?php
				FOR ($i = '0'; $i <= COUNT($allowedtypes) - 1; $i++)
					{
					echo $allowedtypes[$i].'<br />';
					}
?>
					</p></td>
				  </tr>
<?php
				}
?>
				</table>

				</td>
			  </tr>
			</table>
			</form>
<?php
			IF (isset($refresh) && $refresh == 'TRUE')
				{
?>
				<meta http-equiv="refresh" content="2;URL=<?php echo $_SERVER['PHP_SELF'] ?>?name=<?php echo $_GET[name]?>&amp;caseid=view&amp;ticketid=<?php echo $lastinsertid ?>" />
<?php
				}
			}
		}

	function DoFooter()
	{
		global $maintablewidth, $maintablealign;
?>		<br>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="0" align="<?php echo $maintablealign ?>">
			<tr>
				<td align="right">Powered by: <a href="http://www.phpsupporttickets.com" target="_blank">PHP Support Tickets</a>.</td>
			</tr>
			<tr>
				<td align="right">PHP-Nuke Integration by: <a href="http://www.moahosting.com" target="_blank">=|MoA|= Hosting</a>.</td>
			</tr>
		</table>
<?php
	}

if(!is_user($user))
{
	header("Location: modules.php?name=Your_Account");
}
else
{
	global $cookie;
	cookiedecode($user);
//	echo $cookie[1];
	$row = $db->sql_fetchrow($db->sql_query("SELECT user_id FROM ".$prefix."_users WHERE username='".$cookie[1]."'"));
	$sess_uid = $row[user_id];

	SWITCH ($_GET['caseid'])
	{
		case 'view':
			view();
		break;
		case 'UserInfo':
			UserInfo();
		break;
		case 'NewTicket':
			NewTicket();
		break;
		case 'home':
			home();
		break;
		default:
			home();
		break;
	}
	
	CloseTable();
	include("footer.php");
}

?>