<? if ( url('module') == 'video' &&  url('action') == 'list' && 0 ) : ?>
	<div id="features">
		<? foreach ( $features as $item ) : ?>
			<div class="video">
				<? $watched = video::watched( $item->id )  ?>

				<a class="<?= $watched ?>" data-title="<?= $item->title ?> (<?= current( explode( '-', $item->release ) ) ?>)" href="<?= link::href( "video/infos/id:$item->id" ) ?>">
					<img src="<?= img::get( 'video', $item->image, 174, 228 ) ?>" />
					<div class="overlay"></div>
				</a>
				<div class="title"><a href="<?= link::href( "video/view/id:$item->id" ) ?>"><?= $item->title ?></a></div>

				<? if ( user::is_login() && 0 ) : ?>
					<div class="admin">
						<a href="<?= link::href( 'buy', 'add', array( 'video_id' => $item->id ) ) ?>">Acheter ( <?= count( $item->buy ) ?> )</a>
						<a href="<?= link::href( 'bet', 'add', array( 'video_id' => $item->id ) ) ?>">Parier ( <?= count( $item->bet ) ?> )</a>
					</div>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>

<? if ( count( $list ) ) : ?>
	<div id="list_video">
		<? $last_letter = ''; ?>

		<? foreach ( $list as $item ) : ?>
			<? $is_search = url( ':title', false ); ?>

			<? /* if ( $item->title[0] != $last_letter && !$is_search ) : $last_letter = strtoupper( $item->title[0] ) ?>
				<p class="clear"><?= $last_letter ?></p>
			<? endif; */ ?>

			<div class="video">
				<? $watched = video::watched( $item->id )  ?>

				<a class="<?= $watched ?>" data-title="<?= $item->title ?> (<?= current( explode( '-', $item->release ) ) ?>)" href="<?= link::href( "video/infos/id:$item->id" ) ?>">
					<img src="<?= img::get( 'video', $item->image, 174, 228 ) ?>" />
					<div class="overlay"></div>
				</a>
				<div class="title"><a href="<?= link::href( "video/view/id:$item->id" ) ?>"><?= $item->title ?></a></div>

				<? if ( user::is_login() && 0 ) : ?>
					<div class="admin">
						<a href="<?= link::href( 'buy', 'add', array( 'video_id' => $item->id ) ) ?>">Acheter ( <?= count( $item->buy ) ?> )</a>
						<a href="<?= link::href( 'bet', 'add', array( 'video_id' => $item->id ) ) ?>">Parier ( <?= count( $item->bet ) ?> )</a>
					</div>
				<? endif; ?>
			</div>
		<? endforeach; ?>

		<div class="clear"></div>
	</div>

	<div class="clear"></div>

	<? include $this->layout( 'pagination' ); ?>
<? else : ?>
	<p>Aucun résultat</p>
<? endif; ?>