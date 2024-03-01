<img src="https://api.visitorbadge.io/api/combined?path=https%3A%2F%2Fcolab.research.google.com%2Fgithub%2Fsteinhaug%2FKISTAAI.md&label=hitcount&countColor=%23263759&style=flat"> <a href="https://github.com/steinhaug/KISTA-AI" target="_blank"><img alt="Open Github profile" src="https://img.shields.io/badge/KISTA--AI-Repo-blue?logo=github"></a> <a href="https://github.com/steinhaug/" target="_blank"><img alt="Open Github profile" src="https://img.shields.io/badge/Steinhaug-Profile-black?logo=github"></a>

# KISTA-AI

A simple APP that utilizes the AI offered via API from OpenAPI in a quick and dirty way, hopefully showcasing some of the possibilities.

## The idea

Snap a photo of your fridge and let the AI come up with some dinner suggestions presented as sexy as possible.

## Framework

Website is built using PHP.

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

## Local developer server

[http://kista-ai.local/](http://kista-ai.local/)

## Credits

steinhaug@gmail.com 
github.com/steinhaug

Less is more - more or less...
