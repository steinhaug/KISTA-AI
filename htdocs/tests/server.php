<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(__FILE__)) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(__FILE__)) . '/uploaded_files');

require_once dirname(APPDATA_PATH) . '/func.inc.php';
require_once dirname(APPDATA_PATH) . '/func.login.php';


#var_dump($_SERVER);
/*
$chatgpt_result3 = 'This piece of art erupts with a phantasmagorical showcase of opposites entwined in a fierce ballet. Here, the wild ferocity of an anthropomorphic wolf, muscles rippling like molten hills under a moonlit sky, stands in stark contrast to the delicate grace of a woman who clings to the beast with an enigmatic intimacy. Her eyes, pools of liquid vulnerability, glisten with a tempest of emotions, mirroring the inner turmoil of being caught in a world ablaze. The wolf\'s snarl, a radiant crescendo of primal wrath, ripples through the air, threatening to tear the very fabric of reality asunder with its savage intensity.\n\nThe backdrop hints at an inferno that dances wildly, casting a hellish glow upon a crumbling realm, an unsettling theater for this surreal communion. Charcoal smudges and crimson embers frame the duo, adding an apocalyptic aura to their strange, otherworldly embrace. It\'s as if the canvas itself throbs with life, the brush strokes infused with arcane energy, weaving a tapestry of chaos and beauty, danger and desire, the wild and the tame, locked in an eternal waltz amidst the ruins of a once serene landscape.';
$chatgpt_result4 = promptChatGPT4('Come up with 4 different Dall-E prompts, make sure they all are made with psychedelic and vivid colors. Use the following text as your source:' . "\n\TEXT - - -\n" . $chatgpt_result3);
echo $chatgpt_result4;
*/


