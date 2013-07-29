<? if ( $pages > 1 ) : ?>
	<? $current_page = (int)url('page') ?>

	<? $prev = ( $current_page - 1 > 0 ) ? $current_page - 1 : 1 ?>
	<? $next = ( $current_page + 1 < $pages ) ? $current_page + 1 : $pages ?>

	<? $URL_REQUEST = preg_replace( '#/page:([0-9]+)/#', '/', URL_REQUEST ); ?>

	<div class="pagination">
		<ul>
			<? if ( $current_page > 1 ) : ?>
				<li class="first"><a href="<?= link::href( $URL_REQUEST ) ?>"><<</a></li>
			<? endif; ?>

			<? if ( $current_page > 2 ) : ?>
				<li class="prev"><a href="<?= link::href( $URL_REQUEST."page:$prev/" ) ?>"><</a></li>
			<? endif; ?>

			<? for ( $i = 1; $i <= $pages; $i++ ) : ?>
				<? $current = '' ?>
				<? if ( $i == $current_page ) : ?>
					<? $current = ' class="current"' ?>
				<? endif; ?>

				<li<?= $current ?>><a href="<?= link::href( $URL_REQUEST."page:$i/" ) ?>"><?= $i ?></a></li>
			<? endfor; ?>

			<? if ( $current_page < $pages - 1 ) : ?>
				<li class="next"><a href="<?= link::href( $URL_REQUEST."page:$next/" ) ?>">></a></li>
			<? endif; ?>

			<? if ( $current_page < $pages ) : ?>
				<li class="last"><a href="<?= link::href( $URL_REQUEST."page:$pages/" ) ?>">>></a></li>
			<? endif; ?>

			<div class="clear"></div>
		</ul>
	</div>

	<div class="clear"></div>
<? endif; ?>