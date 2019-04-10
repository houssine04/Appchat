
CREATE DATABASE bd_chat;

USE bd_chat;

CREATE TABLE `user` (
  `id` int(11) NOT NULL  AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `message` (
  `id` int(11) NOT NULL  AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `msg` text NOT NULL,
  `created_at` bigint(20) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (sender) REFERENCES user(id),
  FOREIGN KEY (receiver) REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



ALTER TABLE `message` ADD FULLTEXT KEY `search_content` (`msg`);

