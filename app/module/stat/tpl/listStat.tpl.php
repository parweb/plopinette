<ul>
	<li><a href="#">Videos</a></li>
	<li><a href="#">Users</a></li>
</ul>

<h2>Graphe sur les utilisateurs</h2>

<div style="width: <?= str_replace( ',', '.', $graph['w'] ) ?>px; height: <?= str_replace( ',', '.', $graph['h'] ) ?>px;" class="graph user">
	<? $cumul = 0 ?>

	<? foreach ( $users as $user ) : ?>
		<? $cumul += $user['count'] ?>
		<? $_cumul = $cumul / $max['user'] * $graph['h']; ?>
		<? $_more = $user['count'] / $max['count'] * $graph['h']; ?>

		<div>
			<span style="height: <?= str_replace( ',', '.', $_more ) ?>px;" class="more"></span>
			<span style="height: <?= str_replace( ',', '.', $_cumul ) ?>px;" class="cumul"></span>

			<div class="clear"></div>
		</div>
	<? endforeach; ?>

	<div class="clear"></div>
</div>
<div class="clear"></div>

<style>
	.clear {
		clear: both;
	}

	.graph {
		display: block;
		position: relative;
		width: 100%;
	}

	.graph div {
		float: left;
		display: block;
		width: <?= str_replace( ',', '.', 100 / $max['period'] ) ?>%;
		position: relative;
		bottom: 0;
		min-height: 1px;
	}

	.graph div span {
		/*position: absolute;
		bottom: 0;*/
		display: block;
		width: 50%;
		float: left;
		background: #ddd;
	}

	.graph div span.more {
		background: #aaa;
	}
</style>