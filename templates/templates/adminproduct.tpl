<a href="{{LINK_TO_ADMIN_PRODUCTS}}">Вернуться к администрированию товаров</a>
<form method="post">

<div class="error">{{ERROR}}</div>

<p>
 Имя продукта:<br/>
 <input type="text" name="name" value="{{NAME}}" size="30" />
</p>

<p>Описание продукта:<br/>
<textarea name="description" rows="3" cols="60">{{DESCRIPTION}}</textarea>
</p>

<p>
Цена продукта: <br/>
<input type="text" name="price" value="{{PRICE}}" size="5" />
</p>

<p><input type="submit" name="UpdateProductInfo" value="Обновить"></p>

<p>
Image name: {{IMAGE}}
<input name="ImageUpload" type="file" value="Upload" />
<input type="submit" name="Upload" value="Upload" />
</p>
{{IMAGE_OPERATION}}



</form>