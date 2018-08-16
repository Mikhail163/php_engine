{* admin_product_details.tpl *}
{load_presentation_object filename="admin_product_details" assign="obj"}
<form enctype="multipart/form-data" method="post"
 action="{$obj->mLinkToProductDetailsAdmin}">
  <h3>
    Editing product: ID #{$obj->mProduct.product_id} &mdash;
    {$obj->mProduct.name} [
    <a href="{$obj->mLinkToCategoryProductsAdmin}">
      back to products ...</a> ]
  </h3>
  {if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
  <div style="width:100%; float:left">
  <div style="width:60%; float:left;">
  <div style="border: 1px solid #000000;">
  <p>
  	{if $obj->mProduct.visible == 1}
		<input type="submit"
         name="unvisible"
         value="Сделать невидимым" />
		 {else}
		 <input type="submit"
         name="visible"
         value="Сделать видимым" />
		 {/if}
         {if $obj->mProduct.for_sale == 1}
		<input type="submit"
         name="unsale"
         value="Cнять с продаж" />
         
         <input type="submit"
         name="pricenegotiable"
         value="Цена договорная" />
         
		 {else}
         {if $obj->mProduct.for_sale == 2}
         <input type="submit"
         name="pricenormal"
         value="Цена фиксированная" />
         {else}
		 <input type="submit"
         name="forsale"
         value="Добавить в продажу" />
         Продукт заменен на (product_id) <input type="text" name="replace_product_id"
             value="{$obj->mProduct.replace_product_id}" size="5" />
         <input type="submit" name="replaceProduct" value="Заменить" />
         {/if}
		 {/if}
		 </p>
            <p class="bold-text">
            Имя продукта: <input type="text" name="name"
            value="{$obj->mProduct.name}" size="30" />
            
            Артикул: <input type="text" name="articul"
            value="{$obj->mProduct.articul}" size="20" />
          </p>
          
          <p class="bold-text">
            Offer (спец предложение - пишется красным на пиктограмме продукта): <input type="text" name="offer"
            value="{$obj->mProduct.offer}" size="30" />
          </p>
          
          <p class="bold-text">
            Бренд: 
            {strip}
            <select name="brend"> 
               {section name=i loop=$obj->mBrendsList}	
                    <option value="{$obj->mBrendsList[i].brend_id}" 
                    {if $obj->mBrendsList[i].brend_id == $obj->mProduct.brend_id}selected="selected"{/if}>
                    {$obj->mBrendsList[i].name}</option>   
               {/section}
            </select>
            {/strip}
          </p>
          
          <p class="bold-text">
            Тип продукта: 
            {strip}
            <select name="type">
            	<option value="0" {if $obj->mProduct.type_id == 0}selected="selected"{/if}>
                    --</option> 
               {section name=i loop=$obj->mProductTypes}	
                    <option value="{$obj->mProductTypes[i].product_type_id}" 
                    {if $obj->mProductTypes[i].product_type_id == $obj->mProduct.type_id}selected="selected"{/if}>
                    {$obj->mProductTypes[i].name}</option>   
               {/section}
            </select>
            {/strip}
          </p>
          
          <p class="bold-text">
            Описание продукта:
          </p>
          <p>
            {strip}
            <textarea name="description" rows="3" cols="60">{$obj->mProduct.description}</textarea>
            {/strip}
          </p>
          <p class="bold-text">
            Цена продукта:
          </p>
          <p>
            <input type="text" name="price"
             value="{$obj->mProduct.price}" size="5" />
          </p>
          <p class="bold-text">
            Цена со скидкой:
          </p>
          <p>
            <input type="text" name="discounted_price"
             value="{$obj->mProduct.discounted_price}" size="5" />
          </p>
		  <p class="bold-text">
            Масса нетто (в граммах):
          </p>
          <p>
            <input type="text" name="weight"
             value="{$obj->mProduct.weight}" size="5" />
          </p>
		  <p class="bold-text">
            Длина (мм):
          </p>
          <p>
            <input type="text" name="lenght"
             value="{$obj->mProduct.lenght}" size="5" />
          </p>
		  <p class="bold-text">
            Высота в (мм):
          </p>
          <p>
            <input type="text" name="height"
             value="{$obj->mProduct.height}" size="5" />
          </p>
		  <p class="bold-text">
            Ширина в (мм):
          </p>
          <p>
            <input type="text" name="width"
             value="{$obj->mProduct.width}" size="5" />
          </p>
          <p>
            <input type="submit" name="UpdateProductInfo"
             value="Обновить" />
          </p>
</div>
<div style="border: 1px solid #000000;">
<h3>СЕО ПАРАМЕТРЫ</h3>
<p>Показывать описание <input name="show_description" type="checkbox"
      {if $obj->mProduct.show_description == 1}checked="checked"{/if} /></p>
<p class="bold-text">
            h1:<input type="text" name="h1"
            value="{$obj->mProduct.h1}" size="30" />
          </p>
          <p class="bold-text">
            title:<input type="text" name="title"
            value="{$obj->mProduct.title}" size="60" />
          </p>
<p class="bold-text">
            Keywords:
</p>
<p>
            {strip}
            <textarea name="keywords" rows="3" cols="60">{$obj->mProduct.keywords}</textarea>
            {/strip}
</p>
<p class="bold-text">
            Seo description:
</p>
<p>
            {strip}
            <textarea name="seo_description" rows="3" cols="60">{$obj->mProduct.seo_description}</textarea>
            {/strip}
</p>

<p>
            <input type="submit" name="UpdateSeoInfo"
             value="Обновить" />
          </p>
</div>	
<div style="border: 1px solid #000000;">
			<p class="bold-text">
            Set display option for this product:
          </p>
          <p>
            {html_options name="ProductDisplay"
             options=$obj->mProductDisplayOptions
             selected=$obj->mProduct.display}
            <input type="submit" name="SetProductDisplayOption" value="Set" />
          </p>

  <p>При добавлении новой фотографии 
  {if $obj->mProduct.watermark == 0}
    <input type="submit" name="SetWaterMark" value="Устанавливать WaterMark на изображение" />, сейчас watermark устанавливаться не будет
    {else}
    <input type="submit" name="UnsetWaterMark" value="Не ставить WaterMark на изображение" />, сейчас watermark будет устанавливаться 
    {/if}
  </p>

  <p>
    <font class="bold-text">Image name:</font> {$obj->mProduct.image_new.big}
    <input name="ImageUpload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_new.big}"
     border="0" alt="{$obj->mProduct.name} image" />
     <input type="submit" name="DeleteImage1" value="Удалить картинку 1">
  </p>
  {/if}
  <p>
    <font class="bold-text">Image 2 name:</font> {$obj->mProduct.image_2_new.big}
    <input name="Image2Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image_2}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_2_new.big}"
     border="0" alt="{$obj->mProduct.name} image 2" />
     <input type="submit" name="DeleteImage2" value="Удалить картинку 2">
  </p>
  {/if}
  <p>
    <font class="bold-text">Image 3 name:</font> {$obj->mProduct.image_3_new.big}
    <input name="Image3Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image_3}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_3_new.big}"
     border="0" alt="{$obj->mProduct.name} image 3" />
     <input type="submit" name="DeleteImage3" value="Удалить картинку 3">
  </p>
  {/if}
  <p>
    <font class="bold-text">Image 4 name:</font> {$obj->mProduct.image_4_new.big}
    <input name="Image4Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
    
  </p>
  {if $obj->mProduct.image_4}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_4_new.big}"
     border="0" alt="{$obj->mProduct.name} image 4" />
     <input type="submit" name="DeleteImage4" value="Удалить картинку 4">
  </p>
  {/if}
  <p>
    <font class="bold-text">Image 5 name:</font> {$obj->mProduct.image_5_new.big}
    <input name="Image5Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image_5}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_5_new.big}"
     border="0" alt="{$obj->mProduct.name} image 5" />
    <input type="submit" name="DeleteImage5" value="Удалить картинку 5"> 
  </p>
  {/if}
  <p>
    <font class="bold-text">Image 6 name:</font> {$obj->mProduct.image_6_new.big}
    <input name="Image6Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image_6}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_6_new.big}"
     border="0" alt="{$obj->mProduct.name} image 6" />
     <input type="submit" name="DeleteImage6" value="Удалить картинку 6">
  </p>
  {/if}
  
  <p>
    <font class="bold-text">Image 7 name:</font> {$obj->mProduct.image_7_new.big}
    <input name="Image7Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image_7}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_7_new.big}"
     border="0" alt="{$obj->mProduct.name} image 7" />
    <input type="submit" name="DeleteImage7" value="Удалить картинку 7"> 
  </p>
  {/if}
  
  <p>
    <font class="bold-text">Image 8 name:</font> {$obj->mProduct.image_8_new.big}
    <input name="Image8Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
    
  </p>
  {if $obj->mProduct.image_8}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_8_new.big}"
     border="0" alt="{$obj->mProduct.name} image 8" />
     <input type="submit" name="DeleteImage8" value="Удалить картинку 8">
  </p>
  {/if}
  
  <p>
    <font class="bold-text">Image 9 name:</font> {$obj->mProduct.image_9_new.big}
    <input name="Image9Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image_9}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_9_new.big}"
     border="0" alt="{$obj->mProduct.name} image 9" />
   <input type="submit" name="DeleteImage9" value="Удалить картинку 9">  
  </p>
  {/if}
  
  
  <p>
    <font class="bold-text">Image 10 name:</font> {$obj->mProduct.image_10_new.big}
    <input name="Image10Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image_10}
  <p>
    <img style="max-width:90%" src="{$obj->mProduct.image_10_new.big}"
     border="0" alt="{$obj->mProduct.name} image 10" />
     <input type="submit" name="DeleteImage10" value="Удалить картинку 10">
  </p>
  {/if}
  
  <p>
    <font class="bold-text">Thumbnail(230px*230px) name:</font> {$obj->mProduct.thumbnail_new.th}
    <input name="ThumbnailUpload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.thumbnail}
  <p>
    <img src="{$obj->mProduct.thumbnail_new.th}"
     border="0" alt="{$obj->mProduct.name} thumbnail" />
     <input type="submit" name="DeleteImageThumbnail" value="Удалить">
  </p>
  {/if}
</div>
<div style="border: 1px solid #000000;">	
          {if $obj->mProductVideosString}		
          <p>
            <font class="bold-text">Продукт есть в следующих видео:</font>
            {$obj->mProductVideosString}
          </p>
          {/if}
          {if $obj->mVideoInProduct}
          <p class="bold-text">
            Удалить видео из продукта:
          </p>
          <p>
            {html_options name="TargetVideoIdRemove"
             options=$obj->mRemoveFromVideos}
            <input type="submit" name="RemoveVideo" value="Remove"/>
          </p>
          {/if}
          {if $obj->mFreedomVideo}
          <p class="bold-text">
            Добавить видео к продукту:
          </p>
          <p>
            {html_options name="TargetVideoIdAssign"
             options=$obj->mAssignOrMoveVideoTo}
            <input type="submit" name="AssignVideo" value="Assign" />
          </p>
          {/if}
</div> 

<div style="border: 1px solid #000000;">	
 <p>	
          {if $obj->mArticleInProduct}
          <p class="bold-text">
            Удалить статью:
          </p>
          <p>
            {html_options name="TargetArticleIdRemove"
             options=$obj->mRemoveFromArticles}
            <input type="submit" name="RemoveArticle" value="Remove"/>
          </p>
          {/if}
          {if $obj->mFreedomArticle}
          <p class="bold-text">
            Добавить статью:
          </p>
          <p>
            {html_options name="TargetArticleIdAssign"
             options=$obj->mAssignOrMoveArticleTo}
            <input type="submit" name="AssignArticle" value="Assign" />
          </p>
          {/if}
  </p>
</div> 

 <div style="border: 1px solid #000000;">         
          <p>
            Поменять мета-раздел: 
            {strip}
            <select name="meta"> 
               {section name=i loop=$obj->mMetas}	
                    <option value="{$obj->mMetas[i].meta_id}" 
                    {if $obj->mMetas[i].meta_id == $obj->mProduct.meta_id}selected="selected"{/if}>
                    {$obj->mMetas[i].name}</option>   
               {/section}
            </select>
            {/strip}
            <input type="submit" name="ChangeMeta" value="ChangeMeta" />
          </p>
  </div>

<div style="border: 1px solid #000000;">			 
          <p>
            <font class="bold-text">Продукт относится к этим категориям:</font>
            {$obj->mProductCategoriesString}
          </p>
          <p class="bold-text">
            Удалить из:
          </p>
          <p>
            {html_options name="TargetCategoryIdRemove"
             options=$obj->mRemoveFromCategories}
            <input type="submit" name="RemoveFromCategory" value="Remove"
             {if $obj->mRemoveFromCategoryButtonDisabled}
             disabled="disabled" {/if}/>
          </p>
          <p class="bold-text">
            Добавить продукт в эту категорию:
          </p>
          <p>
            {html_options name="TargetCategoryIdAssign"
             options=$obj->mAssignOrMoveTo}
            <input type="submit" name="Assign" value="Assign" />
          </p>
          <p class="bold-text">
           Перемещения продукта в эту категорию:
          </p>
          <p>
            {html_options name="TargetCategoryIdMove"
             options=$obj->mAssignOrMoveTo}
            <input type="submit" name="Move" value="Move" />
            <input type="submit" name="RemoveFromCatalog"
             value="Удалить товар из каталога"
             {if !$obj->mRemoveFromCategoryButtonDisabled} disabled="disabled" {/if}/>
          </p>
  </div>        
          
  </div>
  <div style="width:40%; float:left;">

<table>
<tr><td colspan="2"><b>Редактор данных для яндекс маркета</b><input type="submit" name="UpdateYamtInfo" value="Обновить"  /</td></tr>
<tr><td>
Модель:<input type="text" name="yamt_model" value="{$obj->mProduct.model}" size="30" /></td><td>
Артикул:<input type="text" name="yamt_articul" value="{$obj->mProduct.articul}" size="30" /></td></tr>
<tr><td colspan="2">
Описание для яндекс маркета (максимум 175 символов)
<textarea name="yamt_description" rows="3" cols="60" maxlength="175">{$obj->mProduct.yamt_description}</textarea></td></tr>
</table>
  
  <table>
  <tr><td colspan="2"><b>Редактируем наличие</b></td><td colspan="2">Цена (рубли)</td></tr>
  <tr><td>Название точки</td><td>кол-во</td><td>Закупка</td><td>Розница</td></tr>
  <tr>
   <td>{$obj->mProduct.brend_name} (главный поставщик)</td>
   <td><input type="text" name="product_quantity_0" value="{$obj->mProduct.quantity}" size="3" /></td> 
   <td><input type="text" name="product_purchase_cost_0" value="{$obj->mProduct.purchase_cost}" size="6" /></td> 
   <td><input type="text" name="product_retail_cost_0" value="{$obj->mProduct.retail_cost}" size="6" /></td>
  </tr>
{section name=l loop=$obj->mAvailability}
{if $obj->mAvailability[l].show}
  <tr>
   <td>{$obj->mAvailability[l].sale_point_name} ({$obj->mAvailability[l].city_name}){if $obj->mAvailability[l].role==1}-поставщик{/if}</td>
   <td><input type="text" name="product_quantity_{$obj->mAvailability[l].sale_point_id}" value="{$obj->mAvailability[l].quantity}" size="3" /></td> 
   <td><input type="text" name="product_purchase_cost_{$obj->mAvailability[l].sale_point_id}" value="{$obj->mAvailability[l].purchase_cost}" size="6" /></td> 
   <td><input type="text" name="product_retail_cost_{$obj->mAvailability[l].sale_point_id}" value="{$obj->mAvailability[l].retail_cost}" size="6" />
       <input type="hidden" name="product_sp_role_{$obj->mAvailability[l].sale_point_id}" value="{$obj->mAvailability[l].role}"></td>
  </tr>
{/if}
{/section}
  
  <tr><td colspan="4"><input type="submit" name="UpdateAvailability" value="Обновить наличие и цены" /></td></tr>
  </table>
  
  
  
  <table style="text-align:center; border:1px;">
  <tr><td>Поставщик</td><td>Кол-во</td><td>Закупочная цена</td><td>РРЦ</td><td></td></tr>
  {if $obj->mProvidersCount>0}
  <tr><td colspan="5">Новые поставщики</td></tr>
  <tr><td>  
  {strip}
            <select name="add_provider"> 
               {section name=i loop=$obj->mProviders}	
               {if $obj->mProviders[i].show}
                    <option value="{$obj->mProviders[i].sale_point_id}">
                    {$obj->mProviders[i].sale_point_name}  ({$obj->mProviders[i].city_name})</option>   
               {/if}
               {/section}
            </select>
  {/strip}
  <td><input style="width:30px;" type="number" name="pr_quantity" value="0"/></td>
  <td><input type="text" name="pr_purchase_price" value="0" size="5" /></td>
  <td><input type="text" name="pr_retail_price" value="0" size="5" /></td>
  <td><input type="submit" name="addNewProviderToProduct" value="Добавить"></td>
  </tr>
  {/if}
  
  {if $obj->mProvidersProductCount>0}
  <tr><td colspan="5">Удалить поставщика</td></tr>
  <tr><td>  
  {strip}
            <select name="del_provider"> 
               {section name=i loop=$obj->mProductProviders}	
                    <option value="{$obj->mProductProviders[i].sale_point_id}">
                    {$obj->mProductProviders[i].sale_point_name}  ({$obj->mProductProviders[i].city_name})</option>   
               {/section}
            </select>
  {/strip}
  <td colspan="4"><input type="submit" name="delNewProviderToProduct" value="Удалить"></td>
  </tr>
  {/if}
  
  </table>
  
  
  <table style="border: 1px solid #000000;">
  			{if ($obj->mFilterAttributesCount != 0)}
			<td colspan="2"><b><u>Обязательные свойства продукта</u></b></td>
			{section name=j loop=$obj->mFilterAttributes}
			
				
				{if $obj->mFilterAttributes[j].attribute_value}
			
				{* Generate a new select tag? *}
				{if $smarty.section.j.first ||
				$obj->mFilterAttributes[j].attribute_name !==
				$obj->mFilterAttributes[j.index_prev].attribute_name}
				<tr>
				<td colspan="2"><b>{$obj->mFilterAttributes[j].attribute_name}</b></td>
				</tr>
				{/if}
				<tr>
				<td>
				{$obj->mFilterAttributes[j].attribute_value}
				</td>
				<td>
				
				<img id="img_product_attribute_updating_{$obj->mProduct.product_id}_{$obj->mFilterAttributes[j].attribute_value_id}" 
				class="updatingImage displayNone" src="http://www.ilodki.ru/resources/i/admin/waiting.gif">
                <div id="ProductAttributeValue_{$obj->mProduct.product_id}_{$obj->mFilterAttributes[j].attribute_value_id}">
				
				{if $obj->mFilterAttributes[j].use}
				<input type="submit" 
				onclick="return changeProductAttribute(this, 
				                    {$obj->mProduct.product_id}, 
				                    {$obj->mFilterAttributes[j].attribute_value_id}, 
				                    1);"
				name="submit_RemoveAttributeValue_{$obj->mFilterAttributes[j].attribute_value_id}" 
				
				value="Удалить">
				{*<a href="{$obj->mLinkToProductDetailsAdmin}&RemoveAttributeValue={$obj->mFilterAttributes[j].attribute_value_id}">Удалить</a>*}
				{else}
			    <input type="submit" 
			    	onclick="return changeProductAttribute(this, 
				                    {$obj->mProduct.product_id}, 
				                    {$obj->mFilterAttributes[j].attribute_value_id}, 
				                    0);"
			    name="submit_AssignAttributeValue_{$obj->mFilterAttributes[j].attribute_value_id}" 
			    
			    value="Назначить">	
				{*<a href="{$obj->mLinkToProductDetailsAdmin}&AssignAttributeValue={$obj->mFilterAttributes[j].attribute_value_id}">Назначить</a>*}
				{/if}
				</div>
				</td>
				</tr>	
				
				{/if}
				
			{/section}
			<tr>
			{/if}
				<td colspan="2">
				
				</td>
			</tr>
	</table>
    
    {if $obj->mProductAttributes}
          <p class="bold-text">
            Дополнительные атрибуты продукта:
          </p>
          <p>
            {html_options name="TargetAttributeValueIdRemove"
             options=$obj->mProductAttributes}
            <input type="submit" name="RemoveAttributeValue"
             value="Удалить" />
          </p>
          {/if}
          {if $obj->mCatalogAttributes}
          <p class="bold-text">
            Назначить дополнительные атрибуты продукту:
          </p>
          <p>
            {html_options name="TargetAttributeValueIdAssign"
             options=$obj->mCatalogAttributes}
            <input type="submit" name="AssignAttributeValue"
             value="Назначить" />
          </p>
   {/if}
          
          
       <p>Добавление аксессуаров
       
       {if $obj->mAccessoriesError}
       <span style="font-weight:bold; font-size:150%; color:#F00">{$obj->mAccessoriesError}</span>
       {/if}
       <br>
       Введите через пробел номера продуктов, которые хотите добавить в аксессуары
       <br>
       <textarea style="width:100%" name="accessories" rows="3" cols="60"></textarea>
       <br> 
       <input type="submit" name="AddAccessories" value="Добавить аксессуары" />
       <br />
       
       <table>
       {section name=i loop=$obj->mAccessories}
       <tr>
        <td>
        <a href="{$obj->mAccessories[i].link}"><img src="{$obj->mAccessories[i].link_to_image.th}"
        border="0" alt="{$obj->mAccessories[i].name} thumbnail" /></a>
        </td>
        <td>
        ID: {$obj->mAccessories[i].product_id}<br /><br />
        <a href="{$obj->mAccessories[i].link}">{$obj->mAccessories[i].name}
        </a><br /><br />

{if $obj->mAccessories[i].type == 0}
         <input type="submit" name="submit_make_analog_{$obj->mAccessories[i].product_id}" value="Сделать аналогом" />
{else}
		 <input type="submit" name="submit_make_accessoir_{$obj->mAccessories[i].product_id}" value="Сделать аксессуаром" />
{/if}
         <input type="submit" name="submit_del_accessoir_{$obj->mAccessories[i].product_id}" value="Удалить" />
        
        </td>
        </tr>
    	{/section}
        
        </table>
       </p>
      
       
  </div>
  </div>
</form>
