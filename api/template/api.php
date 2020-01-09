<p>Взаимодействие с пользователем происходит посредством HTTP запросов к API серверу. Все ответы представляют собой JSON объекты.</p>
<p>Сервер реализует следующие методы:</p>
<p class="aq red nobotm"><strong>Выдача товара по ID</strong></p>
<p class="green">https://домен<?=$this->_path?>/gi/?id=<strong class="red">XXX</strong></p>
<p>где <strong class="red">XXX</strong> - идентификатор товара в виде числа</p>
<form id="gi" class="api_form" action="<?=$this->_path?>/gi/" method="get">
	<div class="formpole formpole_active">
		<label>ID товара:</label>
		<div><input class="form-control" name="id" value="" type="text"></div>
	</div>
	<div class="formpole formpole_active">
		<label>&nbsp;</label>
		<div><input class="btn add_submit" value="Запросить" type="submit"></div>
	</div>
</form>
<p class="aq red nobotm"><strong>Выдача товаров по вхождению подстроки в названии</strong></p>
<p class="green">https://домен<?=$this->_path?>/gq/</p>
<p>параметр передаваемый методом POST:</p> 
<p><strong class="green">q =</strong> <strong class="red">QQQ</strong></p>
<p>где</p>
<p><strong class="red">QQQ</strong> - искомая строка</p>
<form id="gs" class="api_form" action="<?=$this->_path?>/gq/" method="post">
	<div class="formpole formpole_active">
		<label>Подстрока в названии товара:</label>
		<div><input class="form-control" name="q" value="" type="text"></div>
	</div>
	<div class="formpole formpole_active">
		<label>&nbsp;</label>
		<div><input class="btn add_submit" value="Найти" type="submit"></div>
	</div>
</form>
<p class="aq red nobotm"><strong>Выдача товаров по производителю/производителям</strong></p>
<p class="green">https://домен<?=$this->_path?>/gb/</p>
<p>параметры передаваемый методом POST: </p>
<p><strong class="green">bid =</strong> <strong class="red">XXX</strong> или</p>
<p><strong class="green">bq =</strong> <strong class="red">QQQ</strong></p>
<p>где</p>
<p><strong class="red">XXX</strong> - идентификатор производителя</p>
<p><strong class="red">QQQ</strong> - подстрока наименования производителя</p>
<form id="gs" class="api_form" action="<?=$this->_path?>/gb/" method="post" enctype="multipart/form-data">
	<div class="formpole formpole_active">
		<label>Подстрока в наименовании:</label>
		<div><input class="form-control" name="q" value="" type="text"></div>
	</div>
	<div class="formpole formpole_active">
		<label>Выберите производителя:</label>
		<div>
			<select class="form-control gbid" name="id[]" multiple="multiple">
				<option>-не выбрано-</option>
				<?php foreach($this->getManufactureList() as $_id=>$_name){?><option value="<?=$_id?>"><?=$_name?></option><?php }?>
			</select>
		</div>
	</div>
	<div class="formpole formpole_active">
		<label>&nbsp;</label>
		<div><input class="btn add_submit" value="Найти" type="submit"></div>
	</div>
</form>
<p class="aq red nobotm"><strong>Выдача товаров по разделу (только раздел)</strong></p>
<p class="green">https://домен<?=$this->_path?>/gs/</p>
<p>параметр передаваемый методом POST: </p>
<p><strong class="green">bid =</strong> <strong class="red">XXX</strong></p>
<p>где</p>
<p><strong class="red">XXX</strong> - идентификатор раздела</p>
<form id="gs" class="api_form" action="<?=$this->_path?>/gs/" method="post">
	<div class="formpole formpole_active">
		<label>Выберите раздел:</label>
		<div>
			<select class="form-control" name="id">
				<option>-не выбрано-</option>
				<?php foreach($this->getSectionList() as $_id=>$_name){?><option value="<?=$_id?>"><?=$_name?></option><?php }?>
			</select>
		</div>
	</div>
	<div class="formpole formpole_active">
		<label>&nbsp;</label>
		<div><input class="btn add_submit" value="Найти" type="submit"></div>
	</div>
</form>
<p class="aq red nobotm"><strong>Выдача товаров по разделу и вложенным разделам</strong></p>
<p class="green">https://домен<?=$this->_path?>/gst/</p>
<p>параметр передаваемый методом POST: </p>
<p><strong class="green">bid =</strong> <strong class="red">XXX</strong></p>
<p>где</p>
<p><strong class="red">XXX</strong> - идентификатор раздела</p>
<form id="gs" class="api_form" action="<?=$this->_path?>/gst/" method="post">
	<div class="formpole formpole_active">
		<label>Выберите раздел:</label>
		<div>
			<select class="form-control" name="id">
				<option>-не выбрано-</option>
				<?php foreach($this->getSectionList() as $_id=>$_name){?><option value="<?=$_id?>"><?=$_name?></option><?php }?>
			</select>
		</div>
	</div>
	<div class="formpole formpole_active">
		<label>&nbsp;</label>
		<div><input class="btn add_submit" value="Найти" type="submit"></div>
	</div>
</form>

<p></p>