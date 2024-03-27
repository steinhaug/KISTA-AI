<?php
define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';

$uploadpath = './uploaded_files/';
$uploadCACHE = '_cache/';

$swlib = new steinhaug_libs;
$swlib->set_type('text/javascript');
$swlib->set_cachedir($uploadpath . $uploadCACHE);
$swlib->optimize_output = false;
$swlib->start_ob(false,false);

    $js_snippet  = "var APP_NAME   = '" . $PWA_APP_NAME . "';                     " . "\n";
    $js_snippet .= "var APP_VER    = '" . $PWA_APP_VER . "';        " . "\n";
    $js_snippet .= "const version    = '" . $PWA_APP_VER . "';        " . "\n";
    $js_snippet .= <<<'EOD'

const cacheName = `cache-${version}`;
const versionCacheName = "cache-v";

// To clear cache on devices, always increase APP_VER number after making changes.
// The app will serve fresh content right away or after 2-3 refreshes (open / close)
//var CACHE_NAME = APP_NAME + '-' + APP_VER;

// Leave REQUIRED_FILES = [] to disable offline.
var REQUIRED_FILES = [

    // pages
    'menu-footer-avatar-nb.html',
    'menu-footer-avatar-en.html',
    'menu-footer-en.html',
    'menu-footer-nb.html',
    'menu-colors.html',
    'menu-main.html',
    'menu-share.html',

    'docs.php?doc=avatarify-intro',
    'docs.php?doc=generative-ai-intro',
    'docs.php?doc=instantid-intro',
    'docs.php?doc=what-is-ip-adapter',
    'docs.php?doc=what-is-identitynet',
    'docs.php?doc=generative-ai-terminology',
    'docs/figs/style-transfer-01_thumb.jpg',
    'docs/figs/style-transfer-02_thumb.jpg',
    'docs/figs/style-transfer-03_thumb.jpg',
    'docs/figs/style-transfer-01.jpg',
    'docs/figs/style-transfer-02.jpg',
    'docs/figs/style-transfer-03.jpg',
    'docs/figs/missing-legs-01.jpg',
    'docs/figs/missing-legs-01_thumb.jpg',
    'docs/figs/extra-legs-02_thumb.jpg',
    'docs/figs/extra-legs-02.jpg',
    'docs/figs/extra-arms-01_thumb.jpg',
    'docs/figs/extra-arms-01.jpg',

	// Styles
	'styles/style.css',
	'styles/bootstrap.css',

	// Scripts
	'scripts/custom.js',
	'scripts/bootstrap.min.js',
    'scripts/jquery-3.7.1.min.js',
    'scripts/gallery-controller.js',

	// Plugins
	'plugins/charts/charts.js',
	'plugins/charts/charts-call-graphs.js',
	'plugins/countdown/countdown.js',
	'plugins/filterizr/filterizr.js',
	'plugins/filterizr/filterizr.css',
	'plugins/filterizr/filterizr-call.js',
	'plugins/galleryViews/gallery-views.js',
	'plugins/glightbox/glightbox.js',
	'plugins/glightbox/glightbox.css',
	'plugins/glightbox/glightbox-call.js',

	// Fonts
	'fonts/css/fontawesome-all.min.css',
	'fonts/webfonts/fa-brands-400.woff2',
	'fonts/webfonts/fa-regular-400.woff2',
	'fonts/webfonts/fa-solid-900.woff2',

	// Images
    'app/icons/google-logo.svg',
    'images/avatars/person.png',
	'images/empty.png',
	'images/pictures/refrigerator-700-en.png',
	'images/pictures/refrigerator-700-nb.png',
];

// Service Worker Diagnostic. Set true to get console logs.
var APP_DIAG = true;

//Service Worker Function Below.
self.addEventListener('install', function(event) {
	event.waitUntil(
		caches.open(cacheName)
		.then(function(cache) {
			return cache.addAll(REQUIRED_FILES);
		}).catch(function(error) {
			if(APP_DIAG){console.log('Service Worker Cache: Error Check REQUIRED_FILES array in _service-worker.js - ' + error);}
		})
		.then(function() {
			return self.skipWaiting();
		})
		.then(function(){
			if(APP_DIAG){console.log('Service Worker: Cache is OK');}
		})
	);
	if(APP_DIAG){console.log('Service Worker: Installed');}
});



self.addEventListener('fetch', function(event) {

	event.respondWith(networkRevalidateAndCache(event));
	// event.respondWith(networkOnly(event));
	if(APP_DIAG){console.log('Service Worker: Fetching '+APP_NAME+'-'+APP_VER+' files from Cache');}
});

async function networkRevalidateAndCache(self) {
  try {
    const fetchResponse = await fetch(self.request);

    if (!fetchResponse || fetchResponse.status !== 200 || fetchResponse.type !== 'basic') {
        console.log('Harry Klein ist politzei');
        return fetchResponse;
    }

    if (fetchResponse.ok) {
      const cache = await caches.open(cacheName);
      await cache.put(self.request, fetchResponse.clone());
      return fetchResponse;
    } else {
      const cacheResponse = await caches.match(self.request);
      return cacheResponse;
    }
  } catch (err) {
    console.log("Could not return cache or fetch NF", err);
  }
}

function networkOnly(self) {
  return fetch(self.request);
}





self.addEventListener('activate', function(event) {
	event.waitUntil(self.clients.claim());
	event.waitUntil(
		//Check cache number, clear all assets and re-add if cache number changed
		caches.keys().then(cacheNames => {
			return Promise.all(
				cacheNames
					//.filter(cacheName => (cacheName.startsWith(APP_NAME + "-")))
					//.filter(cacheName => (cacheName !== cacheName))
					.map(cacheName => caches.delete(cacheName))
			);
		})
	);
	if(APP_DIAG){console.log('Service Worker: Activated')}
});

EOD;
    echo $swlib->minify_js($js_snippet);

$swlib->end_ob();