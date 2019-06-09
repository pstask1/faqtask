
CREATE TABLE IF NOT EXISTS `PREFIX_faq` (
  `id_faq` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_faq_category` int(10) unsigned NOT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_faq`)
) ENGINE=_ENGINE_  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `PREFIX_faq_category` (
  `id_faq_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(10) unsigned NOT NULL,
  `level_depth` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `indexation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT '0',
  `cms_hook` varchar(255) DEFAULT NULL,
  `nleft` int(11) NOT NULL,
  `nright` int(11) NOT NULL,
  PRIMARY KEY (`id_faq_category`),
  KEY `category_parent` (`id_parent`)
) ENGINE=_ENGINE_  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `PREFIX_faq_category_lang` (
  `id_faq_category` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` text,
  `link_rewrite` varchar(128) NOT NULL,
  `meta_title` varchar(128) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_faq_category`,`id_lang`),
  KEY `category_name` (`name`)
) ENGINE=_ENGINE_ DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `PREFIX_faq_category_shop` (
  `id_faq_category` int(11) unsigned NOT NULL,
  `id_shop` int(11) unsigned NOT NULL,
  KEY (`id_faq_category`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=_ENGINE_ DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `PREFIX_faq_lang` (
  `id_faq` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `answer` longtext,
  PRIMARY KEY (`id_faq`,`id_lang`)
) ENGINE=_ENGINE_ DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `PREFIX_faq_shop` (
  `id_faq` int(11) unsigned NOT NULL,
  `id_shop` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id_faq`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=_ENGINE_ DEFAULT CHARSET=utf8;
