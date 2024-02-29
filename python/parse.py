

obj = {'id': 'chatcmpl-8xXXd68poaoI9rLtXaUr3QIlTkNEU', 'object': 'chat.completion', 'created': 1709200977, 'model': 'gpt-4-1106-vision-preview', 'usage': {'prompt_tokens': 819, 'completion_tokens': 300, 'total_tokens': 1119}, 'choices': [{'message': {'role': 'assistant', 'content': 'This task involves some educated guessing, as not all items and labels are fully visible or readable in the image. Here\'s a list of different groceries/separate items that can be inferred from the picture with estimated quantities:\n\n1. **Old El Paso Salsa** - 1 jar\n2. **Pickled Gherkins by Sainsbury\'s** - 1 jar\n3. **Various pickled condiments** - 3 jars (including the one that is only partially visible on the far left)\n4. **Mustard** (brand starting with "Maille") - 1 jar\n5. **Raspberry conserve or jam** (partially obscured label) - 1 jar\n6. **Sparkling wine**, or similar beverage (with gold foil top) - at least 3 bottles\n7. **Chilled spreadable product ** (which might be butter or margarine, like Lurpak) - 1 tub\n8. **Sweetcorn in water** - 1 can\n9. **Other condiments/spreads** (in white-to-cream colored containers with green/red caps) - 2 small jars\n10. **Parmesan cheese** (or similar hard cheese) with plastic wrap - 1 piece\n11. **Black olives or similar in brine** (judging by the lid)  - 1 container\n12. **Herb or seasoning container** (with a green label) - 1 small tub'}, 'finish_reason': 'length', 'index': 0}]}

content_part = obj['choices'][0]['message']['content']
#print(content_part)

def prepare_list(myList):
    # Split the string into individual lines
    lines = myList.split('\n')
    
    # Remove bullet points and bold formatting
    clean_lines = [line.replace('**', '').lstrip('1234567890.').strip() for line in lines]
    
    # Join the lines with a hyphen at the beginning of each line
    result = '\n'.join(['- ' + line for line in clean_lines])
    
    return result

def prepare_list2(myList):
    # Split the string into individual lines
    lines = myList.split('\n')
    
    # Filter out lines that do not start with a number
    filtered_lines = [line for line in lines if line.strip().startswith(('1', '2', '3', '4', '5', '6', '7', '8', '9', '0'))]
    
    # Remove leading numbers and bold formatting from the remaining lines
    clean_lines = [line.replace('**', '').lstrip('1234567890.').strip() for line in filtered_lines]
    
    # Join the lines with a hyphen at the beginning of each line
    result = '\n'.join(['- ' + line for line in clean_lines])
    
    return result

print(prepare_list2(content_part))
