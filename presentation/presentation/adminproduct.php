<?php
header("Content-tupe: text/html; charset=utf-8");

class AdminProduct
{
  public $mProductId = 0;
  
  public $mErrorMessage = "";
  
  public $mProduct;
  
  // Class constructor
  public function __construct()
  {
  	if (!isset($_GET['product_id']))
  		echo "<h1>Ошибка - необходимо указать product_id</h1>";
    else 
    	$this->mProductId = $_GET['product_id'];

  	
  }

  public function init()
  {
  	// If updating product info ...
  	if (isset ($_POST['UpdateProductInfo']))
  	{
  		$product_name = $_POST['name'];
  		$product_description = $_POST['description'];
  		$product_price = $_POST['price'];
  	
  			
  		if ($product_name == null)
  			$this->mErrorMessage = 'Product name is empty';
  				
  		if ($product_description == null)
  			$this->mErrorMessage = 'Product description is empty';
  				
  		if ($product_price == null || !is_numeric($product_price))
  			$this->mErrorMessage = 'Product price must be a number!';
  					
  			
  											
  		if (empty($this->mErrorMessage))
  				Catalog::UpdateProduct($this->mProductId, 
  						$product_name,
  						$product_description, 
  						$product_price);
  	}
	
  }
  
  public function getInfo() {
  	
  	if (empty ($this->mProduct))
  		$this->mProduct = Catalog::GetProductForAdmin($this->mProductId);

  	return $this->mProduct;
  }
}
?>
