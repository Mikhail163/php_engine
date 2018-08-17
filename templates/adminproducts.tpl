<form method="post">
<div class="error">{{ERROR}}</div>
<div class="admin_products">
<div class="admin_product_info admin_product_title">
	<div class="admin_product_id">ID</div>
	<div class="admin_product_name">НАЗВАНИЕ</div>
	<div class="admin_product_price">ЦЕНА</div>
	<div class="admin_product_description">ОПИСАНИЕ</div>
	<div class="admin_product_control">&nbsp;</div>
</div>
{{PRODUCT}}
</div>
<h3>Добавить новый товар:</h3>
  <input type="text" name="product_name" value="[name]" size="30" />
  <input type="text" name="product_description" value="[description]"
   size="60" />
  <input type="text" name="product_price" value="[price]" size="10" />
  <input type="submit" name="submit_add_prod_0" value="Add" />

</form>