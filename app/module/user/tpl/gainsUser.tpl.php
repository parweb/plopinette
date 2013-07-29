<h2>Vous avez accumulé <strong><?= user::gains( user::id() ) ?>€</strong></h2>

<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="http://cdn.oesmith.co.uk/morris-0.4.1.min.js"></script>

<link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.1.min.css">

<div id="chart" style="height: 350px;"></div>

<script>
	$(document).ready(function(){
		new Morris.Line({
			element: 'chart',

			data: [
				<?= user::gains_json( user::id() ) ?>
			],

			xkey: 'year',
			ykeys: ['value'],

			labels: ['euro']
		});
	});
</script>