<?php
/***************************************************************************
File Name 	: functions.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Holds the connection info and any functions.
Date Created	: Friday 12 March 2004 14:35:20
File Version	: 1.8
\\||************************************************************************/

	$version = 'v1.8';


#############################################################################################
#################################   SEND EMAIL FUNCTION   ###################################
#############################################################################################

	Function SendMail($email, $name, $subject, $message)
		{
		Global $sockethost, $smtpauth, $smtpauthuser, $smtpauthpass, $socketfrom, $socketfromname, $socketreply, $socketreplyname;

		include ('class.phpmailer.php');

		$mail = new phpmailer();
		$mail -> IsSMTP();
		$mail -> Host = $sockethost;

		IF ($smtpauth == 'TRUE')
			{
			$mail -> SMTPAuth = true;
			$mail -> Username = $smtpauthuser;
			$mail -> Password = $smtpauthpass;
			}

		IF (isset($_GET['caseid']) && ($_GET['caseid'] == 'NewTicket' || $_GET['caseid'] == 'view'))
			{
			$mail -> From = $email;
			$mail -> FromName = $name;
			$mail -> AddReplyTo($email, $name);
			}
			ELSE
				{
				$mail -> From = $socketfrom;
				$mail -> FromName = $socketfromname;
				$mail -> AddReplyTo($socketreply, $socketreplyname);
				}

		$mail -> IsHTML(False);
		$mail -> Body = $message;
		$mail -> Subject = $subject;

		IF (isset($_GET['caseid']) && ($_GET['caseid'] == 'NewTicket' || $_GET['caseid'] == 'view'))
			$mail -> AddAddress($socketfrom, $socketfromname);
			ELSE
				$mail -> AddAddress($email, $name);

		IF(!$mail -> Send())
			return ('Error: '.$mail -> ErrorInfo);
			ELSE
				return ('Email Sent.');

		$mail -> ClearAddresses();
		}


#############################################################################################
########################   CHECK USER IS LOGGED IN FOR CUSTOMERS   ##########################
#############################################################################################

	Function AuthUser($user, $pass)
	{	
		global $db;
		$query = "	SELECT tickets_users_password
				FROM tickets_users
				WHERE tickets_users_username = '$user'
				AND tickets_users_status = '1'";

		$result = $db->sql_query($query);

		IF (!$result)
			return 0;

		IF (($row = $db->sql_fetchrow($result)) && ($pass == $row['tickets_users_password'] && $pass != ''))
			return 1;
			ELSE
				return 0;
		}


#############################################################################################
###############################   FUNCTION PAGE TITLE BAR   #################################
#############################################################################################

	Function PageTitle($text)
		{
		Global $maintablewidth, $maintablealign, $background;
?>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr bgcolor="<?php echo $background ?>">
			<td><p><?php echo $text ?></p></td>
		  </tr>
		</table>
<?php
		}


#############################################################################################
#########################   USECOLOR FUNCTION FOR COLOURED ROWS   ###########################
#############################################################################################

	Function UseColor()
		{
		$trcolor1 = '#F4FAFF';
		$trcolor2 = '#FFFFFF';
		static $colorvalue;

		IF($colorvalue == $trcolor1)
			$colorvalue = $trcolor2;
			ELSE
				$colorvalue = $trcolor1;

		return($colorvalue);
		}


#############################################################################################
########   FILE UPLOAD FORM FUNCTION - USE FUNCTION AS THIS IS IN MULTIPLE PLACES   #########
#############################################################################################

		Function FileUploadForm()
			{
			GLOBAL $maxfilesize;
?>
			<table width="97%" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="center">
			  <tr bgcolor="#AABBDD">
				<td class="boxborder"><p><b>File Attachment</b></p></td>
			  </tr>
			  <tr>
				<td class="boxborder" align="center"><p>
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxfilesize ?>" />
				<input type="file" name="userfile" size="62" />
				</p></td>
			  </tr>
			</table>
<?php
			}


#############################################################################################
#####################   CHECK ERROR STATUSES ON THE UPLOADED FILES   ########################
#############################################################################################

		Function FileUploadsVerification($userfile, $newfilename)
			{
			GLOBAL $filetypes, $allowedtypes, $uploadpath, $relativepath, $maintablewidth, $maintablealign;

	// CHECK ERROR STATUSES ON THE UPLOADED FILES

			IF ($_FILES['userfile']['error'] == '4')
				$msg = 'No attachment uploaded';
			ELSEIF ($_FILES['userfile']['error'] == '2')
				$msg = 'This file exceeds the Maximum allowable size within this tool.';
			ELSEIF ($_FILES['userfile']['error'] == '1')
				$msg = 'This file exceeds the PHP upload size.';
			ELSEIF ($_FILES['userfile']['error'] == '3')
				$msg = 'Sorry we could only partially upload htis file please try again.';

	// CHECK TO MAKE SURE THE UPLOADED FILE IS OF A FILE WE ALLOW AND GET THE NEWFILE EXTENSION

			ELSEIF (!in_array($_FILES['userfile']['type'], $allowedtypes))
				{
				$msg = 'The file that you uploaded was of a type that is not allowed,
					you are only allowed to upload files of the type:';

				WHILE ($type = current($allowedtypes))
					{
					$msg .= '<br />'.$filetypes[$type].' ('.$type.')';
					next($allowedtypes);
					}
				}

	// IF FILE IS NOT OVER SIZE AND IS CORRECT TYPE THEN CONTINUE WITH PROCESS

			ELSEIF ($_FILES['userfile']['error'] == '0')
				{

	// GET THE EXTENSION FOR THE UPLOADED FILE

				$type1 = $_FILES['userfile']['type'];
				$extension = $filetypes["$type1"];
				$newfilename = $newfilename.$extension;

	// PRINT OUT THE RESULTS

				$msg = '<p><b>Attachment Uploaded</b> - You submitted: '.$_FILES['userfile']['name'].'
					SIZE: '.$_FILES['userfile']['size'].' bytes -
					TYPE: '.$_FILES['userfile']['type'];

				move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadpath.$newfilename);
				}
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" Cellpadding="1" border="0" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr bgcolor="#AACCEE">
				<td><p><?php echo $msg ?></p></td>
			  </tr>
			</table>
<?php
			}
