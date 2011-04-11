<?php


$sql="
CREATE TABLE `red_assignchess` (
  `id` int(11) NOT NULL auto_increment,
  `round` int(11) NOT NULL,
  `queueid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `chessid` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `queueid` (`queueid`),
  KEY `uid` (`uid`),
  KEY `round` (`round`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

CREATE TABLE `red_chess` (
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `followers` int(11) NOT NULL,
  `followings` int(11) NOT NULL,
  `tweets` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `insertno` int(11) NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `name` (`name`),
  KEY `score` (`score`),
  KEY `insertno` (`insertno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `red_online` (
  `uid` int(9) NOT NULL,
  `flag` tinyint(4) NOT NULL,
  `score` int(9) NOT NULL,
  `lasttime` int(11) NOT NULL,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `uid` (`uid`),
  KEY `flag` (`flag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `red_queue` (
  `id` int(11) NOT NULL auto_increment,
  `round` int(9) NOT NULL,
  `fuid` int(11) NOT NULL,
  `tuid` int(11) NOT NULL,
  `starttime` int(11) NOT NULL,
  `fscore` int(9) NOT NULL,
  `tscore` int(9) NOT NULL,
  `fwins` int(9) NOT NULL,
  `twins` int(9) NOT NULL,
  `flosts` int(9) NOT NULL,
  `tlosts` int(9) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fuid` (`fuid`),
  KEY `tuid` (`tuid`),
  KEY `fscore` (`fscore`),
  KEY `tscore` (`tscore`),
  KEY `fwins` (`fwins`),
  KEY `twins` (`twins`),
  KEY `flosts` (`flosts`),
  KEY `tlosts` (`tlosts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `red_userchess` (
  `id` varchar(25) NOT NULL,
  `uid` int(11) NOT NULL,
  `chessid` int(11) NOT NULL,
  `lasttime` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `red_user` (
  `uid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `regtime` int(11) NOT NULL default '0',
  `lasttime` int(11) NOT NULL,
  `logintime` int(11) NOT NULL,
  `wins` int(11) NOT NULL,
  `losts` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `followings` int(11) NOT NULL,
  `tweets` int(11) NOT NULL,
  `logins` int(11) NOT NULL,
  PRIMARY KEY  (`uid`),
  KEY `ix_name` (`name`),
  KEY `ix_score` (`score`),
  KEY `ix_logintime` (`logintime`),
  KEY `wins` (`wins`),
  KEY `losts` (`losts`),
  KEY `score` (`score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


";










?>