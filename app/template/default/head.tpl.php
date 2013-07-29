<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# video: http://ogp.me/ns/video#">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title><?= $title ?>, film streaming live gratuit entier et complet</title>

	<meta name="description" content="<?= @str_replace( '"', "'", $description ) ?>" />

	<? if ( url('module') == 'video' && url('action') == 'infos' ) : ?>
		<meta property="fb:app_id"      content="200606866671822" /> 
		<meta property="og:type"        content="video.movie" /> 
		<meta property="og:url"         content="http://www.dailymatons.com<?= URI ?>" /> 
		<meta property="og:title"       content="<?= $title ?>" /> 
		<meta property="og:image"       content="http://www.dailymatons.com<?= $image ?>" /> 
		<meta property="og:description" content="<?= str_replace( '"', '\\"', $description ) ?>" />
	<? endif; ?>

	<script type="text/javascript">
		var URL = '<?= URL ?>';
	</script>

	<? if ( config( 'site.env' ) == 'local' ) : ?>
		<? foreach ( css::get() as $item ) : ?>
			<link type="text/css" rel="stylesheet" href="<?= URL.APP.TEMPLATE.config('view.template').DS.'css'.DS.$item.'.css' ?>" />
		<? endforeach; ?>

		<? foreach ( js::get() as $item ) : ?>
			<script type="text/javascript" src="<?= URL.APP.TEMPLATE.config('view.template').DS.'js'.DS.$item.'.js' ?>"></script>
		<? endforeach; ?>
	<? else : ?>
		<link type="text/css" rel="stylesheet" href="<?= css::minify() ?>" />
		<script type="text/javascript" src="<?= js::minify() ?>"></script>
	<? endif; ?>
</head>