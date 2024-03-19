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
    $js_snippet .= <<<'EOD'

// To clear cache on devices, always increase APP_VER number after making changes.
// The app will serve fresh content right away or after 2-3 refreshes (open / close)
var CACHE_NAME = APP_NAME + '-' + APP_VER;

// Files required to make this app work offline.
// Add all files you want to view offline below.
// Leave REQUIRED_FILES = [] to disable offline.
var REQUIRED_FILES = [
	// Styles
	'styles/style.css',
	'styles/bootstrap.css',
	// Scripts
	'scripts/custom.js',
	'scripts/bootstrap.min.js',
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
	'images/empty.png',
	'images/pictures/refrigerator-700-en.png',
	'images/pictures/refrigerator-700-nb.png',
];

// Service Worker Diagnostic. Set true to get console logs.
var APP_DIAG = false;

//Service Worker Function Below.
self.addEventListener('install', function(event) {
	event.waitUntil(
		caches.open(CACHE_NAME)
		.then(function(cache) {
			//Adding files to cache
			return cache.addAll(REQUIRED_FILES);
		}).catch(function(error) {
			//Output error if file locations are incorrect
			if(APP_DIAG){console.log('Service Worker Cache: Error Check REQUIRED_FILES array in _service-worker.js - files are missing or path to files is incorrectly written -  ' + error);}
		})
		.then(function() {
			//Install SW if everything is ok
			return self.skipWaiting();
		})
		.then(function(){
			if(APP_DIAG){console.log('Service Worker: Cache is OK');}
		})
	);
	if(APP_DIAG){console.log('Service Worker: Installed');}
});

self.addEventListener('fetch', function(event) {
	event.respondWith(
		//Fetch Data from cache if offline
		caches.match(event.request)
			.then(function(response) {
				if (response) {return response;}
				return fetch(event.request);
			}
		)
	);
	if(APP_DIAG){console.log('Service Worker: Fetching '+APP_NAME+'-'+APP_VER+' files from Cache');}
});

self.addEventListener('activate', function(event) {
	event.waitUntil(self.clients.claim());
	event.waitUntil(
		//Check cache number, clear all assets and re-add if cache number changed
		caches.keys().then(cacheNames => {
			return Promise.all(
				cacheNames
					.filter(cacheName => (cacheName.startsWith(APP_NAME + "-")))
					.filter(cacheName => (cacheName !== CACHE_NAME))
					.map(cacheName => caches.delete(cacheName))
			);
		})
	);
	if(APP_DIAG){console.log('Service Worker: Activated')}
});

EOD;
    echo $swlib->minify_js($js_snippet);

$swlib->end_ob();