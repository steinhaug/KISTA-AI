def read_api_key():
    
    # Open the file in read mode
    with open('../.openai_api_key', 'r') as file:
        # Read the contents of the file into a string
        api_key = file.read()

    return api_key    

if __name__ == "__main__":
    print( f"Api Key: " + read_api_key() )
