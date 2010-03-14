CREATE TABLE IF NOT EXISTS `#__mediasyndicator_feeds` (
  `id` int(11) NOT NULL auto_increment,
  `published` tinyint(1) NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `description` varchar(1024) default '',
  `copyright` varchar(255) default '',
  `number_items_syndicate` int(11) NOT NULL default 20,
  `it_block` tinyint(1) NOT NULL default '0',
  `it_explicit` tinyint(1) NOT NULL default '0',
  `it_category1_id` int(11),
  `it_category2_id` int(11),
  `it_category3_id` int(11),
  `it_keywords` varchar(255) default '',
  `it_owner_email` varchar(255) default '',
  `it_owner_name` varchar(255) default '',
  `it_subtitle` varchar(255) default '',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `#__mediasyndicator_files` (
  `id` int(11) NOT NULL auto_increment,
  `published` tinyint(1) NOT NULL default '0',
  `filename_large` varchar(255) NOT NULL,
  `filename_small` varchar(255) NOT NULL,
  `media_type_id` int(11) NOT NULL default 1,
  `title` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL default '',
  `album` varchar(255) default '',
  `track` varchar(255) default '',
  `genre` varchar(255) default '',
  `comment` varchar(1024) default '',
  `performed_date` datetime NOT NULL,
  `venue` varchar(255) default '',
  `duration` varchar(10) default '',
  `it_block` tinyint(1) NOT NULL default '0',
  `it_explicit` tinyint(1) NOT NULL default '0',
  `it_category_id` int(11) NOT NULL default 37,
  `it_keywords` varchar(255) default '',
  `it_subtitle` varchar(255) default '',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`media_type_id`) REFERENCES `#__mediasyndicator_media_types`(`id`)
);

CREATE TABLE IF NOT EXISTS `#__mediasyndicator_media_types` (
  `id` int(11) NOT NULL auto_increment,
  `media_type_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `#__mediasyndicator_files_to_feeds` (
  `file_id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL,
  PRIMARY KEY  (`file_id`,`feed_id`),
  FOREIGN KEY (`file_id`) REFERENCES `#__mediasyndicator_files`(`file_id`),
  FOREIGN KEY (`feed_id`) REFERENCES `#__mediasyndicator_feeds`(`feed_id`)
);

CREATE TABLE IF NOT EXISTS `#__mediasyndicator_itunes_categories` (
  `id` int(11) NOT NULL,
  `parent_category_id` int(11),
  `published` tinyint(1) NOT NULL default '1',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`parent_category_id`) REFERENCES `#__mediasyndicator_itunes_categories`(`id`)
);

-- INSERT INTO `#__mediasyndicator_media_types` VALUES (1,'Audio');
-- INSERT INTO `#__mediasyndicator_media_types` VALUES (2,'Video');

-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (0, NULL, 1, 'Arts');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (1, 0, 1, 'Design');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (2, 0, 1, 'Fashion &amp; Beauty');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (3, 0, 1, 'Food');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (4, 0, 1, 'Literature');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (5, 0, 1, 'Performing Arts');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (6, 0, 1, 'Visual Arts');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (7, NULL, 1, 'Business');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (8, 7, 1, 'Business News');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (9, 7, 1, 'Careers');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (10, 7, 1, 'Investing');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (11, 7, 1, 'Management &amp; Marketing');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (12, 7, 1, 'Shopping');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (13, NULL, 1, 'Comedy');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (14, NULL, 1, 'Education');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (15, 14, 1, 'Education Technology');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (16, 14, 1, 'Higher Education');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (17, 14, 1, 'K-12');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (18, 14, 1, 'Language Courses');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (19, 14, 1, 'Training');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (20, NULL, 1, 'Games &amp; Hobbies');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (21, 20, 1, 'Automotive');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (22, 20, 1, 'Aviation');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (23, 20, 1, 'Hobbies');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (24, 20, 1, 'Other Games');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (25, 20, 1, 'Video Games');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (26, NULL, 1, 'Government &amp; Organizations');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (27, 26, 1, 'Local');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (28, 26, 1, 'National');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (29, 26, 1, 'Non-Profit');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (30, 26, 1, 'Regional');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (31, NULL, 1, 'Health');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (32, 31, 1, 'Alternative Health');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (33, 31, 1, 'Fitness &amp; Nutrition');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (34, 31, 1, 'Self-Help');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (35, 31, 1, 'Sexuality');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (36, NULL, 1, 'Kids &amp; Family');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (37, NULL, 1, 'Music');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (38, NULL, 1, 'News &amp; Politics');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (39, NULL, 1, 'Religion &amp; Spirituality');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (40, 39, 1, 'Buddhism');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (41, 39, 1, 'Christianity');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (42, 39, 1, 'Hinduism');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (43, 39, 1, 'Islam');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (44, 39, 1, 'Judaism');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (45, 39, 1, 'Other');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (46, 39, 1, 'Spirituality');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (47, NULL, 1, 'Science &amp; Medicine');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (48, 47, 1, 'Medicine');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (49, 47, 1, 'Natural Sciences');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (50, 47, 1, 'Social Sciences');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (51, NULL, 1, 'Society &amp; Culture');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (52, 51, 1, 'History');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (53, 51, 1, 'Personal Journals');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (54, 51, 1, 'Philosophy');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (55, 51, 1, 'Places &amp; Travel');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (56, NULL, 1, 'Sports &amp; Recreation');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (57, 56, 1, 'Amateur');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (58, 56, 1, 'College &amp; High School');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (59, 56, 1, 'Outdoor');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (60, 56, 1, 'Professional');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (61, NULL, 1, 'Technology');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (62, 61, 1, 'Gadgets');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (63, 61, 1, 'Tech News');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (64, 61, 1, 'Podcasting');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (65, 61, 1, 'Software How-To');
-- INSERT INTO `#__mediasyndicator_itunes_categories` VALUES (66, NULL, 1, 'TV &amp; Film');

-- INSERT INTO `#__mediasyndicator_files` VALUES (1, 1, '/images/sermons/my-sermon.mp3', '/images/sermons/my-sermon.mp3', 1, 'My Sermon Title', 'Joel Rowley', 'Genesis', '1', 'Sermons', 'This is my comment', '2010-01-18 19:15:00', 'Cross Impact', 0, 0, 37, '34:20', 'genesis,god,creation', 'iTunes Subtitle', 0, '0000-00-00 00:00:00');
-- INSERT INTO `#__mediasyndicator_files` VALUES (2, 1, '/images/sermons/my-other-sermon.mp3', '/images/sermons/my-other-sermon.mp3', 1, 'Why We Do Not Do Right', 'Matt Collier', '', '', '', '', '2010-01-18 19:16:00', 'Sunday AM', 0, 0, 37, '', '', '', 0, '0000-00-00 00:00:00');

-- INSERT INTO `#__mediasyndicator_feeds` VALUES (1, 1, 'Bethany Baptist Church Sermons', 'Listen to all the sermons published from Bethany Baptist Church', '(c) 2010 Bethany Baptist Church. All Rights Reserved.', 20, 0, 0, NULL, NULL, NULL, '', '', '', '', 0, '0000-00-00 00:00:00');

-- INSERT INTO `#__mediasyndicator_files_to_feeds` VALUES (1, 1);
-- INSERT INTO `#__mediasyndicator_files_to_feeds` VALUES (2, 1);