/***************************************************************************
File Name 	: ReadMe.txt
Domain		: http://www.triangle-solutions.com
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Describes any Fetures and Requirements.
Date Created	: Thursday 17 April 2003 14:51:42
****************************************************************************/

ReadMe
----------------------------------------------------------------------------

Triangle Solutions Ltd has developed an application which allows you to offer
your web site users a way to request Support Tickets.

Through the admin side of the application you can set up user accounts. These
Users may then log into the Client side and write any amount of requests to the
system. On submitting a request the request is emailed to the Administrator's
email specified within the Config file.

Admin can respond to all User tickets and the users will be emailed when the
Admin replies.

Attachments can be uploaded to an individual post. This may well be a screenshot
or document explaining things a little more.

Features
----------------------------------------------------------------------------

+	File attachments to each post.
+	User admin to add, suspend and re-activate users.
+	Users can post in as many requests as they like. Administrator will
	recieve an email automatically.
+	Administrators can action all user enquiries through the admin area.
+	Responding to a request will automatically email the person who created the
	request.
+	Users and Admins can close and reopen requests at any time.
+	Each email contains a thread of past posts and responses.
+	Refreshes stop the user postign the same response twice - I hope!

Requirements
----------------------------------------------------------------------------

+	Requires PHP v4.2+ - For the security measures incorporated in this release.
+	File Uploads Switched On - for attachments.
+	Will run on Linux or Windows.
+	MySQL database.
+	SMTP email server, that you can relay through.


