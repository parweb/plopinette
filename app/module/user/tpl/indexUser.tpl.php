<? foreach ( $list as $item ) : ?>
	<div class="item">
		<p><?= $item['id'] ?></p>
		<h3><a href="<?= link::href( 'user', 'index', array( 'id' => $item['id'] ) ) ?>"><?= $item['login'] ?></a></h3>
		<p><?= $item['email'] ?></p>
	</div>
<? endforeach; ?>