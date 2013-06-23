<h1>Model: <?= $modeltype ?></h1>
<a href="/admin/add/<?= $modeltype ?>">new item</a>
<hr />
<? if(sizeof($entries) == 0): ?>
	<pre>-no entries in <?= $modeltype ?>-</pre>
	<? return ?>
<? endif ?>
<table style="max-width: 100%; table-layout: auto; richness: inherit;">
	<tr>
		<? foreach(get_object_vars($entries[0]) as $key => $value): ?>
			<th valign="top" align="left" style="border-bottom: solid 2px;">
				<span class="s10"><?=$key ?></span>
			</th>
		<? endforeach ?>
	<th valign="top" style="border-bottom: solid 2px;">Actions</th>
	</tr>
	<? foreach($entries as $entry): ?>
		<tr>
			<? foreach(get_object_vars($entry) as $key => $value): ?>
				<td style="border-bottom: solid 1px;" valign="top" class="s10">
					<? if(in_array($key, array('pass', 'password'))): ?>
						*****
					<? else: ?>
						<? $value = htmlspecialchars(htmlentities($value)) ?>
						<?= Helper::cutText($value, 120) ?>
					<? endif ?>
				</td>
			<? endforeach ?>
				<td valign="top" style="border-bottom: solid 1px;">
					<a href="/admin/edit/<?= $modeltype ?>/<?= $entry->id ?>">edit</a>
					<a href="/admin/editplain/<?= $modeltype ?>/<?= $entry->id ?>">(plain)</a>
					<a class="delete" href="/admin/delete/<?= $modeltype ?>/<?= $entry->id ?>">delete</a>
				</td>
		</tr>
	<? endforeach ?>
</table>
<script type="text/javascript">
	$$('.delete').addEvent('click', function(){
		return confirm('really wanna delete this?'); 
	});
</script>