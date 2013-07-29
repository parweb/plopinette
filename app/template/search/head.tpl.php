<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# video: http://ogp.me/ns/video#">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title><?= $title ?>, film streaming</title>

	<meta name="description" content="<?= str_replace( '"', "'", $description ) ?>" />

	<? if ( url('module') == 'video' && url('action') == 'view' ) : ?>
		<meta property="fb:app_id"      content="200606866671822" /> 
		<meta property="og:type"        content="video.movie" /> 
		<meta property="og:url"         content="http://www.dailymatons.com/<?= URL_REQUEST ?>" /> 
		<meta property="og:title"       content="<?= $title ?>" /> 
		<meta property="og:image"       content="<?= $image ?>g" /> 
		<meta property="og:description" content="<?= str_replace( '"', "'", $description ) ?>" />
	<? endif; ?>

	<!--<link type="text/css" rel="stylesheet" href="<?= css::minify('plop') ?>" />-->
	<? foreach ( array( 'reset', 'zoombox', 'print', 'style' ) as $item ) : ?>
		<link type="text/css" rel="stylesheet" href="<?= URL.APP.TEMPLATE.config('view.template').DS.'css'.DS.$item.'.css' ?>" />
	<? endforeach; ?>

	<script type="text/javascript">
		var URL = '<?= URL ?>';
	</script>

	<!--<script type="text/javascript" src="<?= js::minify() ?>"></script>-->
	<? foreach ( array( 'jquery', 'jquery.tinyscrollbar.min', 'video', 'facebook', 'zoombox' ) as $item ) : ?>
		<script type="text/javascript" src="<?= URL.APP.TEMPLATE.config('view.template').DS.'js'.DS.$item.'.js' ?>"></script>
	<? endforeach; ?>

	<link rel="alternate" type="application/rss+xml" title="fftwirling.fr RSS Feed" href="<?= link::href( 'actu/rss' ) ?>" /> 
</head>