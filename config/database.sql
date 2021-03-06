-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************


-- 
-- Table `tl_fakesubsites`
-- 

CREATE TABLE `tl_fakesubsites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0', 
  `name` varchar(255) NOT NULL default '',
  `page` smallint(5) unsigned NOT NULL default '0',
  `tag` varchar(255) NOT NULL default '',
  `replacement` text NULL,
  `active` char(1) NOT NULL default '',  
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_fss_items`
-- 

CREATE TABLE `tl_fss_items` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0', 
  `name` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',  
  `active` char(1) NOT NULL default '',  
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_module`
--
CREATE TABLE `tl_module` (
   `fss_name` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;