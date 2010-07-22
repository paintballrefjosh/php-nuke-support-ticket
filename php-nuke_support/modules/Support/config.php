<?php
/***************************************************************************
File Name 	: config.php
Domain		: http://www.triangle-solutions.com
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Holds the connection info and any user editable items.
Date Created	: Tuesday 13 April 2004 18:26:48
File Version	: 1.8
\\||************************************************************************/


	$encryptkey = "SdFkfa28367dm56w693a2fDS9";					// Security Key.
	$encryptintensity = 111;									// 1-254 : The higher the number, the greater the encryption
	$encryptcalcnum = 1234567890;								// Number to do encryption calculations with

	$tr_color1 = "#E6E6E6";

############ SMTP SOCKET SETUP - EMAIL SETTINGS ############

	$socketfrom = 'Support@domain.com';		// EMAIL ADDRESS TO APPEAR IN FROM / REPLY FIELD
	$socketfromname = '=|MoA|= Support System';	// NAME TO APPEAR IN FROM FIELD / REPLY FIELD
	$sockethost = 'localhost';				// SMTP HOST TO SEND THE EMAILS VIA THE SMTP SOCKET

	// USE SMTP AUTHENTICATION

	$smtpauth = 'TRUE';					// SET THIS TO TRUE IF YOUR SMTP SERVER REQUIRES AUTHENTICATION
	$smtpauthuser = 'Support@domain.com';		// SMTP USERNAME - USUALLY THE SAME AS YOUR MAILBOX
	$smtpauthpass = 'sewport';				// SMTP PASSWORD - USUALLY THE SAME AS YOUR MAILBOX


############ EMAIL FLAGS ############

	$emailclose = 'TRUE';					// EMAIL THE USER IF THE ADMIN CLOSES THE TICKET


############ DETERMINE SOME SITE DESIGN ELEMENTS ############

	$maintablewidth = '100%';				// DO IN '%' OR BY PIXELS IE '40%' OR '400'
	$maintablealign = 'center';				// OPTIONS - Center, Right etc.

	// MAIN TITLE BAR

	$background = '';				// THIS WILL CHANGE THE COLOUT OF THE MAIN BAR 0080C1
	$backover = '#84D7FF';					// THIS WILL CHANGE THE COLOUT OF THE MAIN BAR
	$backout = '';					// THIS WILL CHANGE THE COLOUT OF THE MAIN BAR


############ DYNAMIC LANGUAGE SELECTION ############

	$langdefault = 'lang-english';					// THIS WILL BE THE DEFAULT IF NO LANG VARIABLE IS FOUND

#	eng = English						// THESE ARE THE LANGAUGE SETTINGS THEY RESPOND TO THE
#	dut = Dutch						// FILES IN THE LANGUAGE FOLDER


############ FILE ATTACHMENT ON TICKETS ############

	$allowattachments = 'FALSE';				// SET TO TRUE OR FALSE DEPENDING ON WHETHER YOU WANT TO ALLOW ATTACHMENTS
	$maxfilesize = '10240000';				// IN BYTES

	$uploadpath = 'C:\servers\pub_http\domain\php\php-nuke development\modules\Support\upload';			// ABSOLUTE PATH TO THE UPLOAD FOLDER
								// IF LINUX IT MAY BE usr/home/web etc...
	$relativepath = 'http://php.domain.com/PHP-Nuke%20Development/modules/Support/upload/';	// URL TO THE UPLOAD FOLDER

	$filetypes = 	array (
				'image/pjpeg'		=> '.jpg',
				'image/jpeg'		=> '.jpg',
				'image/gif'		=> '.gif',
			#	'application/msword'	=> '.doc',
			#	'application/pdf'	=> '.pdf'
				);


	$allowedtypes = array(
				'image/pjpeg',			// ALLOWED TYPES COPY THE RELEVANT ITEM TO THIS STRING
				'image/jpeg',
				'image/gif',
			#	'application/msword',
			#	'application/pdf'
				);


############ DEMO MODE ############

	$footeron = 'TRUE';					// Display our footer message? Credit is always appreciated!
?>