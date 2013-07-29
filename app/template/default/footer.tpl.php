<div id="footer-top"></div>

<div id="footer-middle">
	<div class="align-left">
		<!--<h4><a href="<?= link::href( 'sitemap', 'view' ) ?>">Sitemap ></a><span><a href="<?= link::href() ?>">Accueil</a></span></h4>-->
		<!--<p><a href="<?= link::href( 'video' )?>">Videos</a> | <a href="<?= link::href( 'artiste' ) ?>">Artistes</a></p>-->
	</div>

	<? global $time_start; ?>
	<? $load = microtime( true ) - $time_start; ?>

	<div class="align-middle">
		<p>Temps de réponse <?=  $load ?> secondes</p>
	</div>

	<div class="align-right">

	</div>
	<div class="clear"></div>
</div>