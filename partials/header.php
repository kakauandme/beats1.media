<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />



<title itemprop="name"><?php echo $title; ?></title>
<?php /*SEO */ ?>
<meta name="description" content="<?php echo $description; ?>" itemprop="description" />

<?php /*browsers */ ?>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

<?php /* iOS meta */?>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="<?php echo $siteName; ?>">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<?php /*iOS */ ?>
<link rel="apple-touch-icon" sizes="57x57" href="/img/config/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/img/config/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/img/config/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/img/config/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/img/config/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/img/config/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/img/config/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/img/config/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/img/config/apple-touch-icon-180x180.png">
<link rel="mask-icon" href="/img/config/logo.svg" color="black">

<?php /*Chrome & Android */ ?>
<link rel="manifest" href="/manifest.json">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="<?php echo $siteName; ?>">
<meta name="theme-color" content="#ffffff">
<link rel="icon" type="image/png" href="/img/config/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="/img/config/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="/img/config/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="/img/config/favicon-16x16.png" sizes="16x16">

<?php /* Windows */ ?>
<meta name="msapplication-config" content="/browserconfig.xml">
<meta name="msapplication-TileColor" content="#000000">
<meta name="msapplication-TileImage" content="/img/config/mstile-144x144.png">

<?php /*Twitter cards */ ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:creator" content="@kakauandme">
<meta name="twitter:title" content="<?php echo $title; ?>">
<meta name="twitter:description" content="<?php echo $description; ?>">
<meta name="twitter:image:src" content="<?php echo $baseURL; ?>/img/config/z">
<?php /*Facebook OG */ ?>
<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:site_name" content="<?php echo $siteName; ?>"/>
<meta property="og:url" content="<?php echo $baseURL; ?>" />
<meta property="og:description" content="<?php echo $description; ?>" />
<meta property="fb:app_id" content="1166902519993355" />
<meta property="og:type" content="website" />
<meta property="og:image" content="<?php echo $baseURL; ?>/img/config/screenshot.jpg" />
<?php /*Google+ Schema.org microdata */ ?>
<meta itemprop="image" content="<?php echo $baseURL; ?>/img/config/screenshot.jpg">