<?
	/**
	 * whats happening here?
	 * we have a model, we got from the database.
	 * now we want to adjust the input fields to the type of each column.
	 * and we want to have different fields for some models, e.g. a dropdown input for just one model
	 * and some fields should not been displayed, e.g. password fields
	 */
	
	$dbinfo = new DatabaseInformation();
	$database = DatabaseManager::getInstance()->getConnection()->getDatabase();
?>

<h1>Edit item of <?= $modeltype ?> with ID <?= $model->id ?></h1>
<a href="/admin/listing/<?= $modeltype ?>">back to <?= ucfirst($modeltype) ?>-Overview</a>

<hr>

<form action="" method="post">
	<? foreach(get_object_vars($model) as $key => $value): ?>
		<? 
			$type = $dbinfo->getTypeOfColumn($database, $modeltype, $key);
			$type = $type->DATA_TYPE; 
		?>
		<div class="grid_3">
			<label style="width: 300px;" for="inp<?= $key ?>"><?= $key ?> (<?= $type ?>)</label>
		</div>
		<div class="grid_10">
			<? if($type == 'varchar'): ?>
				<? if($key == 'password'): ?>
					<input readonly="readonly" id="inp<?= $key ?>" value="<?= $value ?>" name="updatedata[<?= $key ?>]" />
				<? else: ?>
					<input id="inp<?= $key ?>" value="<?= $value ?>" name="updatedata[<?= $key ?>]" />
				<? endif ?>
			<? endif ?>
			<? if($type == 'int'): ?>
				<? if($key == 'id'): ?>
					<input readonly="readonly" id="inp<?= $key ?>" value="<?= $value ?>" name="updatedata[<?= $key ?>]" />
				<? else: ?>
					<input id="inp<?= $key ?>" value="<?= $value ?>" name="updatedata[<?= $key ?>]" />
				<? endif ?>
			<? endif ?>
			<? if(in_array($type, array('datetime', 'tinyint'))): ?>
				<input id="inp<?= $key ?>" value="<?= $value ?>" name="updatedata[<?= $key ?>]" />
			<? endif ?>			
			<? if($type == 'text'): ?>
				<textarea rows="1000" cols="1000" name="updatedata[<?= $key ?>]"><?= $value ?></textarea>
			<? endif ?>
		</div>
		<div class="clear"></div>
		<hr>
	<? endforeach ?>
	<div class="grid_3">
		&nbsp;
	</div>
	<div class="grid_5">
		<input value="save" type="submit" />
	</div>
	<div class="clear"></div>
</form>