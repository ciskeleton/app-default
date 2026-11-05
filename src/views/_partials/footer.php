<footer class="footer" role="contentinfo">
	<div class="container">
		<div class="row justify-content-center<?= $hub->menus->has('footer') ? ' justify-content-md-between': '' ?> gy-2">

			<div class="col-md-auto">
				<div class="d-flex gap-2 justify-content-center">
					<span>&copy; 2018-<?= date('Y', CI_REQUEST_TIME) ?> <?= anchor('', $site_name) ?></span>

					<span class="border-start border-secondary-subtle ps-2 d-none d-sm-block">
						<?= line('all_rights_reserved') ?>
					</span>

					<span class="border-start border-secondary-subtle ps-2 d-none d-md-block">
						<?= sline('powered_by', anchor(Platform::AUTHOR_URL, line('platform_name'), [
							'target' => '_blank',
							'rel' => 'noopener',
						])) ?>
					</span>
				</div><!--/.d-flex-->

			</div><!--/.col-->

			<?= $hub->menus->render('footer', [
				'container' => 'div',
				'container_attrs' => ['class' => 'col'],
				'menu_attrs' => ['class' => 'nav'],
				'item_attrs' => ['class' => 'nav-item'],
				'link_attrs' => ['class' => 'nav-link'],
			]) ?>

		</div><!--/.row-->
	</div><!--/.container-->
</footer>
