CREATE TABLE IF NOT EXISTS `crops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `crop_type` varchar(255) NOT NULL,
  `field_size` decimal(10,2) NOT NULL,
  `planting_date` date NOT NULL,
  `harvest_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `notes` text,
  `status` varchar(50) DEFAULT 'planted',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 