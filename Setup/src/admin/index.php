<h2>admin-page</h2>
<div class="grid_4 alpha">
	<h3>new users</h3>
	<? if(sizeof($newusers) == 0): ?>
		<pre>-none-</pre>
	<? endif ?>
	<? foreach($newusers as $user): ?>
		<?= $user->nick ?>(<?= $user->email ?>)<br />
		<ul>
			<li><a href="/admin/activateuser/<?= $user->id ?>">activate</a></li>
			<li><a href="/admin/banuser/<?= $user->id ?>">ban</a></li>
		</ul>
		<br />
	<? endforeach ?>
	<hr />
	<h3>FAQs</h3>
	<a href="/admin/faq">edit</a>
	
	<hr />
	<h3>News</h3>
	<a href="/admin/news">edit</a>
</div>
<div class="grid_4 alpha">
	<h3>Models</h3>
	<ul>
	<? foreach($models as $model): ?>
		<li>
			<a href="/admin/listing/<?= $model->TABLE_NAME ?>"><?= $model->TABLE_NAME ?> </a>
		</li>
	<? endforeach ?>
	</ul>
</div>
<div class="clear"></div>
