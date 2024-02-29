import json
import requests

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
    api_key = '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'

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