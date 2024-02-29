import openai

# Function to ask GPT-4 a question
def ask_gpt4(question, model="gpt-3.5-turbo-instruct", temperature=0.7):
    """
    Asks a question to GPT-4 and returns the answer.

    Parameters:
    - question (str): The question to ask.
    - model (str, optional): The model to use. Default is "gpt-3.5-turbo".
    - temperature (float, optional): Controls randomness. Lower is more deterministic. Default is 0.7.

    Returns:
    - str: The answer from GPT-4.
    """
    try:
        # Ensure your API key is correctly configured here
        openai.api_key = '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'

        response = openai.Completion.create(
            engine=model,
            prompt=question,
            temperature=temperature,
            max_tokens=100,
            top_p=1.0,
            frequency_penalty=0.0,
            presence_penalty=0.0
        )

        # Extracting the text from the response
        answer = response.choices[0].text.strip()

        return answer
    except Exception as e:
        print(f"An error occurred: {e}")
        return None

# Example usage
if __name__ == "__main__":
    question = "What is the capital of Norway?"
    answer = ask_gpt4(question)
    print(f"Answer: {answer}")
