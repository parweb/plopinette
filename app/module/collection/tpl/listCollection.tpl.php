<? debug($list) ?>

<? if ( count( $list ) ) : ?>
	<div id="list_collection">
		<? foreach ( $list as $item ) : ?>
			<div class="collection">
				<a href="<?= link::href( "collection/view/id:$item->id" ) ?>">
					<img src="<?= img::get( 'video', $item->image, 174, 228 ) ?>" />
					<div class="overlay"></div>
				</a>
				<div class="title"><a href="<?= link::href( "collection/view/id:$item->id" ) ?>"><?= $item->title ?></a></div>
			</div>
		<? endforeach; ?>

		<div class="clear"></div>
	</div>

	<div class="clear"></div>

	<? include $this->layout( 'pagination' ); ?>
<? endif; ?>