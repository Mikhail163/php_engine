<?php
header('Content-type: text/html; charset=utf-8');

class Sql
{
	public static function catalog_get_products_for_admin()
	{
		return 'SELECT   product_id, name, description, price
		 FROM     product
		 ORDER BY product_id';
	}
	
	
	public static function create_product_table() {
		return 
		"
		CREATE TABLE IF NOT EXISTS `product` (
		  `product_id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(100) NOT NULL,
		  `description` longtext NOT NULL,
		  `price` decimal(10,2) NOT NULL,
		  `image` varchar(150) DEFAULT NULL,
		  PRIMARY KEY (`product_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
		ALTER TABLE `product` ADD FULLTEXT KEY `idx_ft_product_name_description` (`name`,`description`);
		";
	}
	
	public static function create_user_table() {
		return
		"
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `last_action` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY (`user_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
	}
	
	public static function create_role_table() {
		return
		"
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
	}
	
	public static function create_user_role_table() {
		return
		"		
CREATE TABLE IF NOT EXISTS `user_role` (
  `user_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
   PRIMARY KEY (`user_role_id`),
   UNIQUE KEY (`user_id`,`role_id`),
   FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`),
   FOREIGN KEY (`role_id`) REFERENCES `role`(`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
	}
	
	/* Create catalog_add_product_to_category stored procedure */
	public static function catalog_add_product()
	{
		return
		'INSERT INTO product (name, description, price)
			 VALUES (:name, :description, :price);';		
	}
	
	public static function catalog_delete_product()
	{	
		return 'DELETE FROM product WHERE product_id = :product_id LIMIT 1';
	}
	
	public static function catalog_get_product_for_admin() {
		return 'SELECT   product_id, name, description, price, image
		 FROM     product
		 WHERE product_id = :product_id
		 LIMIT 1';
	}
	
	public static function catalog_update_product()
	{
		return
		'UPDATE product
	     SET    name = :name, description = :description, price = :price
	     WHERE  product_id = :product_id';
	}
	
	public static function user_get_info_by_name() {
		return "SELECT user_id, name, password FROM user WHERE login = :user_name";
	}
	
	public static function user_get_id_by_name() {
		return "SELECT user_id FROM user WHERE login = :login LIMIT 1;";
	}
	
	
	public static function user_register() {
		return "INSERT INTO user (name, login, password)
		VALUES (:name, :login, :password)";
	}
}

?>