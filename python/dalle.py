import requests
import os

# Replace 'your_openai_api_key_here' with your actual OpenAI API key
api_key = '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'

#img1 = 'Generate an image of Salsa Parmigiano Gherkin Rolls placed on a white plate with a golden brown texture on the cheese and a drizzle of red salsa wine reduction.'
#img2 = 'Conjure an illustration of an Italian-Mexican fusion cuisine featuring Parmesan cheese rolls filled with sliced gherkins, black olives, and sweetcorn, topped with salsa wine reduction.'
#img3 = 'Generate an image of the process of making Salsa Parmigiano Gherkin Rolls, showing the thin slices of Parmesan cheese rolled with mixed fillings and the baking process.'
#img4 = 'Create an artistic representation of a gourmet dish named Salsa Parmigiano Gherkin Rolls with cheese slices melting around a mixture of gherkins, black olives, and sweetcorn, all served with salsa wine reduction on top.'

img1 = "DALL-E, imagine a gourmet dish called Rich Creamy Oatmeal Carbonara, featuring a bowl of creamy oatmeal topped with a perfectly fried egg with the yolk still runny."    
img2 = "DALL-E, could you visualize a decadent lunch scene that includes a creamy, steaming bowl of oatmeal carbonara with a beautifully fried egg on top on a rustic wooden table."
img3 = "Imagine the preparation and cooking process of creating a gourmet dish known as Rich Creamy Oatmeal Carbonara from a chef's perspective, highlighting crucial steps such as blending ingredients, frying an egg, and stirring in the creamy sauce."
img4 = "DALL-E, illustrate a close-up shot of the final Rich Creamy Oatmeal Carbonara dish, showcasing the contrast between the creamy oatmeal and the fried egg on top, with a nice rustic background."

headers = {
    "Content-Type": "application/json",
    "Authorization": f"Bearer {api_key}"
}


img5 = 'Create a diner-style image focusing on making it look delicious and appetizing, featuring an elegant savory oatmeal tart placed on a fine porcelain plate. The tart should have a golden-brown crust made from whole grain oatmeal, filled with a creamy, mustard-infused custard. On top of the tart, a perfectly soft-boiled egg is cut in half, revealing a runny yolk. A creamy mustard sauce is artistically drizzled over and around the tart, with a sprinkle of fresh herbs for garnish.'
img6 = 'Create a diner-style image focusing on making it look delicious and appetizing, showcasing a close-up of a savory oatmeal tart on a rustic wooden table. The tart should have a crisp, textured base, with a soft, flavorful filling visible. Beside the tart, a silver spoon is smeared with a rich, creamy mustard sauce, and the soft-boiled egg on top gently oozes its yolk over the sides. The background hints at a cozy, gourmet kitchen setting.'
img7 = 'Create a diner-style image focusing on making it look delicious and appetizing, with a savory oatmeal tart taking center stage on a slate serving board. The tart\'s oatmeal crust should appear crunchy and inviting, with the filling set to perfection. A soft-boiled egg, sliced in half to showcase its soft, vibrant yolk, sits atop the tart. Around the dish, small dots of creamy mustard sauce and delicate herbs add a touch of elegance and color.'
img8 = 'Create a diner-style image focusing on making it look delicious and appetizing, depicting a savory oatmeal tart in a dimly lit setting that emphasizes its warmth and texture. The tart, served on a vintage plate, features a golden oatmeal crust with a lush, savory filling. A soft-boiled egg, with its yolk just beginning to spill, crowns the tart. The creamy mustard sauce is elegantly drizzled in a zigzag pattern over the tart and plate, with ambient light casting soft shadows to enhance the dish\'s appeal.'

data = {
    "model": "dall-e-3",
    "prompt": img7,
    "n": 1,
    "size": "1024x1024"
}

response = requests.post("https://api.openai.com/v1/images/generations", headers=headers, json=data)

if response.status_code == 200:
    print("Image generated successfully.")
    # The response includes the URLs to the generated images, among other details
    print(response.json())
else:
    print("Failed to generate image.")
    print(response.text)



# results:
'''
{'created': 1709205363, 'data': [{'revised_prompt': 'Create an enticing image where Salsa Parmigiano Gherkin Rolls are settled on a pure white plate. These rolls should have a golden brown texture on the cheese which testifies its perfect cook. The plate has a noticeable garnish of red salsa wine reduction delicately drizzled over the rolls adding a pop of color and enhanced flavor. Please maintain the visual focus on the rolls, accenting the contrast provided by the white plate.', 'url': 'https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-IDkLIGi3tzb0w5xnca8yuZPE.png?st=2024-02-29T10%3A16%3A03Z&se=2024-02-29T12%3A16%3A03Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-02-29T05%3A54%3A27Z&ske=2024-03-01T05%3A54%3A27Z&sks=b&skv=2021-08-06&sig=PZllfyrqxl7icHZfFIKSMZd4RIV5vHZp4C46pknpjKc%3D'}]}
{'created': 1709205490, 'data': [{'revised_prompt': 'Imagine a delightful culinary harmonization of Italian and Mexican cuisines. It features Parmesan cheese rolls, a traditional Italian element, stuffed with an intriguing mix of sliced gherkins, black olives, and sweetcorn, ingredients commonly found in Mexican food. These flavourful rolls are beautifully garnished with a salsa wine reduction, a combination of two culinary worlds. The resulting fusion dish is a testament to the versatility and deliciousness of combining distinct global flavors.', 'url': 'https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-pEKXLXXLId7Y3YP4PTaEj2cE.png?st=2024-02-29T10%3A18%3A10Z&se=2024-02-29T12%3A18%3A10Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-02-29T04%3A42%3A31Z&ske=2024-03-01T04%3A42%3A31Z&sks=b&skv=2021-08-06&sig=HVHmezBJnVPEGIHQbRG1pcnpK8/jlXxn0vVmZzMoCnI%3D'}]}
{'created': 1709205537, 'data': [{'revised_prompt': 'An elaborate visual display of the process of making Salsa Parmigiano Gherkin Rolls. Start with the sight of a wooden cutting board 
on which are thinly sliced Parmesan cheese. Alongside, in a ceramic bowl, mixed fillings of finely chopped salsa and gherkin stand ready for use. Show the sequence of these ingredients 
being carefully rolled in the Parmesan cheese slices, resulting in neat, delicate rolls. Now, focus on the baking process, with the rolls placed on a parchment-lined baking sheet being 
slipped into a preheated oven. Lastly, portray the golden-browned rolls fresh out of the oven, radiating warmth and mouth-watering aroma.', 'url': 'https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-Uy4IbnciMc8ksD9s0La3uZ59.png?st=2024-02-29T10%3A18%3A57Z&se=2024-02-29T12%3A18%3A57Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-02-29T07%3A27%3A05Z&ske=2024-03-01T07%3A27%3A05Z&sks=b&skv=2021-08-06&sig=%2B4SGK6uG4KTKbD9HrD3Y%2BxkuUuVyMoVgvFEL1x7/5ts%3D'}]}
{'created': 1709205605, 'data': [{'revised_prompt': 'Create an image of a sophisticated and delectable culinary creation named Salsa Parmigiano Gherkin Rolls. The dish has thinly sliced, melting cheese encompassing a flavorful mixture of gherkins, black olives, and sweetcorn. Each roll sits delicately on a plate, with a tantalizing salsa wine reduction generously drizzled over the top. The contrast between the vibrant colors of the ingredients and the deep red wine reduction creates a visually appealing and appetizing scene.', 'url': 'https://oaidalleapiprodscus.blob.core.windows.net/private/org-UnWQwO02hhQxFEzXg50oYEIo/user-MZxMP72hrU22dZHNg3I7Umkv/img-FNoQa3JQRdQLGPM9Oa0f2QzX.png?st=2024-02-29T10%3A20%3A05Z&se=2024-02-29T12%3A20%3A05Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2024-02-29T04%3A40%3A12Z&ske=2024-03-01T04%3A40%3A12Z&sks=b&skv=2021-08-06&sig=7WXCTL5Z72J7WrTvlu3EHxGI1Uq2C5uulFDG%2B07OOO0%3D'}]}

Not step-by-step, not illustration.

Create a diner-style image focusing on making it look delicious and appetizing. 

'''