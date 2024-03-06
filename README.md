<img src="https://api.visitorbadge.io/api/combined?path=https%3A%2F%2Fcolab.research.google.com%2Fgithub%2Fsteinhaug%2FKISTAAI.md&label=hitcount&countColor=%23263759&style=flat"> <a href="https://github.com/steinhaug/KISTA-AI" target="_blank"><img alt="Open Github profile" src="https://img.shields.io/badge/KISTA--AI-Repo-blue?logo=github"></a> <a href="https://github.com/steinhaug/" target="_blank"><img alt="Open Github profile" src="https://img.shields.io/badge/Steinhaug-Profile-black?logo=github"></a>

# KISTA-AI

A simple APP that utilizes the AI offered via API from OpenAPI in a quick and dirty way, hopefully showcasing some of the possibilities.

## The idea

Snap a photo of your fridge and let the AI come up with some dinner suggestions presented as sexy as possible.

## Framework

Website is built using PHP (LAMP Stack), and we will be using the openai-php library for communicating with the API.

## Setup

Get your OPENAI_API_KEY and create a file .openai_api_key with contents:

    YOUR_API_KEY

Create a file credentials.php

    <?php

    $open_ai_key = 'YOUR_API_KEY';

## File structure

- python
  - python scripts for communicating with the openAI API to acomplish the tasks needed to achieve The idea.
- htdocs
  - Website for the application / app built using LAMP stack.
- docs
- misc markdown files appearing during development
- assets
  - images, css and possible external js files needed for the website
- src

## Roadmap / milestones overview

Itallic is completed.

_v0.5.0 - Proof of concept, make sure all the required tasks needed to complete the app are solved._  
_v0.6.0 - Create a roadmap with milestones for an overview on whats left to do._  
_v0.7.0 - Design the website for the app, the site will have an upload feature and a gallery of previous uploads from other users. Design a super simple spinner mode for use while upload is being processed. Complete mockup._  
_v0.8.0 - Complete the upload part of the app, and the display of the reciepe._  
v0.8.1 - Setup database and connect it to the website, make sure all images are saved and cached ready for a possible gallery mode.  
v0.8.2 - Initial pre-release of the KISTA AI app with all required functionality needed to deliver the AI experience.  
v0.9.0 - Complete the gallery mode of the app.  
v0.9.1 - Increase the amount of reciepes generated from 1 to 3 for each upload.  
v0.9.2 - Redo the spinner page with fancier graphics.  
v0.9.3 - Analyze logs and user creations, goal is to improve vision, completion and imagination. Descide what metrics we will focus on for theese goals.  
v0.9.4 - Release candidate 1  
v0.9.5 - Do an iteration over the webdesign, business logic for cache and DB.  
v1.0.0 - Official release!  

## Local developer server

Minimum PHP version 8.1  

[http://kista-ai.local/](http://kista-ai.local/)

## Food for thought

Less is more - more or less...  
Hvis du to'an, kan du bare ta'an, du vant, ikke, jeg heller!  
Før det braker løs, må vi være muse stille.  
Up or down, actually, there is a difference.  

## Credits

steinhaug@gmail.com 
github.com/steinhaug


