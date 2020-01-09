<p class="aq red nobotm"><strong>Загрузка товаров используя csv файл</strong></p>
<?php if($_result){?><p class="green">Данные загружены</p><?php }?>
<p>Пример файла <a href="<?=$this->_path?>/export.csv">export.csv</a></p>
<p>Данные имеющихся уже в базе товаров будут обновлены, а новые добавлены</p>
<p>Новые производители будут добавлены, а соотв. товар к старым, будет привязан, как и к новым</p>
<p>Разделы могут быть любой вложенности: список названий разделов в соответствии с иерархией в файле требуется разделять ":", имеющиеся уже останутся, а новые структуры создадутся</p>
<form id="gs" class="api_form" action="<?=$this->_path?>/import/" method="post" enctype="multipart/form-data">
	<div class="formpole formpole_active">
		<label>Выберите файл csv:</label>
		<div><input class="form-control" name="fl" value="" type="file" style="margin: 9px;"></div>
	</div>
	<div class="formpole formpole_active">
		<label>&nbsp;</label>
		<div><input class="btn add_submit" value="Загрузить" type="submit"></div>
	</div>
</form>
