## Types of changes

Added, Changed, Deprecated, Removed, Fixed, Securit

## changelog

### v0.9.4
- fixed, google auth login fixed
- changed, DB column names refactored
- added, login form
- added, custom modal menus

### v0.9.4
- fixed, contact form now submits
- added, contact form has basic validation
- fixed, favicon.ico
- fixed, no-reciepe links to upload
- added, gallery page has lang
- added, gallery page has carousel
- fixed, stylesheet missing .a.img-fluid

### v0.9.3
- added, processing.php, Added error handling with disformed JSON
- added, second reciepe
- added, verbosing taks development
- fixed, checking if vafriable exists in reciepe.php
- fixed, missing globals in db_helpers
- changed, chatgpt completions have changed, updating Dall-E prompt extraction and Reciepe extraction.
- changed, reciepe parsing

### v0.9.2
- added, head meta lang title 
- fixed, disabled auto sliding for reciepe page
- fixed, reversing hide logic for submit button. Making sure it is always available even if script fails
- added, html lang
- added, info to _SESSION notifications
- changed, composer bumbed to PHP 8.2
- changed, made manifest.json a PHP file
- changed, Syncronising PWA version
- added, bonus config on upload page
- added, sessionTools

### v0.9.1
- changed, nb app title corrected. Thanks to Espen Sunde
- added, google login templates
- added, upload image formats expanded with tif, bmp, webp, gif
- added, when image is of something else than fridge we create dalle image for segway
- added, reciepe page has swipe page for ingredients list.
- fixed, $kista_dp prefixing all db_queries
- changed, enabled ChatGPT4 for all queries
- added, language added on frontpage
- added, languagea added on upload
- added, language added on reciepe
- fixed, top image frontpage set with inline style
- added, nocache paramtere for custom.js script include