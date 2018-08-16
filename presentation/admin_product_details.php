<?php
header("Content-tupe: text/html; charset=utf-8");
require_once BUSINESS_DIR . 'image.php';
require_once BUSINESS_DIR . 'articles.php';
// Class that deals with product administration
class AdminProductDetails
{
  // Public attributes
  public $mProduct;
  public $mErrorMessage;
  public $mProductCategoriesString;
  public $mProductDisplayOptions;
  public $mProductAttributes;
  public $mCatalogAttributes;
  
  
  public $mVideoInProduct = true;
  public $mFreedomVideo = true;
  public $mAssignVideoButton;
  public $mRemoveFromVideos;
  public $mAssignOrMoveVideoTo;
  public $mProductVideosString;
  
  public $mAssignOrMoveTo;
  public $mRemoveFromCategories;
  public $mRemoveFromCategoryButtonDisabled = false;
  
  
  public $mLinkToCategoryProductsAdmin;
  public $mLinkToProductDetailsAdmin;
  public $mMetaId;
  
  public $mFilter;
  public $mFilterAttributes;
  public $mFilterAttributesCount = 0;
  
  public $mProductUnique = '';

  // Private attributes
  private $_mProductId;
  private $_mCategoryId;
  private $_mDepartmentId;
  
  public $mBrendsList;
  public $mMetas;
  
  public $mArticleInProduct = true;
  public $mFreedomArticle = true;
  public $mAssignArticleButton;
  public $mRemoveFromArticles;
  public $mAssignOrMoveArticleTo;
  public $mProductArticlesString;
  
  public $mAccessories;
  public $mAccessoriesError;
  
  public $mWithWatermark = false;
  
  public $mAvailability;
 
  
  public $mProductTypes;
  
  private $_mAction;
  private $_mActionedId;
  
  public $mProviders;
  public $mProductProviders;
  public $mProvidersCount = 0;
  public $mProvidersProductCount = 0;
  

  
  public $mImagesError = array(false, false, false, false, false, false, false, false, false, false);

  // Class constructor
  public function __construct()
  {
  	Customer::ifNotAdminExit();
   	if (isset ($_GET['MetaId']))
      $this->mMetaId = (int)$_GET['MetaId'];
    else
      trigger_error('MetaId not set');
  
    // Need to have DepartmentId in the query string
    if (!isset ($_GET['DepartmentId']))
      trigger_error('DepartmentId not set');
    else
      $this->_mDepartmentId = (int)$_GET['DepartmentId'];

    // Need to have CategoryId in the query string
    if (!isset ($_GET['CategoryId']))
      trigger_error('CategoryId not set');
    else
      $this->_mCategoryId = (int)$_GET['CategoryId'];

    // Need to have ProductId in the query string
    if (!isset ($_GET['ProductId']))
      trigger_error('ProductId not set');
    else
      $this->_mProductId = (int)$_GET['ProductId'];

    $this->mProductDisplayOptions = Catalog::$mProductDisplayOptions;

    $this->mLinkToCategoryProductsAdmin =
      Link::ToCategoryProductsAdmin($this->mMetaId, $this->_mDepartmentId, $this->_mCategoryId);

    $this->mLinkToProductDetailsAdmin =
      Link::ToProductAdmin($this->mMetaId,
	  					   $this->_mDepartmentId,
                           $this->_mCategoryId,
                           $this->_mProductId);
						   
	$this->mMetas = Catalog::GetMetas();
	$this->mAccessories = Catalog::GetAccessoriesForItem($this->_mProductId, false);
	$this->mProductTypes = Catalog::GetAllInTable('product_type', array('name'=>''));
	
	foreach($this->mProductTypes as $type)
		if ($type['product_type_id'] == $this->mProduct['type_id'])
		{
	       $this->mProduct['yamt_category'] = $type['ya_cat_id'];
		   break;
		}
	
	//error_reporting(0);
	
	foreach ($_POST as $key => $value)
		// If a submit button was clicked ...
		if (substr($key, 0, 6) == 'submit')
		{
			/* Get the position of the last '_' underscore from submit
			 button name e.g strtpos('submit_edit_attr_1', '_') is 17 */
			$last_underscore = strrpos($key, '_');
	
			/* Get the scope of submit button
			 (e.g  'edit_dep' from 'submit_edit_attr_1') */
			$this->_mAction = substr($key, strlen('submit_'),
					$last_underscore - strlen('submit_'));
	
			/* Get the attribute id targeted by submit button
			 (the number at the end of submit button name)
			e.g '1' from 'submit_edit_attr_1' */
			$this->_mActionedId = substr($key, $last_underscore + 1);
	
			break;
		}
  }

  public function init()
  {
	  
    
		
  	$this->mProductUnique = $this->mMetaId .'_'.$this->_mProductId . '_';
  

  	self::EditAccessories();
	  
  
  if (isset ($_POST['unvisible']))
    {
	
        Catalog::SetElementVisible('product', $this->_mProductId, '0');

    }

    if (isset ($_POST['visible']))
    {
	
        Catalog::SetElementVisible('product', $this->_mProductId, '1');

    }
	
	if (isset ($_POST['unsale']))
    {
	
        Catalog::SetProductSale($this->_mProductId, '0');

    }

    if (isset ($_POST['forsale']))
    {
	
        Catalog::SetProductSale($this->_mProductId, '1');

    }
	
	if (isset ($_POST['pricenegotiable']))
    {
	
        Catalog::SetProductSale($this->_mProductId, '2');

    }

    if (isset ($_POST['pricenormal']))
    {
	
        Catalog::SetProductSale($this->_mProductId, '1');

    }
  
  	if (isset ($_POST['replaceProduct']))
    {
      $replace_product_id = $_POST['replace_product_id'];
	  
	  if (!is_numeric($replace_product_id))
	  	$this->mErrorMessage = 'Заменяемый product_id должен быть числом';
	  elseif ($replace_product_id < 0)
	  	$this->mErrorMessage = 'Заменяемый product_id не может быть отрицательным числом';
		
	  if ($this->mErrorMessage == null)
      	Catalog::SetTableRowElement('product', $this->_mProductId, 'replace_product_id', $replace_product_id);
    }
  
    

	if (isset ($_POST['ChangeMeta']))
	{
		$meta_id = $_POST['meta'];
		Catalog::SetMetaInProduct($this->_mProductId, $meta_id);
	}

    // If updating product info ...
    if (isset ($_POST['UpdateProductInfo']))
    {
      $product_name = $_POST['name'];
	  $product_brend = $_POST['brend'];
	  $product_type = $_POST['type'];
      $product_description = $_POST['description'];
      $product_price = $_POST['price'];
      $product_discounted_price = $_POST['discounted_price'];
	  $offer = $_POST['offer'];
	  
	  
	  $product_weight = $_POST['weight'];
	  $product_lenght = $_POST['lenght'];
	  $product_width = $_POST['width'];
	  $product_height = $_POST['height'];
	  
	  $product_articul = $_POST['articul'];

	  if ( $product_brend == 0 || !is_numeric($product_brend))
	  	$this->mErrorMessage = 'Укажите БРЕНД';

      if ($product_name == null)
        $this->mErrorMessage = 'Product name is empty';

      /*if ($product_description == null)
        $this->mErrorMessage = 'Product description is empty';*/

      if ($product_price == null || !is_numeric($product_price))
        $this->mErrorMessage = 'Product price must be a number!';

      if ($product_discounted_price == null ||
          !is_numeric($product_discounted_price))
        $this->mErrorMessage = 'Product discounted price must be a number!';

	if ($product_weight == null ||
          !is_numeric($product_weight))
        $this->mErrorMessage = 'Product weight must be a number!';
		
	if ($product_lenght == null ||
          !is_numeric($product_lenght))
        $this->product_lenght = 'Product lenght must be a number!';
		
	if ($product_width == null ||
          !is_numeric($product_width))
        $this->product_width = 'Product width must be a number!';

	if ($product_height == null ||
          !is_numeric($product_height))
        $this->product_height = 'Product height must be a number!';

$product_type = $_POST['type'];
   if ($product_type == null ||
          !is_numeric($product_type))
        $this->mErrorMessage = 'Выберите тип продукта!';
		
      if ($this->mErrorMessage == null)
        Catalog::UpdateProduct($this->_mProductId, $product_name,
          $product_description, $product_price, $product_discounted_price,
		  $product_weight, $product_lenght, $product_width, $product_height, $product_brend, $offer, $product_articul, $product_type);
    }
	
	// If updating product info ...
    if (isset ($_POST['UpdateSeoInfo']))
    {
      $keywords = $_POST['keywords'];
	  $seo_description = $_POST['seo_description'];

	  $product_h1 = $_POST['h1'];
      $product_title = $_POST['title'];
  
      $show_description = Yml::checkbox_verify('show_description');


        Catalog::UpdateSeoInfo3('product', $this->_mProductId, $keywords, $seo_description, 
		  $product_h1, $product_title, $show_description);
    }

    // If removing the product from a category ...
    if (isset ($_POST['RemoveFromCategory']))
    {
      $target_category_id = $_POST['TargetCategoryIdRemove'];
      $still_exists = Catalog::RemoveProductFromCategory(
                        $this->_mProductId, $target_category_id);

      if ($still_exists == 0)
      {
        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToCategoryProductsAdmin));

        exit();
      }
    }

    // If setting product display option ...
    if (isset ($_POST['SetProductDisplayOption']))
    {
      $product_display = $_POST['ProductDisplay'];
      Catalog::SetProductDisplayOption($this->_mProductId, $product_display);
    }
    
    if (isset ($_POST['SetWaterMark']))
    {
    	// устанавливать защитную WaterMark на изображение
    	Catalog::UpdateElementParametrInTable(
    			'product', 'product_id', $this->_mProductId,
    			'watermark', 1);
    	
    	header('Location: ' .
    			htmlspecialchars_decode(
    					Link::ToProductAdmin($this->mMetaId, $this->_mDepartmentId,
    							$this->_mCategoryId,
    							$this->_mProductId)));
    	
    	
    }
    if (isset ($_POST['UnsetWaterMark']))
    {
    	// убирать защитную WaterMark с изображения при добавлении
    	Catalog::UpdateElementParametrInTable(
    			'product', 'product_id', $this->_mProductId,
    			'watermark', 0);
    	
    	header('Location: ' .
    			htmlspecialchars_decode(
    					Link::ToProductAdmin($this->mMetaId, $this->_mDepartmentId,
    							$this->_mCategoryId,
    							$this->_mProductId)));
    }

		

    // If removing the product from catalog ...
    if (isset ($_POST['RemoveFromCatalog']))
    {
	
      Catalog::DeleteProduct($this->_mProductId);


      header('Location: ' .
             htmlspecialchars_decode(
               $this->mLinkToCategoryProductsAdmin));

      exit();
    }

    // If assigning the product to another category ...
    if (isset ($_POST['Assign']))
    {
      $target_category_id = $_POST['TargetCategoryIdAssign'];
      Catalog::AssignProductToCategory($this->_mProductId,
                                       $target_category_id);
    }
	
	// If assigning the product to another category ...
    if (isset ($_POST['AssignVideo']))
    {
      $target_video_id = $_POST['TargetVideoIdAssign'];
      Catalog::AssignProductToVideo($this->_mProductId,
                                       $target_video_id);
    }
	
	// If assigning the product to another category ...
    if (isset ($_POST['RemoveVideo']))
    {
     $target_video_id = $_POST['TargetVideoIdRemove'];
      Catalog::RemoveVideoFromProduct($this->_mProductId,
                                       $target_video_id);
    }

    // If moving the product to another category ...
    if (isset ($_POST['Move']))
    {
      $target_category_id = $_POST['TargetCategoryIdMove'];
      Catalog::MoveProductToCategory($this->_mProductId,
        $this->_mCategoryId, $target_category_id);

      header('Location: ' .
             htmlspecialchars_decode(
               Link::ToProductAdmin($this->mMetaId, $this->_mDepartmentId,
                                    $target_category_id,
                                    $this->_mProductId)));

      exit();
    }

    if ($this->_mAction == 'RemoveAttributeValue')
    {
    	$target_attribute_value_id = $this->_mActionedId;
    	Catalog::RemoveProductAttributeValue($this->_mProductId,
    			$target_attribute_value_id);
    }
    
    if ($this->_mAction == 'AssignAttributeValue')
    {
    	$target_attribute_value_id = $this->_mActionedId;
    	Catalog::AssignAttributeValueToProduct($this->_mProductId,
    			$target_attribute_value_id);
    }
    // If assigning an attribute value to the product ...
    if (isset ($_POST['AssignAttributeValue']))
    {
      $target_attribute_value_id = $_POST['TargetAttributeValueIdAssign'];
      Catalog::AssignAttributeValueToProduct($this->_mProductId,
                                             $target_attribute_value_id);
    }

    // If removing an attribute value from the product ...
    if (isset ($_POST['RemoveAttributeValue']))
    {
      $target_attribute_value_id = $_POST['TargetAttributeValueIdRemove'];
      Catalog::RemoveProductAttributeValue($this->_mProductId,
                                           $target_attribute_value_id);
    }
	
	if (isset ($_GET['AssignAttributeValue']))
    {
      $target_attribute_value_id = $_GET['AssignAttributeValue'];
      Catalog::AssignAttributeValueToProduct($this->_mProductId,
                                             $target_attribute_value_id);
    }

    // If removing an attribute value from the product ...
    if (isset ($_GET['RemoveAttributeValue']))
    {
      $target_attribute_value_id = $_GET['RemoveAttributeValue'];
      Catalog::RemoveProductAttributeValue($this->_mProductId,
                                           $target_attribute_value_id);
    }
    
    
    if (isset ($_POST['addNewProviderToProduct']))
    {
    	
    	$provider_id = $_POST['add_provider'];
    	$quantity = $_POST['pr_quantity'];
    	$purchase_price = $_POST['pr_purchase_price'];
  	    $retail_price = $_POST['pr_retail_price'];
  	    
  	    Catalog::AddProviderToProduct(
  	    		$this->_mProductId, 
  	    		$provider_id, 
  	    		$quantity, 
  	    		$purchase_price, $retail_price);
    }
    
    if (isset ($_POST['delNewProviderToProduct']))
    {
    	 
    	$provider_id = $_POST['del_provider'];
    	
    	 
    	Catalog::DelProviderFromProduct(
    			$this->_mProductId,
    			$provider_id);
    }

    // If moving the product to another category ...
    if (isset ($_POST['Move']))
    {
      $target_category_id = $_POST['TargetCategoryIdMove'];
      Catalog::MoveProductToCategory($this->_mProductId,
        $this->_mCategoryId, $target_category_id);

      header('Location: ' .
             htmlspecialchars_decode(
               Link::ToProductAdmin($this->_mDepartmentId,
                                    $target_category_id,
                                    $this->_mProductId)));

      exit();
    }

    $this->mProduct = Catalog::GetProductInfo($this->_mProductId);
	
    $this->mProduct['name'] = htmlspecialchars($this->mProduct['name']);
	
	
	$this->mProduct['retail_cost'] = 
	  ($this->mProduct['price']>$this->mProduct['discounted_price'] && 
	   $this->mProduct['discounted_price'] != 0)?
	   $this->mProduct['discounted_price']:
	   $this->mProduct['price'];
    
	for ($i = 0; $i < 10; $i++)
	{
		
		
		
		$command = 'DeleteImage'.($i+1);

		if (isset ($_POST[$command]))
		{
			/* Use the move_uploaded_file PHP function to move the file
			   from its temporary location to the product_images folder */
			/*move_uploaded_file($_FILES['Image7Upload']['tmp_name'],
							   SITE_ROOT . '/product_images/' .$this->mProductUnique .
							   $_FILES['Image7Upload']['name']);*/
			
			$image_name = 'image';
			if ($i != 0)
				$image_name .= '_'.($i+1);
			
			Image::DeleteProductImage2($this->mProduct[$image_name]);
	
			// Update the product's information in the database
			 Catalog::SetImageByNumber($this->_mProductId,
                          NULL, ($i==0?0:$i+1));
			 header('Location: ' .
			 		htmlspecialchars_decode(
			 				Link::ToProductAdmin($this->mMetaId, $this->_mDepartmentId,
			 						$this->_mCategoryId,
			 						$this->_mProductId)));
		}
		
		$image_ = 'image'.($i==0?'':'_'.($i+1));
		
		$this->mProduct[$image_.'_new']['big'] = $this->mProduct[$image_];
		if (!empty($this->mProduct[$image_]))
		{
			$new_name = sprintf("%07d", $this->mProduct['product_id']);
			$new_name .= ($i==0?'':'_'.($i+1)).'.jpg';
			$this->mProduct[$image_.'_new'] = Image::GetProductImageLink($new_name);
		}

			
	}
	
	$this->mProduct['thumbnail_new']['th'] = $this->mProduct['thumbnail'];
	if (!empty($this->mProduct['thumbnail']))
	{
		$new_name = sprintf("%07d", $this->mProduct['product_id']).'.jpg';
		$this->mProduct['thumbnail_new'] = Image::GetProductThumbnailLink($new_name);
	}
	
	if (isset ($_POST['DeleteImageThumbnail']))
	{
		Image::DeleteProductImage2($this->mProduct['thumbnail'], true);
		
		Catalog::SetThumbnail($this->_mProductId,  NULL);
		header('Location: ' .
				htmlspecialchars_decode(
						Link::ToProductAdmin($this->mMetaId, $this->_mDepartmentId,
								$this->_mCategoryId,
								$this->_mProductId)));
	}

    // Get product info
    
    
    $this->mWithWatermark = ($this->mProduct['watermark']==0)?false:true;
	
    
    // If uploading a product picture ...
    if (isset ($_POST['Upload']))
    {
    
    	/* Check whether we have write permission on the
    	 product_images folder */
    	/*if (!is_writeable(SITE_ROOT . '/product_images/'))
    	{
    		echo "Can't write to the product_images folder";
    
    		exit();
    	}*/
    
    	// If the error code is 0, the file was
    	 
    	$product_name = sprintf("%07d", $this->_mProductId);
    	 
    	// редактируем 10 картинок
    	for($i = 1, $image_upload = ''; $i <= 10; $i++)
    	{
    		if ($i == 1)
    			$image_upload = 'ImageUpload';
    		else
    			$image_upload = 'Image'.$i.'Upload';
    
    		if($_FILES[$image_upload]['error'] == 0)
    		{
    			$image_name = $product_name . (($i!=1)?('_' . $i):'' ). '.jpg';
    			
    			
    			
    			$result = Image::CreateProductImageWithPrint100_350_original2($_FILES[$image_upload], $image_name, $this->mWithWatermark);
    				
    			if ($result == 0)
    				self::SetImage($image_name, $i);
    			else
    			{
    				$this->mImagesError[$i-1] = true;
    				$this->mImagesError[$i-1]['text'] = $result;
    			}
    			/*move_uploaded_file($_FILES[$image_upload]['tmp_name'],
    			 SITE_ROOT . '/product_images/' .$this->mProductUnique .
    			$_FILES[$image_upload]['name']);
    
    			// Update the product's information in the database
    			self::SetImage($this->mProductUnique .$_FILES[$image_upload]['name'], $i);*/
    
    		}
            
    		header('Location: ' .
    				htmlspecialchars_decode(
    						Link::ToProductAdmin($this->mMetaId, $this->_mDepartmentId,
    								$this->_mCategoryId,
    								$this->_mProductId)));
    	}
    	
    	// If the error code is 0, the file was uploaded ok
    	if ($_FILES['ThumbnailUpload']['error'] == 0)
    	{
    		// Move the uploaded file to the product_images folder
    		/* move_uploaded_file($_FILES['ThumbnailUpload']['tmp_name'],
    		 SITE_ROOT . '/product_images/' .$this->mProductUnique .
    		 $_FILES['ThumbnailUpload']['name']);*/
    	
    		 
    		$product_name = sprintf("%07d", $this->_mProductId);
    	
    		$image_name = $product_name.'.jpg';
    		
    		$path = Image::GetProductThumbnailPath($image_name);
    	/*
    		echo '<h1>'.$path['th'].'</h1>';
    		echo '<h1>'.$path['th100'].'</h1>';*/
    		
    		$image_path = $path['th'];
    	
    		$image_path_100 = $path['th100'];
    	
    		Image::CreateImage($_FILES['ThumbnailUpload'], $image_path, 230, 230, $this->mWithWatermark, $image_path_100);
    	
    		// Update the product's information in the database
    		Catalog::SetThumbnail($this->_mProductId, $image_name);
    		
    			header('Location: ' .
    			htmlspecialchars_decode(
    		$this->mLinkToProductDetailsAdmin));
    	}
    }
	
	// If uploading a product picture ...
    if (isset ($_POST['Upload234']))
    {
	
      /* Check whether we have write permission on the 
          product_images folder */
      if (!is_writeable(SITE_ROOT . '/product_images/'))
      {
        echo "Can't write to the product_images folder";

        exit();
      }

      // If the error code is 0, the file was 
	  
	  $product_name = Link::NormalizeStringToURL2($this->mProduct['name']);
	  
	  // редактируем 10 картинок
	  for($i = 1, $image_upload = ''; $i <= 10; $i++)
	  {
		  if ($i == 1)
		  	$image_upload = 'ImageUpload';
		  else
		  	$image_upload = 'Image'.$i.'Upload';
		  
		  if($_FILES[$image_upload]['error'] == 0)
		  {
			  $image_name = $product_name . '-'.$this->_mProductId.'-i' . $i . '.jpg';
			  
			  $result = Image::CreateProductImageWithPrint100_350_original($_FILES[$image_upload], $image_name, $this->mWithWatermark);
			  
			  if ($result == 0)
			  	self::SetImage($image_name, $i);
			  else
			  {
			    $this->mImagesError[$i-1] = true;
				$this->mImagesError[$i-1]['text'] = $result;
			  }
			  /*move_uploaded_file($_FILES[$image_upload]['tmp_name'],
                           SITE_ROOT . '/product_images/' .$this->mProductUnique .
                           $_FILES[$image_upload]['name']);

				// Update the product's information in the database
				self::SetImage($this->mProductUnique .$_FILES[$image_upload]['name'], $i);*/
						  				
		  }
		  
		  header('Location: ' .
             htmlspecialchars_decode(
               Link::ToProductAdmin($this->mMetaId, $this->_mDepartmentId,
                                    $this->_mCategoryId,
                                    $this->_mProductId)));
	  }


      // If the error code is 0, the file was uploaded ok
      if ($_FILES['ThumbnailUpload']['error'] == 0)
      {
        // Move the uploaded file to the product_images folder
       /* move_uploaded_file($_FILES['ThumbnailUpload']['tmp_name'],
                           SITE_ROOT . '/product_images/' .$this->mProductUnique .
                           $_FILES['ThumbnailUpload']['name']);*/

      	
      	$product_name = sprintf("%07d", $this->_mProductId);

		$image_name = $product_name.'jpg';
		$path = Image::GetProductThumbnailPath($image_name);
		
		$image_path = $path['th'];
		
		$image_path_100 = $path['th100'];
		
		Image::CreateImage($_FILES['ThumbnailUpload'], $image_path, 230, 230, $this->mWithWatermark, $image_path_100);
		
        // Update the product's information in the database
        Catalog::SetThumbnail($this->_mProductId, $image_name);
		/*					  
		header('Location: ' .
             htmlspecialchars_decode(
               $this->mLinkToProductDetailsAdmin));*/
      }
    }
	
	
	
	
	$this->mBrendsList = Catalog::GetBrends();
	
	foreach($this->mBrendsList as $brend)
		if ($brend['brend_id'] == $this->mProduct['brend_id'])
		{
			$this->mProduct['brend_name'] = $brend['name'];
			break;
		}
	
	/////////////////////////////////////////
	//
	// Обратботка видео для продукта
	//
	/////////////////////////////////////////
	$all_videos = Catalog::GetVideosIdNameDur(0, false);
	$product_videos = Catalog::GetVideosIdNameDur($this->_mProductId);
	
	//$vtemp1; $vtemp2;
	
	 
	 
	$vtemp1;
	 
	// Show the categories the product belongs to
    for ($i = 0; $i < count($product_videos); $i++)
      $vtemp1[$product_videos[$i]['video_id']] =
        $product_videos[$i]['name'];  
	
	for ($i = 0; $i < count($all_videos); $i++)
		  $vtemp2[$all_videos[$i]['video_id']] =
			$all_videos[$i]['name'];
	
	if (!empty($vtemp1))
	{
		$this->mRemoveFromVideos = $vtemp1;
		$this->mProductVideosString = implode(', ', $vtemp1);	
		$this->mAssignOrMoveVideoTo = array_diff($vtemp2, $vtemp1);
		
		if (empty($this->mAssignOrMoveVideoTo))
			$this->mFreedomVideo = false;
	}
	else
	{
		$this->mVideoInProduct = false;
		$this->mAssignOrMoveVideoTo = $vtemp2;
	}
	
	////////////////////////////////////////
	////////////////////////////////////////
	////////////////////////////////////////
	
	// If assigning the product to another category ...
    if (isset ($_POST['RemoveArticle']))
    {
     $target_article_id = $_POST['TargetArticleIdRemove'];
      Articles::RemoveArticleFrom($this->_mProductId, 'product',
                                       $target_article_id);
    }
	
	// If assigning the product to another category ...
    if (isset ($_POST['AssignArticle']))
    {
      $target_article_id = $_POST['TargetArticleIdAssign'];
      Articles::AssignToArticle($this->_mProductId, 'product',
                                       $target_article_id);
    }
	
	// Работаем со статьями
	/////////////////////////////////////////
	//
	// Обратботка статей
	//
	/////////////////////////////////////////
	$all_articles = Articles::GetArticlesShortInfo();
	$item_articles = Articles::GetArticlesShortInfo($this->_mProductId, 'product');
		 
	$atemp1;
	 
	// Show the categories the product belongs to
    for ($i = 0; $i < count($item_articles); $i++)
      $atemp1[$item_articles[$i]['article_id']] =
        $item_articles[$i]['name'];  
	
	for ($i = 0; $i < count($all_articles); $i++)
		  $atemp2[$all_articles[$i]['article_id']] =
			$all_articles[$i]['name'];
	
	if (!empty($atemp1))
	{
		$this->mRemoveFromArticles = $atemp1;
		$this->mProductArticlesString = implode(', ', $atemp1);	
		$this->mAssignOrMoveArticleTo = array_diff($atemp2, $atemp1);
		
		if (empty($this->mAssignOrMoveArticleTo))
			$this->mFreedomArticle = false;
	}
	else
	{
		$this->mArticleInProduct = false;
		$this->mAssignOrMoveArticleTo = $atemp2;
	}
	
	////////////////////////////////////////
	////////////////////////////////////////
	////////////////////////////////////////
	
    $product_categories = Catalog::GetCategoriesForProduct($this->_mProductId);

    
	
    if (count($product_categories) == 1)
      $this->mRemoveFromCategoryButtonDisabled = true;

    // Show the categories the product belongs to
    for ($i = 0; $i < count($product_categories); $i++)
      $temp1[$product_categories[$i]['category_id']] =
        'id'.$product_categories[$i]['category_id'].'#'.$product_categories[$i]['name'];

    $this->mRemoveFromCategories = $temp1;
    $this->mProductCategoriesString = implode(', ', $temp1);
    $all_categories = Catalog::GetMetaCategories($this->mMetaId);

    for ($i = 0; $i < count($all_categories); $i++)
      $temp2[$all_categories[$i]['category_id']] =
        'id'.$all_categories[$i]['category_id'].'#'.$all_categories[$i]['name'];

    $this->mAssignOrMoveTo = array_diff($temp2, $temp1);
	
	///////////////////////////////////////
	// Работа фильтра
	///////////////////////////////////////
	$this->mFilter = Catalog::GetFilterInfo($this->_mCategoryId, 'category');
	
	
	$this->mFilterAttributes = Catalog::GetProductFilterAttributes($this->_mProductId);
	if (isset($this->mFilterAttributes[0]))
		$this->mFilterAttributesCount = count($this->mFilterAttributes);
		
		//print_r($this->mFilterAttributes);
	
	$attributes = Catalog::GetAllAttributeValues();
	
	$product_attributes =
      Catalog::GetProductAttributes($this->_mProductId, true);
	
	//this->mFilterAttributes[0]['attribute_name'] = '';
	//$this->mFilterAttributes[0]['attribute_value'] = true;
	for ($i = 0, $fa = 0, $pa = 0; $i < count($attributes); $i++)
	{
	
		$this->mFilterAttributes[$fa]['use'] = false;
		//$this->mFilterAttributes[$fa]['attribute_value'] = '';
		//if (!isset($this->mFilterAttributes[$fa][])
		
		// Проверяем, входит ли аттрибут в фильтр
		$in_filter = false;
		if (isset($this->mFilterAttributes[$fa]['attribute_value_id']))
		if (($fa < count($this->mFilterAttributes)) && ($attributes[$i]['attribute_value_id'] == $this->mFilterAttributes[$fa]['attribute_value_id']))
		{
			// атрибут входит в фильтр
			$in_filter = true;
			$fa++;
		}
		
		// Проверяем принадлежит ли аттрибут продукту
		$in_product = false;
		if (isset($product_attributes[$pa]['attribute_value_id']))
		if (($pa < count($product_attributes)) && ($attributes[$i]['attribute_value_id'] == $product_attributes[$pa]['attribute_value_id']))
		{
			// атрибут принадлежит продукту
			$in_product = true;
			$pa++;
		}
		
		//свойство не в фильтре и не принадлежит объекту
			/*$this->mFilterAttributes[($fa-1)]['attribute_name'] = '';*/
			
		if ($in_filter && $in_product)
			//свойство в фильтре и присвоен
			$this->mFilterAttributes[($fa-1)]['use'] = true;
		elseif ($in_filter && !$in_product)
			//свойство в фильтре, но не присвоено
			$this->mFilterAttributes[($fa-1)]['use'] = false;
		elseif (!$in_filter && $in_product)
			//свойство не в фильтре но принадлежит продукту
			$this->mProductAttributes[$product_attributes[$pa-1]['attribute_value_id']] =
				$product_attributes[$pa-1]['attribute_name'] . ': ' .
				$product_attributes[$pa-1]['attribute_value'];
		else
			//свойство не в фильтре и не принадлежит объекту
			$this->mCatalogAttributes[$attributes[$i]['attribute_value_id']] =
				$attributes[$i]['attribute_name'] . ': ' .
				$attributes[$i]['attribute_value'];

	}
	
	$this->mAvailability = Catalog::GetAdminAvailabilityForProduct($this->_mProductId);

	$this->mProviders = Catalog::GetAdminProviders();
	$this->mProductProviders = Catalog::GetAdminProviders($this->_mProductId);
	
	$this->mProvidersProductCount = count($this->mProductProviders);
	
	$this->mProvidersCount = count($this->mProviders);
	$count = $this->mProvidersCount;
	for ($i=0; $i<$this->mProvidersCount; $i++)
	{
		$this->mProviders[$i]['show'] = true;
		
		foreach($this->mProductProviders as $pp)
		{
			if ($pp['sale_point_id'] == $this->mProviders[$i]['sale_point_id'])
			{
				$this->mProviders[$i]['show'] = false;
				$count--;
				break;
			}
		}
	}
	$this->mProvidersCount = $count;
	//$this->mProviders = array_diff($this->mProviders, $this->mProductProviders);
	
	//$this->mProvidersCount = count($this->mProviders);
	
	foreach ($this->mAvailability as &$availability)
	{
	        $availability['show'] = true;
			
			// - проверяем, если это поставщик - нужно ли его показывать
			if ($availability['role'] == 1)
			{
				$show = false;
				foreach ($this->mProductProviders as $provider)
				{
					if ($provider['sale_point_id'] == $availability['sale_point_id'])
					{
						$show = true;
						break;
					}	
				}
				$availability['show'] = $show;
			}	
			
	}
	
	
	
	
	if (isset ($_POST['UpdateYamtInfo']))
	{
		$articul = $_POST['yamt_articul'];
		$description = $_POST['yamt_description'];
		$model = $_POST['yamt_model'];
		
		Yml::UpdateProductYamtInfo($this->_mProductId, $articul, $description, $model);
		
		header('Location: ' .
             htmlspecialchars_decode(
               $this->mLinkToProductDetailsAdmin));
	}
	

	
	
	if (isset ($_POST['UpdateAvailability']))
    {
		$what_change = array();
		// Проверяем - какие были изменения
		// исходные данные $this->mAvailability
		
		$quantity = $_POST['product_quantity_0'];
		$purchase_cost = $_POST['product_purchase_cost_0'];
		$retail_cost = $_POST['product_retail_cost_0'];
		
		if (($quantity != $this->mProduct['quantity'] ||
		    $purchase_cost != $this->mProduct['purchase_cost'] ||
			$retail_cost != $this->mProduct['retail_cost']) &&
			(is_numeric($quantity) ||
			is_numeric($purchase_cost) ||
			is_numeric($retail_cost))
			)
			{
				// Изменили какой то параметр у поставщика
				// все данные корректны - меняем все
				
				// Здесь у нас есть момент с дисконтной ценой
				// Обработаем этот вариант
				$discounted_price = ($retail_cost <= $this->mProduct['discounted_price'])?0:$this->mProduct['discounted_price'];
				
				array_push($what_change, 
					array('sale_point_id' => 0, 
					      'retail_cost' => empty($retail_cost)?0:$retail_cost,
						  'quantity' => empty($quantity)?0:$quantity, 
						  'purchase_cost' => empty($purchase_cost)?0:$purchase_cost,
						  'discounted_price' => $discounted_price));
				
				;
			}
		
		
		
		foreach ($this->mAvailability as $availability)
		{
			
			$quantity = $_POST['product_quantity_'.$availability['sale_point_id']];
			$purchase_cost = $_POST['product_purchase_cost_'.$availability['sale_point_id']];
			$retail_cost = $_POST['product_retail_cost_'.$availability['sale_point_id']];
			$role = $_POST['product_sp_role_'.$availability['sale_point_id']];
			
		
			if (($quantity != $availability['quantity'] ||
				$purchase_cost != $availability['purchase_cost'] ||
				$retail_cost != $availability['retail_cost']) &&
				(is_numeric($quantity) ||
				is_numeric($purchase_cost) ||
				is_numeric($retail_cost))
				)
				{
					// Изменили какой то параметр
					// все данные корректны - меняем все
					$add_new = ($availability['role']==0)?(empty($availability['quantity'])?true:false):false;
					array_push($what_change, 
						array('sale_point_id' => $availability['sale_point_id'],
							  'quantity' => empty($quantity)?0:$quantity, 
							  'retail_cost' => empty($retail_cost)?0:$retail_cost,
							  'purchase_cost' => empty($purchase_cost)?0:$purchase_cost,
							  'role' => $role,
							  'add_new' => $add_new));
					
					;
				}			
		}
		
		if (count($what_change) > 0)
			Catalog::ChangeProductAvailability($what_change, $this->_mProductId);
		
        header('Location: ' .
             htmlspecialchars_decode(
               $this->mLinkToProductDetailsAdmin));
    }
	
  }
  
  public function SetImage($imageName, $imageNumber)
  {
	  if ($imageNumber == 1)
	  {
		  Catalog::SetImage($this->_mProductId, $imageName);
	  }
	  elseif ($imageNumber == 2)
	  {
		  Catalog::SetImage2($this->_mProductId, $imageName);
	  }
	  else
	  {
		  Catalog::SetImageByNumber($this->_mProductId,
                          $imageName, $imageNumber);
	  }
  }
  
  public function EditAccessories()
  {
	  foreach ($this->mAccessories as &$product)
	  {
		$product['link'] = Link::ToProduct2($product['product_id'], $product['name']);
		
		$name = sprintf("%07d", $product['product_id']).'.jpg';
		$product['link_to_image'] = Image::GetProductThumbnailLink($name);
	  }
	  
	  // Добавляем новые аксессуары
	  if (isset($_POST['AddAccessories']))
	  {
		 // Парсим строку ввода и получаем из нее номера продуктов
		 $input_string = $_POST['accessories'];
		 
		 if (empty($input_string))
		 {
			 $this->mAccessoriesError = 'Введена пустая строка';
		 	return 0;
		 }
		
		// Выбираем все введенные числа из строки	
		preg_match_all('/\d+/', $input_string, $temp);
		
		if (count($temp[0]) == 0)
		{
			$this->mAccessoriesError = 'Не введено ни одного номера продукта';
			// найдено 0 элементов	
			return 0;
		}
		
		// удаляем повторяющиеся значения
		$accessories = array_unique($temp[0]);
		

		if (!empty($this->mAccessories))
		{
			foreach ($accessories as &$product_id)
			{
				if ($product_id == $this->_mProductId)
				    // самого себя в аксессуары добавить нельзя
					$product_id = 0;
				else
					//проверяем, не добавлены ли уже эти аксессуары к продукту
					foreach ($this->mAccessories as $product_accessori)
						if ($product_id == $product_accessori['product_id'])
						{
							$product_id = 0;
							break;
						}
			}
					
			$accessories = array_unique($accessories); // оставляем один ноль

		}
		
		// удаляем если есть нулевые значения
		$accessories = array_flip($accessories); //Меняем местами ключи и значения
		unset ($accessories[0]) ;                      //Удаляем элемент массива
		$accessories = array_flip($accessories); //Меняем местами ключи и значения	
		
		if (empty($accessories))
		{
			$this->mAccessoriesError = 'Такие аксессуары уже добавлены';
			return 0;
		}
		
		// проверяем продукты на существование
		$products_id_array = Catalog::ProductsTestingForTheExistence($accessories);
		
		// переинициализируем массив, надеюсь сборщик мусора соберет потерянные данные
		$accessories = array();
		
		// собираем массив с корректными product_id для добавления их в аксессуары
		foreach ($products_id_array as $product_id)
			array_push($accessories, $product_id['product_id']);
		
		if (empty($accessories))
		{
			$this->mAccessoriesError = 'Введенных продуктов не существует: ';
			
			foreach ($temp[0] as $product_id)
				$this->mAccessoriesError .= $product_id.' ';
			
			
				
			return 0;
		}
		else
		{
			Catalog::AddAccessoiresToProduct($this->_mProductId, $accessories);
			
			header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToProductDetailsAdmin));

        	exit();
		}
		
	  }
	  
	  
	  $action = '';
	  $action_id = '';
	  
	  foreach ($_POST as $key => $value)
		  // If a submit button was clicked ...
		  if (substr($key, 0, 6) == 'submit')
		  {
		  
			/* Get the position of the last '_' underscore from submit
			   button name e.g strtpos('submit_edit_dept_1', '_') is 17 */
			$last_underscore = strrpos($key, '_');
	
			/* Get the scope of submit button
			   (e.g  'edit_dep' from 'submit_edit_dept_1') */
			$action = substr($key, strlen('submit_'),
									 $last_underscore - strlen('submit_'));
	
			/* Get the meta id targeted by submit button
			   (the number at the end of submit button name)
			   e.g '1' from 'submit_edit_dept_1' */
			$action_id = substr($key, $last_underscore + 1);
	
			break;
		  }
		  
	if ($action == 'del_accessoir')
    {
		Catalog::DeleteAccessoir($this->_mProductId, $action_id);
		
		header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToProductDetailsAdmin));

       exit();
    }
	
	if ($action == 'make_analog')
	{
		Catalog::SetAccessoirType($this->_mProductId, $action_id, 1);
		
		header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToProductDetailsAdmin));

       exit();
	}
	
	if ($action == 'make_accessoir')
	{
		Catalog::SetAccessoirType($this->_mProductId, $action_id, 0);
		
		header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToProductDetailsAdmin));

       exit();
	}
	
	

}
	  
	  
  
}
?>
