-- 
-- Table structure for table `nuke_hosting_tickets_categories`
-- 

CREATE TABLE `nuke_hosting_tickets_categories` (
  `tickets_categories_id` tinyint(3) unsigned NOT NULL auto_increment,
  `tickets_categories_name` varchar(20) NOT NULL default '',
  `tickets_categories_order` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`tickets_categories_id`),
  UNIQUE KEY `tickets_categories_name` (`tickets_categories_name`)
) TYPE=MyISAM;

INSERT INTO `nuke_hosting_tickets_categories` VALUES (1, 'Department 1', 1);
INSERT INTO `nuke_hosting_tickets_categories` VALUES (2, 'Department 2', 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `nuke_hosting_tickets_status`
-- 

CREATE TABLE `nuke_hosting_tickets_status` (
  `tickets_status_id` tinyint(3) unsigned NOT NULL auto_increment,
  `tickets_status_name` varchar(20) NOT NULL default '',
  `tickets_status_order` tinyint(3) unsigned NOT NULL default '1',
  `tickets_status_color` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`tickets_status_id`),
  KEY `tickets_status_name` (`tickets_status_name`)
) TYPE=MyISAM;

INSERT INTO `nuke_hosting_tickets_status` VALUES (1, 'Low', 1, 'FFCC99');
INSERT INTO `nuke_hosting_tickets_status` VALUES (2, 'Medium', 2, 'FF9966');
INSERT INTO `nuke_hosting_tickets_status` VALUES (3, 'High', 3, 'FF6633');
INSERT INTO `nuke_hosting_tickets_status` VALUES (4, 'Urgent', 4, 'FF3300');

-- --------------------------------------------------------

-- 
-- Table structure for table `nuke_hosting_tickets_tickets`
-- 

CREATE TABLE `nuke_hosting_tickets_tickets` (
  `tickets_id` smallint(5) unsigned NOT NULL auto_increment,
  `tickets_uid` varchar(16) NOT NULL default '',
  `tickets_subject` varchar(50) NOT NULL default '',
  `tickets_timestamp` varchar(20) NOT NULL default '',
  `tickets_status` varchar(10) NOT NULL default 'Open',
  `tickets_name` varchar(50) NOT NULL default '',
  `tickets_email` varchar(50) NOT NULL default '',
  `tickets_urgency` tinyint(3) unsigned NOT NULL default '1',
  `tickets_category` tinyint(3) unsigned NOT NULL default '1',
  `tickets_admin` varchar(20) NOT NULL default 'Client',
  `tickets_child` smallint(5) unsigned NOT NULL default '0',
  `tickets_question` text NOT NULL,
  PRIMARY KEY  (`tickets_id`),
  KEY `tickets_username` (`tickets_uid`),
  KEY `tickets_urgency` (`tickets_urgency`),
  KEY `tickets_category` (`tickets_category`),
  KEY `tickets_child` (`tickets_child`),
  KEY `tickets_status` (`tickets_status`)
) TYPE=MyISAM;