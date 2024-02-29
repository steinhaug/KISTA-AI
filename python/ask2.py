import json
import requests

def ask_openai_gpt4(message, model="gpt-4", api_key="your_openai_api_key_here"):
    """
    Sends a message to the OpenAI GPT-4 chat model and returns the response.

    Parameters:
    - message (str): The message to send to GPT-4.
    - model (str, optional): The model to use. Default is "gpt-4".
    - api_key (str): Your OpenAI API key.

    Returns:
    - str: The response message from GPT-4.
    """
    url = "https://api.openai.com/v1/chat/completions"
    headers = {
        "Authorization": f"Bearer {api_key}",
        "Content-Type": "application/json"
    }
    data = {
        "model": model,
        "messages": [{"role": "user", "content": message}]
    }

    try:
        response = requests.post(url, headers=headers, json=data)
        response_json = response.json()
        if response.status_code == 200:
            # Assuming the response contains at least one message
            return response_json['choices'][0]['message']['content']
        else:
            print(f"Error: {response_json.get('error', 'Unknown error')}")
            return None
    except Exception as e:
        print(f"An error occurred: {e}")
        return None


my_prompt = '''You are a gourmet chef with 50+ years of experience, your task is to come up with a food reciepe. For this task you will have a 
sortement of groceries to work with, so you will have to base your creation from theese.

CONSIDER THE FOLLOWING WHEN COMPLETING THIS TASK

- I have an oven. If the recipe requires it, preheat the oven to the recommended temperature and duration provided in the recipe.
- I only want to make a dish for one person.
- I have a blender.
- You do not have to use all of the ingredients. Please provide a recipe for one great dish, preferably for lunch or dinner.
- I do not care about side dishes. Only one recipe is needed, with no sides required.
- Please use the metric system, not the American measurement system.
- The name of the dish must be in English, and you must provide an estimation of calories, the country/region of origin of the recipe, and the duration of preparation.
- Optional ingredients are acceptable and can be suggested.

LIST OF AVAILABLE INGREDIENTS

- Old El Paso Salsa - 1 jar
- Pickled Gherkins by Sainsbury's - 1 jar
- Various pickled condiments - 3 jars (including the one that is only partially visible on the far left)
- Mustard (brand starting with "Maille") - 1 jar
- Raspberry conserve or jam (partially obscured label) - 1 jar
- Sparkling wine, or similar beverage (with gold foil top) - at least 3 bottles
- Chilled spreadable product  (which might be butter or margarine, like Lurpak) - 1 tub
- Sweetcorn in water - 1 can
- Other condiments/spreads (in white-to-cream colored containers with green/red caps) - 2 small jars
- Parmesan cheese (or similar hard cheese) with plastic wrap - 1 piece
- Black olives or similar in brine (judging by the lid)  - 1 container
- Herb or seasoning container (with a green label) - 1 small tub

Please provide a gourmet recipe, as if you were the best chef in the world with over 50 years of experience.'''


# Example usage
if __name__ == "__main__":
    message = my_prompt
    api_key = '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'
    response_message = ask_openai_gpt4(message, api_key=api_key)
    if response_message:
        print(f"GPT-4 says: {response_message}")
    else:
        print("Failed to get a response from GPT-4.")



# RESULT
'''
GPT-4 says: RECIPE: Gourmet Salsa Parmigiano Stuffed Baked Olives with Mustard Sweetcorn Sauce 

COUNTRY/REGION OF ORIGIN: Italian-Mexican Fusion

PREPARATION TIME: 30 minutes

ESTIMATED CALORIES: Around 600 - 700 calories

INGREDIENTS:

- Black Olives - 10 pcs or about 60 grams
- Old El Paso Salsa - 2 tablespoons
- Parmesan Cheese (or similar hard cheese) - 30 grams grated
- Mustard (brand starting with "Maille") - 1 tablespoons
- Sweetcorn - 2 tablespoons,
- Chilled Spreadable Product (Butter/Margarine) - 1 tablespoons
- Herb or Seasoning - A pinch (as per your taste)

The rest of the ingredients are optional and can be added as per the taste.

PREPARATION:

1. Preheat your oven to 180 degrees Celsius.

2. De-stone and carefully hollow out the black olives. This can be a delicate process, but remember, the olive only needs to hold enough of the stuffing.

3. In a bowl, mix the grated Parmesan cheese and salsa together. You might need to blend the salsa into a more paste-like consistency before mixing it with the cheese. The mixture should be easy to spoon into the olives, but not too liquid.

4. Very carefully, using a small spoon or a piping bag, stuff the hollowed out olives with the cheese and salsa mixture.

5. Arrange the stuffed olives on a baking tray lined with baking paper. You might want to stick a single toothpick through each olive to secure its structure.

6. Bake for 10-15 minutes or until the olives are sizzling and the cheese melted.

7. While that is in the oven, make the Mustard Sweetcorn Sauce. Start by melting the chilled spreadable product in a saucepan over medium heat.

8. Drain the sweetcorn and add them to the saucepan. Sauté until they started to brown.

9. Add the mustard and seasoning (add gradually and taste, until it suits your preference). Turn down the heat and keep stirring until everything is well combined. If the mixture gets too dry, you can loosen it up with a splash of sparkling wine.

10. Serve the baked olives hot from the oven with the Mustard Sweetcorn Sauce on the side.

Enjoy your Gourmet Salsa Parmigiano Stuffed Baked Olives with Mustard Sweetcorn Sauce! This recipe can be a unique and delightful appetizer or a light main dish.

TIP:
- Olives and cheese can be quite salty, so approach this recipe with a moderate hand when it comes to seasoning. You can even rinse the olives in water before stuffing them to dial back the brine's impact on the final taste.
'''