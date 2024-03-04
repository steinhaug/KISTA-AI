<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
require_once APPDATA_PATH . '/openai_helpers.php';


$string = "Certainly, here's a list of visible items and estimated quantities based on what can be seen in the image of the refrigerator:\n\n1. Jars of pickles and condiments on the top shelf\n - Raspberry flavored product (partially visible)\n - Old El Paso Salsa x 1\n - Pickled Gherkins x 1\n - Mustard (brand partially visible, possibly Maille) x 1\n - Other jarred condiment or preserve (label not visible) x 1\n\n2. Bottles (likely alcoholic beverages, possibly wine or champagne) on the middle shelf wire rack\n - Bottles with gold foil top x 3\n - Additional bottle, cap visible (unable to determine contents or quantity) x 1\n\n3. Other items on the middle shelf\n - Plastic-wrapped item (potentially cheese or meat) x 1\n - Cheese in blue packaging (star visible, possibly European Union symbol) x 1\n - Sweetcorn in water (can) x 1\n - Container with orange lid (possibly a spread or dip) x 1\n - Jar with green lid (contents not visible) x 1\n - Clear container with a brown spread or sauce x 1\n - Multi-colored carton or box (label not visible) x 1\n - Green container with yellow label (contents not visible) x 1\n - Yellow cylindrical container (possibly a drink or stock pot) x 1\n\n4. Lower shelf items\n - Plastic container with a blue lid (possibly margarine or spread) labeled \"Pure\" x 1\n - Lurpak butter in rectangular container x 1\n - Various cheese in plastic bags or wraps (unquantifiable due to overlap)\n\nIt is important to note that the quantity is estimated on what can be seen and some items may be partially obscured or hidden behind other items, thus actual quantities may vary.";
$string = "Based on the image provided, here's a list of different groceries and items in the refrigerator, along with estimated quantities where applicable:

1. Old El Paso Thick 'n' Chunky Salsa - 1 jar
2. Pickled Gherkins by Sainsbury's - 1 jar
3. Maille Mustard - 1 jar
4. Raspberry Conserve - 1 jar (partially obscured)
5. Bottles of champagne or sparkling wine - 3 bottles
6. Sweetcorn in water - 1 can
7. Greek-style yogurt or similar - 1 container
8. Lurpak spreadable butter - 1 tub
9. Cheese - at least 2 types (possibly Cheddar and another variety), multiple pieces
10. Pesto or similar condiment in a jar - 1 jar
11. Mayonnaise or similar condiment in a jar - 1 jar
12. Peanut butter or similar spread in a jar - 1 jar
13. A plastic container with a blue lid - contents unknown - 1 container
14. Various wrapped items (likely cheeses or deli meats) - at least 3 items
15. Small yellow container with a green lid - 1 (possibly a condiment)
16. A small round container with brown contents - possibly a spread or condiment - 1
17. A wrapped block of an unknown item (possibly cheese) with European Union flag - 1 item
18. Clear plastic container with a white substance - possibly dairy or a spread - 1 container

Please note that due to the angle and items being partially obscured, some estimations may not be fully accurate.";

$string = openai__parse_vision_completion($string);
echo '<pre>';
echo $string;
echo '</pre>';