<?php
header("Content-tupe: text/html; charset=utf-8");
// Class that deals with products administration from a specific category
class AdminProducts
{
  // Public variables available in smarty template
  public $mProductsCount;
  public $mProducts;
  
  public $mProductsInCategory;
  
  public $mErrorMessage;
  public $mDepartmentId;
  public $mMetaId;
  public $mCategoryId;
  public $mCategoryName;
  public $mLinkToDepartmentCategoriesAdmin;
  public $mLinkToCategoryProductsAdmin;
  public $mBrendsList;
  
  public $mSubcategoryId;
  public $mSubcategoryName;
  
  public $mAssignProductTo;

  // Private attributes
  private $_mAction;
  private $_mActionedProductId;
  
  public $mProductTypes;

  // Class constructor
  public function __construct()
  {
  	Customer::ifNotAdminExit();
  
  	if (isset ($_GET['MetaId']))
      $this->mMetaId = (int)$_GET['MetaId'];
    else
      trigger_error('MetaId not set');
  
    if (isset ($_GET['DepartmentId']))
      $this->mDepartmentId = (int)$_GET['DepartmentId'];
    else
      trigger_error('DepartmentId not set');

    if (isset ($_GET['CategoryId']))
      $this->mCategoryId = (int)$_GET['CategoryId'];
    else
      trigger_error('CategoryId not set');
	 
	 
	// Это страница субкатегорий 
	if (isset ($_GET['SubcategoryId']))
	{
      $this->mSubcategoryId = (int)$_GET['SubcategoryId'];
	  $subcategory_details = Catalog::GetSubcategoryDetails($this->mSubcategoryId);
	  $this->mSubcategoryName = $subcategory_details['name'];
	  
	  $this->mLinkToCategoryProductsAdmin =
      		Link::ToCategoryProductsAdmin($this->mMetaId, $this->mDepartmentId, $this->mCategoryId, 
			$this->mSubcategoryId);
			
	  $this->mLinkToDepartmentCategoriesAdmin =
     	 Link::ToSubcategoriesAdmin($this->mMetaId, $this->mDepartmentId, $this->mCategoryId);
	}
	else
	{
		$this->mLinkToCategoryProductsAdmin =
      		Link::ToCategoryProductsAdmin($this->mMetaId, $this->mDepartmentId, $this->mCategoryId);
			
		$this->mLinkToDepartmentCategoriesAdmin =
      		Link::ToDepartmentCategoriesAdmin($this->mMetaId, $this->mDepartmentId);
	}

    $category_details = Catalog::GetCategoryDetails($this->mCategoryId);
    $this->mCategoryName = $category_details['name'];

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

    

    
	  $this->mProductTypes = Catalog::GetAllInTable('product_type');
	  
	  $this->mBrendsList = Catalog::GetBrends();
  }

  public function init()
  {
    // If adding a new product ...
    if ($this->_mAction == 'add_prod')
    {
      $product_name = $_POST['product_name'];
	  $product_brend = $_POST['brend'];
      $product_description = $_POST['product_description'];
      $product_price = $_POST['product_price'];
	  $product_weight = $_POST['product_weight'];
	  $product_article = $_POST['product_article'];
	  $product_type = $_POST['type'];
	  
	  $product_type = $_POST['type'];
	  
   if ($product_type == null || $product_type == 0 ||
          !is_numeric($product_type))
        $this->mErrorMessage = 'Выберите тип продукта!';

	  if ( $product_brend == 0 || !is_numeric($product_brend))
	  	$this->mErrorMessage = 'Укажите БРЕНД';

      if ($product_name == null)
        $this->mErrorMessage = 'Product name is empty';

      if ($product_description == null)
        $this->mErrorMessage = 'Product description is empty';

      if ($product_price == null || !is_numeric($product_price))
        $this->mErrorMessage = 'Product price must be a number!';
		
	  if ($product_weight == null || !is_numeric($product_weight))
        $this->mErrorMessage = 'Product weight must be a number!';

      if ($this->mErrorMessage == null)
      {
        $product_id = Catalog::AddProductToCategory($this->mMetaId, $this->mCategoryId, $product_name,
          $product_description, $product_price, $product_weight, $product_brend, $product_article, $product_type);


		if (isset ($_GET['SubcategoryId']))
		{
			Catalog::AddProductToSubcategory($this->mSubcategoryId, $product_id);
		}

        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToCategoryProductsAdmin));
      }
    }

    if ($this->_mAction == 'unvisible')
    {
	
        Catalog::SetElementVisible('product', $this->_mActionedProductId, '0');

        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToCategoryProductsAdmin));
    }

    if ($this->_mAction == 'visible')
    {
	
        Catalog::SetElementVisible('product', $this->_mActionedProductId, '1');

        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToCategoryProductsAdmin));
    }


    // If we want to see a product details
    if ($this->_mAction == 'edit_prod')
    {
      header('Location: ' .
             htmlspecialchars_decode(
               Link::ToProductAdmin($this->mMetaId,
			   						$this->mDepartmentId,
                                    $this->mCategoryId,
                                    $this->_mActionedProductId)));

      exit();
    }

	if ($this->_mAction == 'del_from_subcat')
    {
	
        Catalog::RemoveProductFromSubcategory($this->_mActionedProductId, $this->mSubcategoryId);
    }

	if (isset ($_POST['AssignProduct']))
    {
      $target_product_id = $_POST['TargetProductIdAssign'];
	  
      Catalog::AddProductToSubcategory($this->mSubcategoryId, $target_product_id);
    }

	if (isset ($_GET['SubcategoryId']))
	{
		$this->mProducts = Catalog::GetSubcategoryProducts($this->mSubcategoryId);	
		$this->mProductsInCategory = Catalog::GetCategoryProducts($this->mCategoryId);
		
		if (!empty($this->mProductsInCategory))
		{
		
				// Show the categories the product belongs to
				for ($i = 0; $i < count($this->mProducts); $i++)
				  $vtemp1[$this->mProducts[$i]['product_id']] =
					$this->mProducts[$i]['name'];  
				
				for ($i = 0; $i < count($this->mProductsInCategory); $i++)
					  $vtemp2[$this->mProductsInCategory[$i]['product_id']] =
						$this->mProductsInCategory[$i]['name'];
	
				if (!empty($vtemp1))
				{
					$this->mAssignProductTo = array_diff($vtemp2, $vtemp1);
					
					if (empty($this->mAssignProductTo))
						$this->mAssignProductTo = false;
				}
				else
					$this->mAssignProductTo = $vtemp2;
		}
	}
	else
    	$this->mProducts = Catalog::GetCategoryProducts($this->mCategoryId);
		
    $this->mProductsCount = count($this->mProducts);
  }
}
?>
