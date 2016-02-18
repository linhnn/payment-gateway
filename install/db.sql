CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` varchar(20) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `card_name` varchar(255) DEFAULT NULL,
  `card_number` varchar(20) DEFAULT NULL,
  `card_expiration` char(7) DEFAULT NULL,
  `card_ccv` char(5) DEFAULT NULL,  
  `description` text DEFAULT NULL,
  `response` text DEFAULT  NULL,
  `created_time` datetime DEFAULT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;