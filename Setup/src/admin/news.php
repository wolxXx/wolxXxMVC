<h1>edit news</h1>

<h2>new item:</h2>
<form action="/admin/writeNews" method="post">
	<label for="title">Title</label>
	<input name="title" id="title" />

	<label for="text">Text</label>
	<textarea rows="1000" cols="1000" name="text" id="text"></textarea>

	<label for="active">active?</label>
	<input type="checkbox" name="is_active" id="active" />

	<input type="submit" value="save" />
</form>

<h2>existing news</h2>
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
		<? foreach($news as $current): ?>
			<tr>
				<td>
					<input name="title[<?= $current->id ?>]" value="<?= $current->title ?>" />
				</td>
				<td width="50%">
					<textarea rows="1000" cols="1000" name="text[<?= $current->id ?>]"><?= $current->text ?></textarea>
				</td>
				<td>
					<input id="cb<?= $current->id ?>" type="checkbox" name="is_active[<?= $current->id ?>]" <?= '1' === $current->is_active? 'checked="checked"' : '' ?> />
					<label for="cb<?= $current->id ?>">active</label>

					<p>
						<a class="deletePrompter" href="/admin/delete/news/<?= $current->id ?>">delete</a>
					</p>
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