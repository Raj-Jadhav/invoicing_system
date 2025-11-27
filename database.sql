CREATE DATABASE IF NOT EXISTS `phpinvoice_tutorial` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci; 
USE `phpinvoice_tutorial`; 
 
CREATE TABLE IF NOT EXISTS `invoices` ( 
  `id` int(11) NOT NULL AUTO_INCREMENT, 
  `client_name` varchar(255) NOT NULL, 
  `client_address` text NOT NULL, 
  `payment_status` varchar(50) NOT NULL, 
  `notes` text NOT NULL, 
  `created` datetime NOT NULL, 
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
 
CREATE TABLE IF NOT EXISTS `invoice_items` ( 
  `id` int(11) NOT NULL AUTO_INCREMENT, 
  `item_name` varchar(255) NOT NULL, 
  `item_quantity` int(11) NOT NULL, 
  `item_price` decimal(7,2) NOT NULL, 
  `invoice_id` int(11) NOT NULL, 
  PRIMARY KEY (`id`) 
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;