<?php

        $client = OpenAI::client($open_ai_key);
        $base64Image = imageToBase64($imgIn);
        $promptQue = [];
        $promptQue[] = [
            'type' => 'image_url',
            'image_url' => ['url' => $base64Image]
        ];
        $promptQue[] = [
            'type' => 'text',
            'text' => 'Your job is to detect as many different groceries / seperate items currently inside of the refrigerator as possible. If you find more items of the same type try to estimate pcs / quanta and return your findings as an item list with quantities.'
        ];
        $messagesArray = [];
        $messagesArray[] = [
            'role' => 'user',
            'content' => $promptQue
        ];
        $settings['model'] = 'gpt-4-vision-preview';
        $settings['messages'] = $messagesArray;
        $settings['max_tokens'] = 1200;
        try {
            $response = $client->chat()->create($settings);
            $additionalQuestion = [
                'role' => 'user',
                'content' => [['type' => 'text', 'text' => 'If there is a refridgerator visible in the image answer answer one word only YES, if no refridgerator is visible answer one word only NO.']]
            ];
            $settings['messages'][] = $additionalQuestion;
            $response2 = $client->chat()->create($settings);
        } catch (Exception $e) {
            throw new OpenAIException($e->getMessage());
        }

        $chatgpt_result1 = '';
        $chatgpt_result2 = '';

        foreach ($response->choices as $result) {
            $chatgpt_result1 = $result->message->content;
        }
        $json_all = $response->toArray();
        $meta = $response->meta();
        $json_meta = $meta->toArray();
        $log[] = 'ChatGPT Vision:';
        $log['vision_m1'] = json_encode($json_meta);
        $log['vision_q1'] = json_encode($json_all);

        foreach ($response2->choices as $result) {
            $chatgpt_result2 = $result->message->content;
        }
        $json_all = $response2->toArray();
        $meta = $response2->meta();
        $json_meta = $meta->toArray();
        $log['vision_m2'] = json_encode($json_meta);
        $log['vision_q2'] = json_encode($json_all);

        if( $chatgpt_result2 == 'NO' ){
            throw new OpenAIException('Missing refridgerator');
        }

        $list_of_ingredients = openai__parse_vision_completion($chatgpt_result1);

        if( empty($list_of_ingredients) ){
            throw new OpenAIException('Missing ingredients');
        }
