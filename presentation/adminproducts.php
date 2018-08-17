<?php
header("Content-tupe: text/html; charset=utf-8");

class AdminProducts
{
  
  public $mProductsCount;
  public $mProducts;
  
  // Private attributes
  private $_mAction;
  private $_mActionedProductId;
  
  public $mErrorMessage = "";
  
  // Class constructor
  public function __construct()
  {
  	
    foreach ($_POST as $key => $value)
      // If a submit button was clicked ...
      if (substr($key, 0, 6) == 'submit')
      {
        /* Get the position of the last '_' underscore from submit button name
           e.g strtpos('submit_edit_prod_1', '_') is 17 */
        $last_underscore = strrpos($key, '_');

        /* Get the scope of submit button
           (e.g  'edit_dep' from 'submit_edit_prod_1') */
        $this->_mAction = substr($key, strlen('submit_'),
                                 $last_underscore - strlen('submit_'));

        /* Get the product id targeted by submit button
           (the number at the end of submit button name)
           e.g '1' from 'submit_edit_prod_1' */
        $this->_mActionedProductId = (int)substr($key, $last_underscore + 1);

        break;
      }
  }

  public function init()
  {
    // If adding a new product ...
    if ($this->_mAction == 'add_prod')
    {
      $product_name = $_POST['product_name'];
      $product_description = $_POST['product_description'];
      $product_price = $_POST['product_price'];
	  

      if ($product_name == null)
        $this->mErrorMessage = 'Product name is empty';

      if ($product_description == null)
        $this->mErrorMessage = 'Product description is empty';

      if ($product_price == null || !(is_numeric($product_price))) {
        $this->mErrorMessage = 'Product price must be a number!';
      }
		

      if (empty($this->mErrorMessage))
      {

        $product_id = Catalog::AddProduct(
        		$product_name,
                $product_description, 
        		$product_price);


        header('Location: ' . Link::ToAdminProducts());
      }
    }

    // If we want to see a product details
    if ($this->_mAction == 'edit_prod')
    {

    	header('Location: ' . Link::ToAdminProduct($this->_mActionedProductId));

        exit();
    }
    
    // If we want to see a product details
    if ($this->_mAction == 'del_prod')
    {
    	Catalog::DeleteProduct($this->_mActionedProductId);
    	header('Location: ' . Link::ToAdminProducts());
    	
    	exit();
    }
    
    return $this->mErrorMessage;

	
  }
}
?>
