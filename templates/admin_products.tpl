{* admin_products.tpl *}
{load_presentation_object filename="admin_products" assign="obj"}
<form method="post"
 action="{$obj->mLinkToCategoryProductsAdmin}">
  
  {if $obj->mSubcategoryId}
    <h3>Editing products for subcategory: {$obj->mCategoryName} -> {$obj->mSubcategoryName} [
    <a href="{$obj->mLinkToDepartmentCategoriesAdmin}">
      back to subcategories ...</a> ]</h3>
      
      {if $obj->mAssignProductTo}
          <p class="bold-text">
            Добавить продукт в подкатегорию:
          </p>
          <p>
            {html_options name="TargetProductIdAssign"
             options=$obj->mAssignProductTo}
            <input type="submit" name="AssignProduct" value="Добавить" />
          </p>
       {/if}
          
          
  {else}
    <h3>Editing products for category: {$obj->mCategoryName} [
    <a href="{$obj->mLinkToDepartmentCategoriesAdmin}">
      back to categories ...</a> ]</h3>
  {/if}
  
{if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
{if $obj->mProductsCount eq 0}
  <p class="no-items-found">There are no products in this category!</p>
{else}
  <table class="tss-table">
    <tr>
      <th>Name</th>
      <th>Description</th>
      <th>Price</th>
      <th>Discounted Price</th>
      <th>Weight</th>
      <th width="80">&nbsp;</th>
    </tr>
  {section name=i loop=$obj->mProducts}
    <tr>
      <td>{$obj->mProducts[i].name}</td>
      <td>{$obj->mProducts[i].description}</td>
      <td>{$obj->mProducts[i].price}</td>
      <td>{$obj->mProducts[i].discounted_price}</td>
      <td>{$obj->mProducts[i].weight}</td>
      <td>
        <input type="submit"
         name="submit_edit_prod_{$obj->mProducts[i].product_id}"
         value="Edit" />
         {if $obj->mSubcategoryId}
         <input type="submit"
         name="submit_del_from_subcat_{$obj->mProducts[i].product_id}"
         value="Удалить из подкатегории" />
         {/if}
		 {if $obj->mProducts[i].visible == 1}
		<input type="submit"
         name="submit_unvisible_{$obj->mProducts[i].product_id}"
         value="Сделать невидимым" />
		 {else}
		 <input type="submit"
         name="submit_visible_{$obj->mProducts[i].product_id}"
         value="Сделать видимым" />
		 {/if}
      </td>
    </tr>
  {/section}
  </table>
{/if}
  <h3>Add new product:</h3>
  <input type="text" name="product_name" value="[name]" size="30" />
  <input type="text" name="product_description" value="[description]"
   size="60" />
  <input type="text" name="product_price" value="[price]" size="10" />
  <input type="text" name="product_weight" value="[weight, g]" size="10" />
  
  Тип: <select name="type">
   <option value="0">--</option> 
   {section name=i loop=$obj->mProductTypes}	
    	<option value="{$obj->mProductTypes[i].product_type_id}">{$obj->mProductTypes[i].name}</option>   {/section}
   </select>
  
  Бренд: <select name="brend">
   <option value="0">--</option> 
   {section name=i loop=$obj->mBrendsList}	
    	<option value="{$obj->mBrendsList[i].brend_id}">{$obj->mBrendsList[i].name}</option>   
   {/section}
   </select>
   Артикул товара (необязательно): <input type="text" name="product_article" value="" size="20" />
  <input type="submit" name="submit_add_prod_0" value="Add" />
</form>
