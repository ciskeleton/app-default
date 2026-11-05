<?= $hub->menus->render('sidebar', [
	// Menu.
	'menu_attrs' => ['class' => 'nav flex-column bg-light rounded-2'],

	// Menu item.
	'item_attrs' => ['class' => 'nav-item'],

	// Menu link.
	'link_attrs' => ['class' => 'nav-link'],
]) ?>
<div class="card shadow-sm">
	<div class="card-header bg-body-secondary">
		<h2 class="card-title h6 mb-0"><?= line('sidebar_heading') ?></h2>
	</div>
	<div class="card-body">
		<p class="mb-0"><?= line('sidebar_content') ?></p>
	</div>
</div>

<?php
// Render newsletter widget (example)
echo $hub->theme->widget('newsletter');
