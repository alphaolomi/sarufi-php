<?php

namespace Alphaolomi\Sarufi;

use Alphaolomi\Sarufi\Exceptions\FileNotFoundException;
use GuzzleHttp\Client as GuzzleHttpClient;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Sarufi
 * @author Alpha Olomi
 * @version 1.0
 */
class Sarufi
{
    public const BASE_URL = "https://developers.sarufi.io/";

    protected string $token;
    protected GuzzleHttpClient $httpClient;

    public function __construct(string $token = null)
    {
        $token = empty(trim($token));
        if ($token) {
            throw new \RuntimeException("Invalid credentials, token is empty");
        }
        $this->token = $token;
        $this->httpClient = new GuzzleHttpClient([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);
    }

    // use absolute path here
    // check if its ablsote path
    // otherwise check current dir for file
    // if missing throw missing
    /**
     * Read json, yaml or PHP array file
     * @internal
     */
    public static function readFile(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException(path: $path);
        }

        if (str_ends_with($path, '.json')) {
            try {
                $fileStringContent = file_get_contents($path);

                return json_decode($fileStringContent, true);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        if (str_ends_with($path, '.yml') || str_ends_with($path, '.yaml')) {
            if (!class_exists(Yaml::class)) {
                throw new \RuntimeException(
                    sprintf("Yaml parser not found, install symfony/yaml package")
                );
            }
            try {
                $data = Yaml::parseFile($path);

                return $data;
            } catch (ParseException $exception) {
                // printf('Unable to parse the YAML string: %s', $exception->getMessage());
                throw $exception;
            }
        }

        if (str_ends_with($path, '.php')) {
            try {
                $data = include $path;

                return $data;
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        throw new \RuntimeException(
            sprintf("Unable to read file: %s", $path)
        );
    }

    //
    public function createBot(
        string $name = "My Bot",
        null|string $description = null,
        string $industry = "general",
        $flows = [],
        $intents = [],
        bool $visibleOnCommunity = false,
        $modelName = null,
        int $confidence_threshold = null,
        $evaluationMetrics = [],
        $language = null, // english or swahili
        $webhookUrl = null,
        $webhookTriggerIntents = [],
    ) {
        $data = [
            "name" => $name,
            "description" => $description,
            "intents" => $intents,
            "flows" => $flows,
            "industry" => $industry,
            "visible_on_community" => $visibleOnCommunity,
            "model_name" => $modelName,
            "confidence_threshold" => $confidence_threshold,
            "evaluation_metrics" => $evaluationMetrics,
            "language" => $language,
            "webhook_url" => $webhookUrl,
            "webhook_trigger_intents" => $webhookTriggerIntents,
        ];
        $response = $this->httpClient->post("chatbot", ["json" => $data]);

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Create bot from file(s)
     * ## Example
     * ```php
     * $sarufi = new Sarufi('your_token')
     * $bot = $sarufi->createFromFile(
     *  intents: __DIR__ . '/intents.json',
     *  flows: __DIR__ . '/flows.json',
     *  metadata: __DIR__ . '/metadata.json',
     * );
     * ````
     */
    public function createFromFile(
        ?string $metadata = null,
        ?string $intents = null,
        ?string $flows = null
        )
    {
        // must have metadata
        // other are optional
        //
        $_intents = $this->readFile($intents);
        $_flows = $this->readFile($flows);
        $_metadata = $this->readFile($metadata);



        $res = $this->createBot(
            intents: $_intents,
            flows: $_flows,
            name: $_metadata["name"],
            description: $_metadata["description"],
            industry: $_metadata["industry"],
            visibleOnCommunity: $_metadata["visible_on_community"],
            modelName: $_metadata["model_name"],
            confidence_threshold: $_metadata["confidence_threshold"],
            evaluationMetrics: $_metadata["evaluation_metrics"],
            language: $_metadata["language"],
            webhookUrl: $_metadata["webhook_url"],
            webhookTriggerIntents: $_metadata["webhook_trigger_intents"],
        );

        return $res;
    }

    /**
     * Update a bot
     *
     * Example
     * ```
     * $sarufi = new Sarufi('your_token')
     * $bot = $sarufi->updateBot(
     * 		id: 5,
     *      name: 'Maria',
     *      description: 'A chatbot that does this and that',
     *      intents: [],
     *      flows: [],
     *      industry: 'healthcare',
     *      visibleOnCommunity: True
     * );
     * ```
     */
    public function updateBot(
        $id,
        string $name,
        string $industry,
        string $description,
        array $intents = [],
        array $flows = [],
        bool $visibleOnCommunity = false,
    ) {
        $data = [
            "name" => $name,
            "description" => $description,
            "intents" => $intents,
            "flows" => $flows,
            "industry" => $industry,
            "visible_on_community" => $visibleOnCommunity,
        ];
        $res = $this->httpClient->put("chatbot/{$id}", ["json" => $data]);

        return json_decode((string) $res->getBody(), true);
    }

    /**
     * Update a bot from file(s)
     *
     *
     * Example
     * ```php
     * $sarufi = new Sarufi('your_token')
     * $bot = $sarufi->updateFromFile(
     *     id:5,
     *     intents:'data/intents.json',
     *     flows:'data/flow.json',
     *     metadata:'data/metadata.json'
     * );
     * ````
     */
    public function updateFromFile(
        string $id,
        string $intents,
        string $flows,
        string $metadata,
    ) {
        $_intents = $this->readFile($intents);
        $_flow = $this->readFile($flows);
        $_metadata = $this->readFile($metadata);

        $res = $this->updateBot(
            id: $id,
            name: $_metadata["name"],
            description: $_metadata["description"],
            industry: $_metadata["industry"],
            visibleOnCommunity: $_metadata["visible_on_community"],
            intents: $_intents,
            flows: $_flow,
        );

        return $res;
    }

    /**
     * Get bot details
     * @param string $id
     */
    public function getBot(string $id)
    {
        $response = $this->httpClient->get("chatbot/{$id}");

        return json_decode((string)$response->getBody(), true);
    }

    public function bots(): array
    {
        $response = $this->httpClient->get("chatbots");

        return json_decode((string)$response->getBody(), true);
    }

    private function fetchResponse(
        int $botId,
        string $chatId,
        string $message,
        string $messageType,
        string $channel = "whatsapp",
    ) {
        $data = [
            "chat_id" => $chatId,
            "bot_id" => $botId,
            "message" => $message,
            "message_type" => $messageType,

        ];
        $url = $channel === "whatsapp" ? "conversation/whatsapp/" : "conversation/";

        $res = $this->httpClient->post($url, ["json" => $data]);

        return json_decode((string)$res->getBody(), true);
    }

    // conversation
    public function conversation(
        int $botId,
        string $chatId,
        string $message,
        string $messageType,
        string $channel = "whatsapp",
    ) {
        return $this->fetchResponse(
            botId: $botId,
            chatId: $chatId,
            message: $message,
            messageType: $messageType,
            channel: $channel,
        );
    }

    // conversationStatus
    public function conversationStatus(
        int $botId,
        string $chatId,
    ) {
        $data = [
            "chat_id" => $chatId,
            "bot_id" => $botId
        ];

        $res = $this->httpClient->post("conversation/status", ["json" => $data]);

        return json_decode((string)$res->getBody(), true);
    }

    // intents
    public function intents(int $botId)
    {
        $res = $this->httpClient->get("intents/{$botId}");

        return json_decode((string)$res->getBody(), true);
    }

    // flow
    public function flows(int $botId)
    {
        $res = $this->httpClient->get("flow/{$botId}");

        return json_decode((string)$res->getBody(), true);
    }

    // preditIntent
    public function predictIntent(
        int $botId,
        string $message,
    ) {
        $data = [
            "message" => $message,
            "bot_id" => $botId
        ];

        $res = $this->httpClient->post("predict/intent", ["json" => $data]);

        return json_decode((string)$res->getBody(), true);
    }

    // conversionState
    public function conversionState(
        int $botId,
        string $chatId,
    ) {
        $data = [
            "chat_id" => $chatId,
            "bot_id" => $botId
        ];

        $res = $this->httpClient->post("conversation/state", ["json" => $data]);

        return json_decode((string)$res->getBody(), true);
    }

    // chatBotUsers
    public function chatBotUsers(int $botId)
    {

        $res = $this->httpClient->get("chatbot/$botId/users");

        return json_decode((string)$res->getBody(), true);
    }

    // conversationHistory
    public function conversationHistory(int $botId, string $chatId)
    {
        $res = $this->httpClient->get("conversation/history/$botId/$chatId");

        return json_decode((string)$res->getBody(), true);
    }

    // getPlugin
    public function getPlugin(int $botId, string $pluginName)
    {
        $res = $this->httpClient->get("plugin/$botId/$pluginName");

        return json_decode((string)$res->getBody(), true);
    }

    // addPlugin
    public function addPlugin(
        int $botId,
        $theme_config = [],
        $approved_domain = null
    ) {

        $data = [
            "bot_id" => $botId,
            "theme_config" => $theme_config,
            "approved_domain" => $approved_domain
        ];
        $res = $this->httpClient->post("plugin/$botId", ["json" => $data]);

        return json_decode((string)$res->getBody(), true);
    }

    // deletePlugin
    public function deletePlugin(int $botId)
    {
        $res = $this->httpClient->delete("plugin/$botId");

        return json_decode((string)$res->getBody(), true);
    }

    /**
     * Get next reposnse, assuiming use have a already created a bot
     *
     * @param     int $botId,
     * @param  string $chatId
     * @param  string $message
     * @param  string $messageType
     * @param  string $channel
     * @return array
     *
     */
    public function chat(
        int $botId,
        string $chatId,
        string $message = "Hello",
        string $messageType = "text",
        string $channel = "general",
    ) {
        return  $this->fetchResponse(
            botId: $botId,
            chatId: $chatId,
            message: $message,
            messageType: $messageType,
            channel: $channel,
        );
    }

    /**
     * Delete a bot
     *
     * @param string $id
     * @return array
     */
    public function deleteBot($id)
    {
        $response = $this->httpClient->delete("chatbot/{$id}");

        return json_decode((string)$response->getBody(), true);
    }
}
