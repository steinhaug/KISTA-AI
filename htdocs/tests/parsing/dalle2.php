<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(dirname(__FILE__))) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))) . '/uploaded_files');
require dirname(APPDATA_PATH) . '/func.inc.php';
require dirname(APPDATA_PATH) . '/func.login.php';

$string2 = 'Here are four different prompt ideas for showcasing the Norwegian Open-Faced Sandwich (Smørbrød) through food photography:

1. A plate of **Norwegian Open-Faced Sandwich (Smørbrød)** elegantly arranged on a rustic wooden board. Surround the sandwich with delicate slices of cucumber and tomato, accentuating the freshness of the ingredients. Let the soft natural light illuminate the scene, highlighting the creamy texture of the TINE Lettrømme and the richness of the GILDE sliced meat. Ensure the composition follows the rule of thirds, with the sandwich placed on an intersecting point, drawing the viewer\'s eye to its delectable layers.

2. A plate of **Norwegian Open-Faced Sandwich (Smørbrød)** presented on a pristine white porcelain platter, evoking a sense of sophistication and refinement. Decorate the sandwich with meticulously arranged fresh dill and parsley, enhancing its visual appeal. Incorporate leading lines by placing elegant silverware strategically around the plate, guiding the viewer\'s gaze towards the focal point—the mouthwatering combination of tender GILDE meat and creamy TINE Lettrømme.

3. A bowl of **Norwegian Open-Faced Sandwich (Smørbrød)** served on a polished marble countertop, juxtaposing the sandwich\'s rustic charm with a touch of luxury. Surround the bowl with vibrant, colorful vegetables, such as crisp cucumber slices and juicy tomato wedges, adding a burst of freshness to the scene. Pay close attention to the details, capturing the subtle nuances of the drizzled TINE Lettrømme and the delicate crumb of the rye bread. Invite the viewer to indulge in the sensory experience of this classic Norwegian delicacy.

4. A plate of **Norwegian Open-Faced Sandwich (Smørbrød)** displayed on a sleek slate platter, exuding modern elegance. Enhance the visual appeal of the sandwich by delicately arranging pickles from the jar as a side garnish, providing a refreshing contrast to the rich flavors of the dish. Pay meticulous attention to the styling of the shot, ensuring each element, from the thinly spread butter to the creamy Kavli cheese spread, is showcased in exquisite detail. Let the composition draw the viewer in, enticing them to savor every bite of this irresistible culinary creation.

These prompt ideas aim to capture the essence of the Norwegian Open-Faced Sandwich (Smørbrød) through luxurious food photography, enticing the viewer to indulge in its delectable flavors and textures.';


$dalle_prompts = openai__extract_the_prompts($string2);

var_dump($dalle_prompts);
echo '<pre>';
echo json_encode($dalle_prompts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
echo '</pre>';
