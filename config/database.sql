-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_dataexchange_config`
-- 

CREATE TABLE `tl_dataexchange_config` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `addExportInDCA` char(1) NOT NULL default '',
  `tableName` varchar(128) NOT NULL default '',
  `exportType` varchar(128) NOT NULL default '',
  `exportCSVSeparator` varchar(255) NOT NULL default ',',
  `exportCSVExcel` char(1) NOT NULL default '',
  `includeHeader` char(1) NOT NULL default '',
  `sqlWhere` varchar(255) NOT NULL default '',
  `sqlOrderBy` varchar(255) NOT NULL default '',
  `prependString` varchar(255) NOT NULL default '',
  `exportToFile` char(1) NOT NULL default '',
  `storeDir` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_dataexchange_fields`
-- 

CREATE TABLE `tl_dataexchange_fields` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `dcaField` varchar(255) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `fieldQuery` varchar(255) NOT NULL default '',
  `useFilter` char(1) NOT NULL default '',
  `enabled` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

