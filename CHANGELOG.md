## Types of changes

Added, Changed, Deprecated, Removed, Fixed, Securit

## changelog


### v0.11.5
- changed, _vars.php renamed for ../cred.{domain}_vars.php
- changed, NROK tunnel = htdocs
- changed, moving off-canvas elements outside page-content
- added, help section explaining avatarify and ai-jargon
- changed, new Avatarify main logo
- changed, avatarify gallery queries now handles google login sessions
- fixed, when logging in with google the uses session table was not updated

### v0.11.4
- fixed, index had old links to kista-ai.steinhaug.no, now set in _vars.php
- fixed, Google API has updated syntax for oauth login.
- fixed, Google Login redirect URI now set in credentials.

### v0.11.3
- added, new AJAX communication layer added for easy prototyping.
- added, new animation layer added for smooth transitions.
- added, avatarify gallery image controls now have ajax communication with server.
- added, 3 new AJAX command dummy-files setup to handle image controls.

### v0.11.2
- added, avatarify gallery image controls. Added confirmation buttons ready for AJAX.
- added, jQuery added together with custom fuctionality and uglify compression scripts via gulp
- changed, replicate settings for style transfer have been tweaked for better results
- changed, avatarify upload page have been reordered so that first you upload image before selecting style
- changed, avatarify upload page now display styles images instead of results of myself to prevent confusion

### v0.11.1
- added, session create skip for facebook crawler
- changed, gallery page for avatar images has new icons for rotate and download.

### v0.11.0
- added, avatarify concept and icons
- added, avatarify gallery and view
- added, style setting for framework
- added, replicate upload auto scroll to steps

### v0.10.0
- added, extra webfolder for NGROK tunnel and Replicate webhooks for development
- added, POC for inference.
- added, new upload feature for style transfer / avatar mode.
- added, Added abstraction layer for handling with the _SESSION tasks
- changed, replicate will now read as waiting, for webhook. Webhook will download all images and create thumbs.

### v0.9.6
- fixed, Moving action sheets, snackbars and sidebars outside page-content
- added, PWA installer button for the frontpage

### v0.9.5
- changed, added extra icon on top for css
- added, google login
- fixed, added "external-link" for logout and login links
- added, option to include the google login button
- fixed, moved modal parts to correct place in framework
- changed, adding cache prevention for manifest
- fixed, footer title was styled wrong

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