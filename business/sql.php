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
}

?>