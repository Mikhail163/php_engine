<?php
header("Content-tupe: text/html; charset=utf-8");
// Business tier class for reading product catalog information

class Catalog
{
	/**
	 * Инициализируем наш проект и строим структуру бд
	 */
	public static function Init() {
		$sql = Sql::create_product_table();
		
		DatabaseHandler::Execute($sql);
	}
	
	// Retrieves the list of products on catalog page
	public static function GetProductsForAdmin()
	{
		
		// Retrieve the list of products
		$sql = Sql::catalog_get_products_for_admin();
		
		// Execute the query and return the results
		return DatabaseHandler::GetAll($sql);
	}
	
	public static function AddProduct($name, $description, $price) {
		// Build the SQL query
		$sql = Sql::catalog_add_product();
		
		$params = array (
				':name' => $name,
				':description' => $description,
				':price' => $price
		);
		
		
		// Execute the query
		return DatabaseHandler::GetLastInsertId($sql, $params);
	}
	
	public static function DeleteProduct($product_id) {
		// Build the SQL query
		$sql = Sql::catalog_delete_product();
		
		$params = array (
				':product_id' => $product_id
		);
		
		
		// Execute the query
		return DatabaseHandler::Execute($sql, $params);
	}

		 
}
?>
