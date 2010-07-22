<?php
/***************************************************************************
File Name 	: index.html
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Admin page
Date Created	: Tuesday 13 April 2004 20:37:291
File Version	: 1.8c
\\||************************************************************************/

#############################################################################################
############################ INCLUDE THE CONFIG AND HEADER FILE #############################
#############################################################################################

	// STARTS THE SESSION FOR THE USERS SO LOGIN IS TRACKED THROUGH THE PAGES
if (!eregi("admin.php", $_SERVER[PHP_SELF])) { die ("Access Denied"); }
$aid = trim($aid);
$row = $db->sql_fetchrow($db->sql_query("SELECT radminsuper FROM " . $prefix . "_authors WHERE aid='$aid'"));
if ($row[radminsuper]==1)
{

	//session_start();

	include_once ('modules/Support/config.php');
	include_once ('modules/Support/class/functions.php');
	include("header.php");
	GraphicAdmin();
	OpenTable();
?>		<center><b><strong>&middot; <a href="admin.php?op=Support">Support Tickets Administration</a> &middot;</strong></b></center><?php
	CloseTable();
	OpenTable();
	
#############################################################################################
####################     DISPLAY THE PAGE TITLE AND NAVIGATION    ###########################
#############################################################################################
?>

	<form action="<?php echo $_SERVER['PHP_SELF']?>?op=<?php echo $_GET[op]?>" method="post">
	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" align="<?php echo $maintablealign ?>">
	  <tr>
		<td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support"><img src="modules/Support/images/support_tickets_logo.gif" width="83" height="61" title="Triangle Solutions PHP Support Tickets" alt="Triangle Solutions PHP Support Tickets" vspace="1" border="0" /></td>
		<td valign="bottom" align="right"><p>
		<input name="keywords" size="24" onfocus="javascript:this.value=''" value="Search Ticket Subject" />
		<input type="submit" value="GO" />
		</p></td>
	  </tr>
	</table>
	</form>

	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1" align="<?php echo $maintablealign ?>">
	  <tr bgcolor="<?php echo $backout ?>">
		<td  onmouseover="window.status='Support Services Manager Admin';return true;" onmouseout="window.status=' ';return true;" bgcolor="<?php echo $background ?>">
			<p><a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=home">Support Services Manager Admin</a></td>

		<td  width="10%" onmouseover="this.style.background='<?php echo $backover ?>';window.status='Open Requests';return true;" onmouseout="this.style.background='<?php echo $backout ?>';window.status=' ';return true;" align="center">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=home&order=Open" style="WIDTH:94%;COLOR:#000000">Open</td>

		<td  width="10%" onmouseover="this.style.background='<?php echo $backover ?>';window.status='Closed Requests';return true;" onmouseout="this.style.background='<?php echo $backout ?>';window.status=' ';return true;" align="center">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=home&order=Closed" style="WIDTH:94%;COLOR:#000000">Closed</td>

		<td  width="10%" onmouseover="this.style.background='<?php echo $backover ?>';window.status='Department Admin';return true;" onmouseout="this.style.background='<?php echo $backout ?>';window.status=' ';return true;" align="center">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=cats" style="WIDTH:94%;COLOR:#000000">Departments</td>

		<td  width="10%" onmouseover="this.style.background='<?php echo $backover ?>';window.status='Status Admin';return true;" onmouseout="this.style.background='<?php echo $backout ?>';window.status=' ';return true;" align="center">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=status" style="WIDTH:94%;COLOR:#000000">Status</td>
	  </tr>
	</table>

	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1" align="<?php echo $maintablealign ?>">
	  <tr>
		<td  width="15%"><p><a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=document&docid=Install">Install</a></p></td>
		<td  width="15%"><p><a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=document&docid=ReadMe">ReadMe</a></p></td>
		<td  width="15%"><p><a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=document&docid=ChangeLog">ChangeLog</a></p></td>
		<td  width="15%"><p><a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=document&docid=Todo">Todo</a></p></td>
		<td  width="15%"><p><a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=document&docid=Licence">Licence</a></p></td>
		<td  width="15%"><p><a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=document&docid=Version">Version</a></p></td>
	  </tr>
	</table>
	<script language="javascript" type="text/javascript">
	<!-- Hide script from old browsers

	function deletemember()
		{
		if (confirm('Before we continue are you sure you want to action this.'))
			{
			return true;
			}
			else
				{
				return false;
				}
		}
	</script>
<?php
#############################################################################################
#######     START OF THE SWITCH FOR THE CASEID IE WHAT PAGE THIS IS HOME - VIEW ETC    ######
#############################################################################################
function home()
{
	global $db, $maintablewidth, $maintablealign;
			IF (!isset($_GET['order']) && !isset($_POST['keywords']))
				$_GET['order'] = 'Open';

	// PROCESS THE FUNCTIONS WHEN THE CHECKBOXES ARE CHECKED - IE OPEN CLOSE TICKET

			IF (isset($_POST['status']))
				{
				IF (isset($_POST['ticket']))
					{
					FOREACH ($_POST['ticket'] AS $ticketid)
						{
						if($_POST[status] == 'Delete')
						{
							$query = "DELETE FROM nuke_hosting_tickets_tickets WHERE tickets_id = '".$ticketid."'";
							if(!$db->sql_query($query))
								$iserror = 1;
							$query = "DELETE FROM nuke_hosting_tickets_tickets WHERE tickets_child = '".$ticketid."'";
							if(!$db->sql_query($query))
								$iserror = 1;
							if($iserror == 1)
								$msg = 'Ticket Deleted';
							else
								$msg = 'This could not be done at this time';
						}
						else
						{
							$query = "UPDATE nuke_hosting_tickets_tickets
									SET tickets_status = '".$_POST['status']."'
									WHERE tickets_id = '".$ticketid."'";
	
							IF ($db->sql_query($query))
								$msg = 'Ticket '.$_POST['status'];
								ELSE
									$msg = 'This could not be done at this time';
						}
						}
					}
					ELSE
						$msg = 'Please select a Ticket.';
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AACCEE">
					<td><p><?php echo $msg ?></p></td>
				  </tr>
				</table>
<?php
				}

	// QUERY TO GET THE LATEST OPEN - CLOSED OR SEARCH ON SUBJECT

			$query = "	SELECT tickets_id, tickets_uid, tickets_subject, tickets_timestamp, tickets_status, tickets_status_name, tickets_status_color, tickets_categories_name
					FROM nuke_hosting_tickets_tickets a, nuke_hosting_tickets_status b, nuke_hosting_tickets_categories c
					WHERE a.tickets_child = '0'
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

			$query .= "	ORDER BY a.tickets_id DESC, a.tickets_timestamp DESC";

			$result = $db->sql_query($query);
			$totaltickets = $db->sql_numrows($result);

	// ADD THE HOME VIEW TITLE TABLE
?>
			<div style="padding-top:5px"></div>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1"  align="<?php echo $maintablealign ?>">
			  <tr bgcolor="<?php echo $background ?>">
				<td><p>Recent Tickets: <?php echo $totaltickets ?> -
					Click on the Ticket ID to read the ticket.</p></td>
			  </tr>
			</table>

<?php			IF ($totaltickets > '0')
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

				<form name="myform" action="admin.php?op=Support&amp;caseid=home<?php echo $addon ?>" method="post">
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
				  <tr align="center" bgcolor="<?php echo $background ?>">
					<td onClick="check_all();" style="cursor:hand"><p><b><u>All</u></b></p></td>
					<td ><p><b>Ticket ID</b></p></td>
					<td ><p><b>Replies</b></p></td>
					<td ><p><b>Username</b></p></td>
					<td ><p><b>Subject</b></p></td>
					<td ><p><b>Date / Time</b></p></td>
					<td ><p><b>Urgency</b></p></td>
					<td ><p><b>Department</b></p></td>
					<td ><p><b>Status</b></p></td>
				  </tr>
<?php
				WHILE ($row = $db->sql_fetchrow($result))
					{

	// QUERY TO GET THE AMOUNT OF REPLIES TO A CERTAIN TICKET AND DATE OF LAST ENTRY

					$queryA = "	SELECT COUNT(*) FROM nuke_hosting_tickets_tickets WHERE tickets_child = '".$row[tickets_id]."'";

					$resultA = $db->sql_query($queryA);
					$rowA = $db->sql_fetchrow($resultA);

					IF ($rowA['0'] <= '0')
						$rowA['0'] = '0';
?>
					<tr align="center" bgcolor="<?php echo UseColor() ?>">
						<td ><input type="checkbox" name="ticket[]" value="<?php echo $row[tickets_id] ?>" /></td>
						<td  bgcolor="<?php echo $backout ?>" onmouseover="this.style.background='<?php echo $backover ?>';window.status='Read Ticket';return true;" onmouseout="this.style.background='<?php echo $backout ?>';window.status=' ';return true;"><p>
							<a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=AdminView&amp;ticketid=<?php echo $row[tickets_id] ?>" style="width:100%"><?php echo $row[tickets_id] ?></a>
						</p></td>
						<td ><p>[<?php echo $rowA['0'] ?>]</p></td>
						<td ><p><?php echo $row[tickets_uid] ?></p></td>
						<td ><p><?php echo $row[tickets_subject] ?></p></td>
						<td ><p><?php echo $row[tickets_timestamp] ?></p></td>
						<td bgcolor="#<?php echo $row[tickets_status_color] ?>"><p><?php echo $row[tickets_status_name] ?></p></td>
						<td ><p><?php echo $row[tickets_categories_name] ?></p></td>
						<td ><p>
<?php
					IF ($tickets_status == 'Closed')
						echo '<span style="color:#FF0000">';
						ELSE
							echo '<span>';

					echo 		$row[tickets_status].'</span></p></td>
						  </tr>';
					}
?>
					</td>
				  </tr>
				  <tr>
					<td colspan="8">
					<select name="status">
					<option value="Open">Open</option>
					<option value="Closed">Closed</option>
					<option value="Delete">Delete</option>
					</select>
					<input type="submit" name="sub" value="Go">
					</td>
				  </tr>
				</table>
				</form>
<?php
				}
				ELSE
					{
					IF (isset($_POST['keywords']))
						$msg = 'Sorry but the search returned Zero results please try again.';
						ELSE
							$msg = 'You have no recent tickets for your account.';
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
					  <tr>
						<td><p><?php echo $msg ?></p></td>
					  </tr>
					</table>
<?php
					}
}

function AdminView()
{
	global $db, $uploadpath, $aid, $prefix, $relativepath, $socketfrom, $socketfromname;
			IF (isset($_GET['closesub']))
				{
				$query = "UPDATE nuke_hosting_tickets_tickets
						SET tickets_status = '".$_GET['closesub']."'
						WHERE tickets_id = '".$_GET['ticketid']."'";

				IF ($db->sql_query($query))
					$msg = 'Ticket '.$_GET['closesub'];
					ELSE
						$msg = 'This could not be done at this time';
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AACCEE">
					<td><p><?php echo $msg ?></p></td>
				  </tr>
				</table>
<?php
				}

	// AND A NEW RESPONSE AND ATTACHMENT TO THE SYSTEM

			IF (isset($_POST[submit]))
				{
				IF ($_POST['message'] == '')
					$msg = 'Please complete all the fields';
					ELSE
						{
						$urgency = explode('|', $_POST['posturgency']);
						$category = explode('|', $_POST['postdept']);
   						$date = getdate();
						$query = "INSERT INTO nuke_hosting_tickets_tickets
								SET
								tickets_uid = '".$_POST[uid]."',
								tickets_subject = '".$_POST['postsubject']."',
								tickets_timestamp = '".$date[mon]."/".$date[mday]."/".$date[year]." - ".$date[hours].":".$date[minutes].":".$date[seconds]."',
								tickets_urgency = '".$urgency['0']."',
								tickets_category = '".$category['0']."',
								tickets_admin = '".$aid."',
								tickets_child = '".$_GET['ticketid']."',
								tickets_question = '".$_POST['message']."'";

						IF ($result = $db->sql_query($query))
							{

	// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS

							IF ($allowattachments == 'TRUE')
								FileUploadsVerification("$_FILES(userfile)", mysql_insert_id());

	// MAIL THE PERSON WHO STARTED THE TICKET

							$message  = "Ticket ID:\t ".$_GET['ticketid']." - This has been responded too.\n";
							$message .= "Name:\t\t ".$_POST['name']."\n";
							$message .= "Email:\t ".$_POST['email']."\n";
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
							$mailheaders = "From: ".$socketfrom."\n";
							$mailheaders .= "Reply-To: ".$socketfrom."\n\n";
							$subject = "Response to your ticket: ".$_GET[ticketid]."\n\n";
							mail($_POST[email], $subject, $message, $mailheaders);
							}
						}
				}

	// QUERY TO GET THE TICKET INFORMATION

			$query = "	SELECT tickets_id, tickets_subject, tickets_uid, tickets_timestamp, tickets_status, tickets_name, tickets_email, tickets_admin, tickets_child, tickets_question, tickets_status_id, tickets_status_name, tickets_status_color, tickets_categories_id, tickets_categories_name
					FROM nuke_hosting_tickets_tickets a, nuke_hosting_tickets_status b, nuke_hosting_tickets_categories c
					WHERE (a.tickets_id = '".$_GET[ticketid]."'
					OR tickets_child = '".$_GET[ticketid]."')
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id
					ORDER BY tickets_id ASC";

			$result = $db->sql_query($query);
			$totaltickets = $db->sql_numrows($result);
			$row = $db->sql_fetchrow($result);
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
			  <tr>
				<td width="50%" valign="top">

				<table width="97%" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AABBDD">
					<td  colspan="2"><p><b>Ticket #<?php echo $_GET['ticketid'] ?> Information</b></p></td>
					<td  width="50%" onmouseover="this.style.background='';" onmouseout="this.style.background='#FFF000';" bgcolor="#FFF000" align="center">
<?php
				IF ($row[tickets_status] == 'Open')
					{
?>
					<a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=AdminView&amp;ticketid=<?php echo $row[tickets_id] ?>&amp;closesub=Closed" style="width:94%;color:#000000">Close Ticket
<?php
					}
					ELSE
						{
?>
						<a href="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=AdminView&amp;ticketid=<?php echo $row[tickets_id] ?>&amp;closesub=Open" style="width:94%;color:#000000">Reopen Ticket
<?php
						}
?>
					</td>
				  </tr>
				  <tr>
					<td bgcolor="#CCCCCC" ><p><b>Username:</b></p></td>
					<td  colspan="2"><p><?php echo $row[tickets_admin]; ?></p></td>
				  </tr>
				  <tr>
					<td bgcolor="#CCCCCC" ><p><b>Email:</b></p></td>
					<td  colspan="2"><p><?php echo $row[tickets_email] ?></p></td>
				  </tr>
				  <tr>
					<td bgcolor="#CCCCCC" ><p><b>Subject:</b></p></td>
					<td  colspan="2"><p><?php echo $row[tickets_subject] ?></p></td>
				  </tr>
				  <tr>
					<td bgcolor="#CCCCCC" ><p><b>Department:</b></p></td>
					<td  colspan="2"><p><?php echo $row[tickets_categories_name] ?></p></td>
				  </tr>
				  <tr>
					<td bgcolor="#CCCCCC" ><p><b>Urgency:</b></p></td>
					<td  colspan="2" bgcolor="#<?php echo $row[tickets_status_color] ?>">
						<p><b><?php echo $row[tickets_status_name] ?></b></p></td>
				  </tr>
				  <tr>
					<td bgcolor="#CCCCCC" ><p><b>Status:</b></p></td>
					<td  colspan="2"><p>
<?php
			IF ($tickets_status == 'Closed')
				echo '<span style="color:#FF0000">';
				ELSE
					echo '<span>';

			echo 		$row[tickets_status].'</span></p></td>
				  </tr>';
?>
				</table><div style="padding-top:5px"></div>

				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=AdminView&amp;ticketid=<?php echo $_GET['ticketid'] ?>" method="post">
				<table width="97%" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AABBDD">
					<td ><p><b>Respond</b></p></td>
				  </tr>
				  <tr>
					<td align="right"><p><textarea name="message" cols="45" rows="10"></textarea>
					<input type="hidden" name="name" value="<?php echo $row[tickets_name] ?>" />
					<input type="hidden" name="uid" value="<?php echo $row[tickets_uid] ?>" />
					<input type="hidden" name="email" value="<?php echo $row[tickets_email] ?>" />
					<input type="hidden" name="postsubject" value="<?php echo $row[tickets_subject] ?>" />
					<input type="hidden" name="posturgency" value="<?php echo $row[tickets_status_id] ?>|<?php echo $row[tickets_status_name] ?>" />
					<input type="hidden" name="postdept" value="<?php echo $row[tickets_categories_id] ?>|<?php echo $row[tickets_categories_name] ?>" />
					<input type="submit" name="submit" value="Submit" /></p></td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?php
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

			IF ($allowattachments == 'TRUE')
				FileUploadForm();
?>
				<br /></td>
				<td valign="top">
<?php
	// LIST THE ASSOCIATED RESPONSES TO THIS TICKET

			$j = '0';
			$result = $db->sql_query($query);

			WHILE ($row = $db->sql_fetchrow($result))
				{
?>
				<table width="97%" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AABBDD">
					<td ><p><b>
<?php
				IF ($j == '0')
					echo '	Dialog Question';
					ELSE
						echo '	Response '.$j;
?>
					</b></p></td>
					<td  bgcolor="#AACCDD" width="50%" align="right"><p><?php echo $row[tickets_timestamp] ?></p></td>
				  </tr>
<?php
				IF ($row[tickets_admin] == 'Admin')
					$bgcolor = '#FFF000';
					ELSE
						$bgcolor = '#AACCEE';
?>
				  <tr>
					<td  colspan="2"><p><?php echo nl2br($row[tickets_question]) ?></p></td>
				  </tr>
				  <tr bgcolor="<?php echo $bgcolor ?>">
					<td ><p>Posted By: <?php echo $row[tickets_admin] ?></p></td>
					<td ><p align="right">
<?php
	// SCAN THE UPLOAD DIRECTORY FOR ATTACHMENTS TO THIS POST

					$d = dir($uploadpath);

					WHILE (false != ($files = $d -> read()))
						{
						$files = explode('.', $files);

						IF ($files['0'] == $row[tickets_id])
							{
?>
						  	<b>Attachment:</b> <?php echo $files['0'] ?>.<?php echo $files['1'] ?>
							<a href="<?php echo "http://".$relativepath.$files['0'] ?>.<?php echo $files['1'] ?>" target="_blank">
							<img src="modules/Support/images/download.gif" width="13" height="13" align="absmiddle" border="0" /></a>
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

?>
					</td>
				  </tr>
				</table><div style="padding-top:5px"></div>

				<input type="hidden" name="ticketquestion[]" value="<?php echo $row[ tickets_question] ?>" />
				<input type="hidden" name="postedby[]" value="<?php echo $row[tickets_admin] ?>" />
				<input type="hidden" name="postdate[]" value="<?php echo $row[tickets_timestamp] ?>" />
<?php

				$j ++;
				}
?>
				</td>
			  </tr>
			</table>
			</form>
<?php
}

function Document()
{
	global $maintablewidth, $maintablealign;
?>
			<table width="<?php echo $maintablewidth ?>" align="<?php echo $maintablealign ?>" cellspacing="1" cellpadding="1" border="1" >
			  <tr>
				<td><iframe src="modules/Support/documents/<?php echo $_GET['docid'] ?>.txt" frameborder="0" width="100%" height="450" name="inframe"></iframe></td>
			  </tr>
			</table>
<?php
}

function Cats()
{
	global $db, $maintablealign, $maintablewidth;
	// EDIT OR DELETE USER SETTINGS

			IF (isset($_POST['sub']) && isset($_POST['depid']))
				{
				IF ($_POST['sub'] == 'Delete')
					{
					$query = "	DELETE FROM nuke_hosting_tickets_categories
							WHERE tickets_categories_id = '".$_POST['depid']."'";

					$actiontaken = 'Department Deleted';
					}

				ELSEIF ($_POST['sub'] == 'Edit')
					{
					$query = "	UPDATE nuke_hosting_tickets_categories
							SET
							tickets_categories_name = '".$_POST['department']."'
							WHERE tickets_categories_id = '".$_POST['depid']."'";

					$actiontaken = 'Edited';
					}

				$result = $db->sql_query($query);

				PageTitle('Department '.$_POST['depid'].' '.$actiontaken);
				}
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
			  <tr bgcolor="#AACCEE">
				<td><p>Please add in all the details below for the each department.</p></td>
			  </tr>
			</table>

			<form action="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=cats" method="post">
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
			  <tr>
				<td><br /><p>Be careful about deleting departments. Deleting them will cause
				errors with all tickets assigned to that particular department. Therefore be careful
				when you add them, make sure they are concise and what you want.<br /><br />

				<table width="300" cellpadding="0" cellspacing="0" align="center">
				  <tr>
					<td><p>Department Name:</p></td>
					<td><input name="firstname" size="30"
<?php
			IF (isset($_POST['userform']) && isset($_POST['firstname']) && $_POST['firstname'] != '')
				echo ' value="'.$_POST['name'].'"';
				ELSE
					{
					echo ' style="background-color:#FDD3D4"';
					$error = 'T';
					}
?>
					/> <input type="submit" value="Submit" name="userform" /></td>
				  </tr>
<?php
			IF (!isset($error))
				{
				$query = "	INSERT INTO nuke_hosting_tickets_categories
						SET
						tickets_categories_name = '".$_POST['name']."'";

				$result = $db->sql_query($query);
?>
				<tr>
					<td colspan="2"><p><b>Everythings OK Department added.</b></p></td>
				</tr>
<?php
				}
?>
				</table><br />

				</p></td>
			  </tr>
			</table>
			</form>

			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
			  <tr>
				<td  colspan="3" bgcolor="#AACCEE"><p>Departments Already In The System.</p></td>
			  </tr>
			  <tr>
				<td ><p><b>ID.</b></p></td>
				<td ><p><b>Department</b></p></td>
				<td ><p><b>Action</b></p></td>
			  </tr>
<?php
	// LOOP THROUGH ALL EXISTING USERS IN THE DATABASE AND GIVE OPTIONS TO SUSPEND - DELETE ETC

			$sql = "SELECT tickets_categories_id, tickets_categories_name
					FROM nuke_hosting_tickets_categories
					ORDER BY tickets_categories_id ASC";

			$result = $db->sql_query($sql);

			while($row = $db->sql_fetchrow($result))
				{
?>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=cats" Method="post">
				<tr bgcolor="<?php echo UseColor() ?>">
					<td ><p><?php echo $row[tickets_categories_id] ?></p></td>
					<td ><input name="department" value="<?php echo $row[tickets_categories_name] ?>" size="40" /></td>
					<td ><p>
					<input type="submit" name="sub" value="Delete" onclick="return deletemember()" />
					<input type="hidden" name="depid" value="<?php echo $row[tickets_categories_id] ?>">
					<input type="submit" name="sub" value="Edit" />
					</p></td>
				  </tr>
				</form>
<?php
				}
?>
			</table>
<?php
}

function Status()
{
	global $db, $maintablealign, $maintablewidth;
	
	// EDIT OR DELETE USER SETTINGS
	if(isset($_POST['sub']) && isset($_POST['depid']))
	{
		if($_POST['sub'] == 'Delete')
		{
			$query = "DELETE FROM nuke_hosting_tickets_status WHERE tickets_status_id = '".$_POST['depid']."'";
			$actiontaken = 'Status Deleted';
		}
	
		elseif($_POST['sub'] == 'Edit')
		{
			$query = "	UPDATE nuke_hosting_tickets_status SET tickets_status_name = '".$_POST['status']."', tickets_status_order = '".$_POST['order']."',
				tickets_status_color = '".$_POST['color']."' WHERE tickets_status_id = '".$_POST['depid']."'";
			$actiontaken = 'Edited';
		}
		$result = $db->sql_query($query);
		PageTitle('Status '.$_POST['depid'].' '.$actiontaken);
	}
?>
	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
		<tr bgcolor="#AACCEE">
			<td><p>Please add in all the details below for the each status.</p></td>
		</tr>
	</table>

	<form action="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=status" method="post">
	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
		<tr><td><br /><p>Be careful about deleting status's. Deleting them will cause
			errors with all tickets assigned to that particular status. Therefore be careful
			when you add them, make sure they are concise and what you want. Order refers
			to where in the list it will appear, 1 being first.<br /><br />
				<table width="300" cellpadding="0" cellspacing="0" align="center">
					<tr>
						<td><p>Status Name:</p></td>
						<td><input name="firstname" size="30"
<?php
			if(isset($_POST['userform']) && isset($_POST['firstname']) && $_POST['firstname'] != '')
				echo ' value="'.$_POST['name'].'"';
			else
			{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
			}
?>
						> <input type="submit" value="Submit" name="userform" /></td>
				  	</tr>
<?php
			if(!isset($error))
			{
				$query = "	INSERT INTO nuke_hosting_tickets_status SET tickets_status_name = '".$_POST['name']."'";
				$result = $db->sql_query($query);
?>
					<tr>
						<td colspan="2"><p><b>Everythings OK Status added.</b></p></td>
					</tr>
<?php
			}
?>
				</table><br>
			</p></td>
		</tr>
	</table>
	</form>

	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1"  align="<?php echo $maintablealign ?>">
		<tr>
			<td colspan="5" bgcolor="#AACCEE"><p>Status's Already In The System.</p></td>
		</tr><tr>
			<td><p><b>ID.</b></p></td>
			<td><p><b>Status</b></p></td>
			<td><p><b>Order</b></p></td>
			<td><p><b>Color</b></p></td>
			<td><p><b>Action</b></p></td>
		</tr>
<?php
	// LOOP THROUGH ALL EXISTING USERS IN THE DATABASE AND GIVE OPTIONS TO SUSPEND - DELETE ETC
		$query = "SELECT tickets_status_id, tickets_status_name, tickets_status_order, tickets_status_color FROM nuke_hosting_tickets_status ORDER BY tickets_status_id ASC";
		$result = $db->sql_query($query);
		while($row = $db->sql_fetchrow($result))
		{
?>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?op=Support&amp;caseid=status" Method="post">
				<tr bgcolor="<?php echo UseColor() ?>">
					<td><p><?php echo $row[tickets_status_id] ?></p></td>
					<td><input name="status" value="<?php echo $row[tickets_status_name] ?>" size="40" /></td>
					<td><input name="order" value="<?php echo $row[tickets_status_order] ?>" size="20" /></td>
					<td bgcolor="#<?php echo $row[tickets_status_color] ?>"><input name="color" value="<?php echo $row[tickets_status_color] ?>" size="20" /></td>
					<td><p>
					<input type="submit" name="sub" value="Delete" onclick="return deletemember()" />
					<input type="hidden" name="depid" value="<?php echo $row[tickets_status_id] ?>">
					<input type="submit" name="sub" value="Edit" />
					</p></td>
				  </tr>
				</form>
<?php
		}
?>
	</table>
<?php
}

SWITCH ($_GET['caseid'])
{
	CASE 'home':
		home();
	BREAK;
	CASE 'AdminView':
		AdminView();
	BREAK;
	CASE 'document':
		Document();
	BREAK;
	CASE 'cats':
		Cats();
	BREAK;
	CASE 'status':
		Status();
	BREAK;
	default:
		home();
	break;
}
?><br>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="0" align="<?php echo $maintablealign ?>">
	<tr>
		<td align="right">Powered by <a href="http://www.phpsupporttickets.com" target="_blank">PHP Support Tickets</a>.</td>
	</tr>
	<tr>
		<td align="right">PHP-Nuke Module Integration by <a href="http://www.moahosting.com" target="_blank">=|MoA|= Hosting</a>.</td>
	</tr>
</table>
<?php
	CloseTable();
	include("footer.php");
}
else 
    echo "Access Denied";
?>