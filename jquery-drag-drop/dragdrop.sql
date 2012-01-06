SET NAMES latin1;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `dragdrop` (
  `id` int(11) NOT NULL auto_increment,
  `text` varchar(255) default NULL,
  `listorder` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

insert into `dragdrop` values('1','Go Shopping','1'),
 ('2','Take the dog for a walk','2'),
 ('3','Go swimming','3'),
 ('4','Go to the Gym','4'),
 ('5','Pick up the wife from work','5'),
 ('6','Wash the car','6'),
 ('7','Take the kids to school','7');

SET FOREIGN_KEY_CHECKS = 1;
