
CREATE TABLE IF NOT EXISTS `stored_queries` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `code` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `superlists` (
  `id` int(11) NOT NULL auto_increment,
  `key` varchar(200) NOT NULL,
  `class` varchar(200) NOT NULL,
  `name_function_body` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `superlist_activerecord_params` (
  `id` int(11) NOT NULL auto_increment,
  `superlist_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `value` longtext NOT NULL,
  `comment` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `superlist_buttons` (
  `id` int(11) NOT NULL auto_increment,
  `superlist_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `url_function` varchar(200) default NULL,
  `url_code` longtext,
  `weight` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `superlist_list_columns` (
  `id` int(11) NOT NULL auto_increment,
  `superlist_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `weight` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `superlist_sections` (
  `id` int(11) NOT NULL auto_increment,
  `superlist_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `weight` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `superlist_section_fields` (
  `id` int(11) NOT NULL auto_increment,
  `superlist_section_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `weight` int(11) default NULL,
  `query_id` int(11) default NULL COMMENT 'stored_queries',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
