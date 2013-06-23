<h1>edit FAQs</h1>

<hr />

<h2>new item:</h2>
<form action="/admin/addfaq" method="post">
	<label for="title">Title</label>
	<input name="title" id="title" />
	
	<label for="text">Text</label>
	<textarea rows="1000" cols="1000" name="text" id="text"></textarea>
	
	<input type="submit" value="save" />
</form>

<hr />

<h2>existing FAQs</h2>
<form action="" method="post">
	<table>
		<tr>
			<th>Title</th>
			<th>Text</th>
			<th>State</th>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td>
				<input type="submit" value="save" />
			</td>
		</tr>
		<? foreach($faqs as $current): ?>
			<tr>
				<td>
					<input name="title[<?= $current->id ?>]" value="<?= $current->title ?>" />
				</td>
				<td width="50%">
					<textarea rows="1000" cols="1000" name="text[<?= $current->id ?>]"><?= $current->text ?></textarea>
				</td>
				<td>
					<input id="cb<?= $current->id ?>" type="checkbox" name="is_active[<?= $current->id ?>]" <?= '1' === $current->is_active? 'checked="checked"' : '' ?> /> 
					<label for="cb<?= $current->id ?>">aktive</label>
				</td>
			</tr>
		<? endforeach ?>
		<tr>
			<td colspan="2"></td>
			<td>
				<input type="submit" value="save" />
			</td>
		</tr>
	</table>
</form>