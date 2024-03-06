<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(dirname(__FILE__))) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))) . '/uploaded_files');
require dirname(APPDATA_PATH) . '/func.inc.php';
require dirname(APPDATA_PATH) . '/func.login.php';


$string = 'Recipe: Oatmeal Souffle with Raisin Compote

Calories: Approximately 400 calories

Origin: France

Preparation Time: 45 minutes

Ingredients:
- 2 eggs
- 50g Quaker Oats
- 30g raisins
- 1 tbsp sugar
- 1/4 tsp cinnamon
- 1/4 cup water
- 1 tbsp butter
- Pinch of salt

Optional Ingredients:
- Vanilla extract
- Lemon zest

Instructions:
1. Preheat the oven to 180°C.
2. Separate the egg yolks and whites into two bowls.
3. In a blender, blend the Quaker Oats until finely ground.
4. In a saucepan, combine the ground oats, water, sugar, cinnamon, butter, and salt. Cook over low heat until thickened, stirring constantly.
5. Remove from heat and let it cool slightly.
6. Beat the egg yolks and gradually mix them into the oat mixture.
7. In a separate bowl, beat the egg whites until stiff peaks form.
8. Gently fold the egg whites into the oat mixture until well combined.
9. Pour the mixture into a greased ramekin and bake in the preheated oven for 20-25 minutes or until puffed and golden.
10. In the meantime, prepare the raisin compote by simmering the raisins with a little water and sugar until soft and syrupy.
11. Serve the oatmeal souffle with the raisin compote on top.
12. Enjoy your delicious and healthy gourmet meal! Bon appétit!';

$url = '/uploaded_files/00008-00002-1.png';
$thumb_url = '/uploaded_files/_thumbs/00008-00002-1.png';

//678
echo 'UserID: ' . $USER_ID . '<br>';
$item = $mysqli->query1("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE upload_id=" . 8);

openai__generateReciepe($item['reciepe'], $item['reciepe_image']);
#var_dump($parts);
