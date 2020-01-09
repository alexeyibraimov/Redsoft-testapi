<p class="aq red nobotm"><strong>Создать заново структуру БД (данные будут удалены)</strong></p>
<?php if($_result){?><p class="green">Стурктура создана</p><?php }?>
<form id="gs" class="api_form" action="<?=$this->_path?>/bd/" method="post">
	<div class="formpole formpole_active">
		<label>&nbsp;</label>
		<div><input class="btn add_submit" name="create" value="Создать таблицы" type="submit"></div>
	</div>
</form>
