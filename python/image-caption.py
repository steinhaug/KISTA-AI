import openai
import base64
from scripts.credentials import read_api_key

# Your OpenAI API key
openai.api_key = read_api_key()

def encode_image_to_base64(image_path):
    with open(image_path, "rb") as image_file:
        return base64.b64encode(image_file.read()).decode('utf-8')

def get_ingredients_from_image(image_path):
    encoded_image = encode_image_to_base64(image_path)
    
    prompt_text = f"Your job is to detect as many different groceries / separate items currently inside of the refrigerator as possible. If you find more items of the same type try to estimate pcs / quanta and return your findings as an item list with quantities. [image: {encoded_image}]"
 
    # Assuming 'image_model' is a placeholder for the actual model capable of image analysis
    response = openai.Completion.create(
        model="gpt-4-vision-preview",
        prompt=prompt_text,
        temperature=0.5,
        max_tokens=100,
        top_p=1.0,
        frequency_penalty=0.0,
        presence_penalty=0.0
    )
    
    description = response.choices[0].text
    ingredients = parse_description_to_ingredients(description)
    return ingredients

def parse_description_to_ingredients(description):
    # Implement natural language processing here to extract ingredients from the description
    # This is a placeholder function that needs to be developed based on the output format
    return description
    return ["ingredient1", "ingredient2", "ingredient3"]

# Example usage
image_path = "../assets/fridge/001.jpg"
ingredients = get_ingredients_from_image(image_path)
print(ingredients)
