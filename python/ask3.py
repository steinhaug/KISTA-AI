import json
import requests
from scripts.credentials import read_api_key

OPENAI_API_KEY = read_api_key()

def ask_openai_gpt4(conversation, model="gpt-4", api_key="your_openai_api_key_here"):
    """
    Sends a conversation to the OpenAI GPT-4 chat model and returns the updated conversation with GPT-4's response.

    Parameters:
    - conversation (list): A list of message dicts in the conversation so far.
      Each message is a dict with 'role' ('user' or 'assistant') and 'content'.
    - model (str, optional): The model to use. Default is "gpt-4".
    - api_key (str): Your OpenAI API key.

    Returns:
    - list: The updated conversation including GPT-4's response.
    """
    url = "https://api.openai.com/v1/chat/completions"
    headers = {
        "Authorization": f"Bearer {api_key}",
        "Content-Type": "application/json"
    }
    data = {
        "model": model,
        "messages": conversation
    }

    try:
        response = requests.post(url, headers=headers, json=data)
        response_json = response.json()
        if response.status_code == 200:
            # Adding GPT-4's response to the conversation
            conversation.append(response_json['choices'][0]['message'])
            return conversation
        else:
            print(f"Error: {response_json.get('error', 'Unknown error')}")
            return conversation
    except Exception as e:
        print(f"An error occurred: {e}")
        return conversation


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

- Eggs - Approximately 6 eggs in the door bin.
- Whole grain oatmeal box - 1 box.
- Condiment bottles - Various kinds, at least 10 (including hot sauce, soy sauce, mayonnaise, mustard, and other sauces).
- Carbonated beverage cans - At least 3 visible Coca-Cola cans.
- Drink bottle - 1 white bottle, possibly dairy or non-dairy milk/cream.
- Containers with yellow lids - 2 containers (contents unknown).
- Clear container in the upper shelf - 1 (contents unknown).
- Small jars (possibly containing spices or condiments) - About 7 jars.
- Butter or margarine tub - 1.
- Gelatin powder box - 1.
- Large beverage bottle (label has green text, possibly an oil or drink) - 1.
- Large white bottle with red cap (possibly mayonnaise or a dairy product) - 1.

Please provide a gourmet recipe, as if you were the best chef in the world with over 50 years of experience.'''


# Example usage
if __name__ == "__main__":
    api_key = OPENAI_API_KEY

    # First question
    conversation = [{"role": "user", "content": my_prompt}]
    conversation = ask_openai_gpt4(conversation, api_key=api_key)
    if conversation[-1]:
        print(f"GPT-4 says: {conversation[-1]['content']}")

    # Follow-up question based on GPT-4's response
    follow_up_question = "Based on the reciepe above, come up with 4 different prompt ideas that I can give DALL-E to imagine the reciepe."
    conversation.append({"role": "user", "content": follow_up_question})
    conversation = ask_openai_gpt4(conversation, api_key=api_key)
    if conversation[-1]:
        print(f"GPT-4 says: {conversation[-1]['content']}")




# ANSWER
'''
GPT-4 says: RECIPE NAME:      
Salsa Parmigiano Gherkin Rolls

ORIGIN:
Italian-Mexican fusion        

PREPARATION TIME: 
30 Minutes

ESTIMATED CALORIES:
400-450 Calories

INGREDIENTS:

1. Old El Paso Salsa - 200 grams
2. Pickled Gherkins - 2 pieces
3. Mustard (brand starting with "Maille") - 1 tablespoon
4. Sparkling Wine - 1 glass
5. Chilled spreadable product (Lurpak or similar) - 1 tablespoon
6. Sweetcorn (drained) - 1 tablespoon
7. Parmesan Cheese - 50 grams
8. Black olives (drained and pitted) - 5 pieces
9. Herbs (of your choice) - as needed

(Optional: Condiment in white-to-cream colored containers can be used for extra flavor)

INSTRUCTIONS:

1. Preheat the oven to 200 degrees Celsius.

2. Thinly slice Parmesan cheese with a cheese slicer. You should have eight thin slices, around the same length as the gherkins.

3. Spread each slice of cheese with a thin layer of the chilled spreadable Lurpak product.

4. Thinly slice the gherkins and black olives. Mix them with the sweetcorn in a bowl and apply a dab of mustard and salsa to it. Mix thoroughly ensuring the condiments are evenly spread.

5. Next, place this mixture evenly onto the cheese slices and carefully roll them.

6. Once you have the rolls ready, place them on a baking tray lined with baking paper.

7. Bake these rolls in the preheated oven for 10 minutes.

8. In the meantime, heat a pan. Add the rest of the salsa, a tablespoon of mustard, and a pour of sparkling wine into the pan. Simmer for a few minutes until the wine has reduced.      

9. Pull out the rolls from the oven once they are golden brown and have melted slightly.

10. Pour the warm salsa wine reduction onto a plate, then carefully place the Parmigiano Gherkin Rolls on top. Drizzle the rolls with the remaining salsa and garnish with herbs of your 
choice.

Serve your Salsa Parmigiano Gherkin Rolls hot and enjoy this unique Italian-Mexican fusion dish.


GPT-4 says: 

1. "Generate an image of Salsa Parmigiano Gherkin Rolls placed on a white plate with a golden brown texture on the cheese and a drizzle of red salsa wine reduction."        

2. "Conjure an illustration of an Italian-Mexican fusion cuisine featuring Parmesan cheese rolls filled with sliced gherkins, black olives, and sweetcorn, topped with salsa wine reduction."

3. "Generate an image of the process of making Salsa Parmigiano Gherkin Rolls, showing the thin slices of Parmesan cheese rolled with mixed fillings and the baking process."

4. "Create an artistic representation of a gourmet dish named Salsa Parmigiano Gherkin Rolls with cheese slices melting around a mixture of gherkins, black olives, and sweetcorn, all served with salsa wine reduction on top."

'''



'''

GPT-4 says: DISH NAME: Rich Creamy Oatmeal Carbonara

Origin: Italy-Inspired

Duration of Preparation: 30-40 minutes

Estimated Calories: 800-900 cal

INGREDIENTS:

- Whole grain oatmeal box - 50g
- Eggs - 2
- Butter or margarine tub - 15g
- Condiment bottles - freshly ground black pepper, a pinch of hot sauce, 1 teaspoon of mustard
- Drink bottle - possibly dairy or non-dairy milk/cream. If it’s cream, then 50ml
- Large white bottle with red cap - if it’s mayonnaise, then 1 tablespoon
- Small jars - we'll use this if they contain spices (like garlic powder, onion powder). If so, then 1/4 teaspoon each.

INSTRUCTIONS:

1. Bring about 250ml of water to boil in a saucepan. Stir in the oats. Reduce heat to medium and cook for 10 minutes, stirring occasionally.

2. In a blender, blend 1 egg with the cream (if it's cream in the bottle),  black pepper, mustard, a small pinch of hot sauce, mayonnaise (if it is indeed in the large white bottle with red cap), and 1/4 teaspoon each of garlic and onion powder (if available in the small jars). Blend until all ingredients are well combined into a creamy sauce.

3. In a non-stick pan, add the butter and let it melt. Once it's melted, carefully crack in the other egg and cook it until the edges are crispy but yolk is still runny (also known as a fried egg).

4. When the oats are cooked, remove from the heat. While it's still warm, slowly add the blended sauce into the oatmeal while consistently stirring to make sure the egg doesn't get cooked and scrambled in the hot oatmeal.

5. Once all the sauce is added, put the oatmeal back on low heat and keep stirring for another 2 minutes.

6. Serve the oatmeal in a bowl with the fried egg on top for a rich and hearty lunch or dinner.

(KEY NOTE: The containers with yellow lids and the clear container in the upper shelf were not used as their contents are unknown. The carbonated beverage cans and the large beverage bottle with green text label were also not used in this recipe due to their unsuitability for this dish. These items might be handy for other creations or as accompaniments for the meal.)

GPT-4 says: 
1. "DALL-E, imagine a gourmet dish called Rich Creamy Oatmeal Carbonara, featuring a bowl of creamy oatmeal topped with a perfectly fried egg with the yolk still runny."    
2. "DALL-E, could you visualize a decadent lunch scene that includes a creamy, steaming bowl of oatmeal carbonara with a beautifully fried egg on top on a rustic wooden table."
3. "Imagine the preparation and cooking process of creating a gourmet dish known as Rich Creamy Oatmeal Carbonara from a chef's perspective, highlighting crucial steps such as blending 
ingredients, frying an egg, and stirring in the creamy sauce."
4. "DALL-E, illustrate a close-up shot of the final Rich Creamy Oatmeal Carbonara dish, showcasing the contrast between the creamy oatmeal and the fried egg on top, with a nice rustic background."

'''