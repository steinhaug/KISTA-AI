<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(__FILE__)) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(__FILE__)) . '/uploaded_files');

require_once dirname(APPDATA_PATH) . '/func.inc.php';

$q = 'Look at the norwegian photo of a norwegian refrigerator. Your job is to detect as many different groceries / seperate items currently inside of the refrigerator as possible. If you find more items of the same type try to estimate pcs / quanta and return your findings as a numerated item list with the item\'s quantities.';
$string1 = 'Based on the image provided, here\'s a numerated list of the different grocery items visible in the refrigerator, including estimated quantities where applicable:

1. Bottles in the bottle holder on the top (hard to identify the exact content):
   - Pepsi bottle: 1
   - Yellow labeled bottle (possibly a soda or juice): 1
   - Black labeled bottle (possibly a soda or alcoholic beverage): 1
   - Blue labeled bottle (possibly a soda or sports drink): 1

2. Tine Lettmelk (reduced-fat milk): 1 carton

3. Tine sour cream or a similar dairy product in a green container: 1

4. A clear container with a white lid, possibly holding leftovers or a prepared meal: 1

5. Kavli spreadable cheese in a tube: 1

6. Yellow labeled container on the middle shelf, perhaps margarine or butter: 1

7. Gilde - looks like packaged sliced meat (possibly ham or salami): estimated 2-3 packages

8. Jar with a gold-colored lid, could be jam or pickles: 1

9. Red packages on the bottom shelf, possibly cheese or another type of sliced meat: estimated 2 packages

10. Blue carton on the bottom shelf, potentially another dairy product like milk or juice: 1

11. Clear plastic container on the bottom, details not visible: 1

Please note that some items\' specific details are unclear due to the angle and resolution of the photo. This list is based on visible packaging and estimations.';
$string2 = 'Alright, I’ll list the visible items in the Norwegian refrigerator:

1. Soda bottles in the bottle holder (top shelf):
   - Pepsi Max: 1
   - FUZE Tea: 1
   - Solo: 1
   - Probably a Coca-Cola product due to the distinct red cap and partial logo: 1

2. Dairy (middle shelf):
   - TINE Lettmelk (low-fat milk): 1 carton
   - TINE Lettrømme (sour cream): 1 container

3. Various spreads and dips:
   - A tub of spreadable butter or margarine (yellow lid): 1
   - Kavli cheese spread in a tube: 1

4. Preserved goods:
   - Jar of what could be honey, jam, or pickles (with a gold lid): 1

5. Packaged foods:
   - A transparent container with a white lid, likely storing leftovers or a pre-prepared dish: 1
   - GILDE (brand) products, looks like at least two packets of sliced meats: 2
   - Red packaging which might be more sliced meats or cheese: 2 packets visible, possibly more underneath

6. Bottom shelf:
   - Blue carton, could be another dairy product like milk or juice: 1
   - Transparent storage container, content unclear: 1

7. Inside the door (not fully visible):
   - There appears to be something in the door shelves, but it’s too obscured to accurately identify.

These are the items I could discern. The specific quantity of some items may not be accurate if there are multiples stacked or hidden behind what we can see.';

$string3 = 'Here\'s the numerated list of ingredients mentioned in the text:

1. Pepsi Max - 1
2. FUZE Tea - 1
3. Solo - 1
4. Probably a Coca-Cola product (distinct red cap and partial logo) - 1
5. TINE Lettmelk (low-fat milk) - 1 carton
6. TINE Lettrømme (sour cream) - 1 container
7. Tub of spreadable butter or margarine (yellow lid) - 1
8. Kavli cheese spread in a tube - 1
9. Jar of honey, jam, or pickles (with a gold lid) - 1
10. Transparent container with white lid (likely storing leftovers or a pre-prepared dish) - 1
11. GILDE sliced meats (at least two packets) - 2
12. Red packaging (possibly more sliced meats or cheese) - 2 packets visible, possibly more underneath
13. Blue carton (potentially another dairy product like milk or juice) - 1
14. Transparent storage container, content unclear - 1

Please note that the quantity of some items may not be accurate due to potential stacking or items hidden from view.';
$ingedients = openai__parse_vision_completion($string3);

echo "
<div style='display: flex;'>
    <div style='width:50%;'><pre style='white-space: pre-wrap;'>{$string3}</pre></div>
    <div style='width:50%;'><pre>{$ingedients}</pre></div>
</div>
";
