<?php

namespace Alphaolomi\Sarufi;

use Alphaolomi\Sarufi\Exceptions\FileNotFoundException;
use GuzzleHttp\Client as GuzzleHttpClient;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Sarufi
{
    public const BASE_URL = "https://api.sarufi.io/";

	protected string $username;
	protected string $password;
	protected string $token;
	protected GuzzleHttpClient $httpClient;

	public function __construct(string $username, string $password, null|string $token = null)
	{
		$this->username = $username;
		$this->password = $password;
		$this->httpClient = new GuzzleHttpClient();
		if (is_null($token)) {
			try {
				$this->token = $this->getToken()['token'];
			} catch (\Throwable $th) {
				throw new \RuntimeException("Invalid credentials");
			}
		}
	}

	private function getToken()
	{
		$url = self::BASE_URL . "users/login";
		$res = $this->httpClient->post($url, [
			"headers" => [],
			"json" => [
				"username" => $this->username,
				"password" => $this->password
			]
		]);

		return json_decode((string) $res->getBody(), true);
	}

    private function updateToken()
    {
        try {
            $this->token = $this->getToken();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

	// use absolute path here
	// check if its ablsote path 
	// otherwise check current dir for file
	// if missing throw missing
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

			try {
				$data =  Yaml::parseFile($path);
				return $data;
			} catch (ParseException $exception) {
				// printf('Unable to parse the YAML string: %s', $exception->getMessage());
				throw $exception;
			}
		}

        throw new \RuntimeException(
            sprintf("Unable to read file: %s", $path)
        );
    }

	//
	public function createBot(
		string $name,
		null|string $description = null,
		string $industry = "general",
		$flow = [],
		$intents = [],
		bool $visibleOnCommunity = false
	) {
		$url = self::BASE_URL . "chatbot";
		$data = [
			"name" => $name,
			"description" => $description,
			"intents" => $intents,
			"flows" => $flow,
			"industry" => $industry,
			"visible_on_community" => $visibleOnCommunity,
		];
		$response = $this->httpClient->post($url, [
			"json" => $data,
			"headers" => [
				"Authorization" => "Bearer " . $this->token,
			]
		]);
		return json_decode((string)$response->getBody(), true);
		// return Bot(response.json(), token=self.token)
	}

	// $sarufi = new Sarufi('your_email', 'your_password')
	// $bot = $sarufi->createFromFile(
	//  intents: 'data/intents.json',
	//  flow: 'data/flow.json',
	//  metadata: 'data/metadata.json'
	// );
	public function createFromFile($metadata = null, $intents = null, $flow = null)
	{
		// must have metadata 
		// other are optional
		// 
		$_intents = $this->readFile($intents);
		$_flow = $this->readFile($flow);
		$_metadata = $this->readFile($metadata);



		$res = $this->createBot(
			name: $_metadata["name"],
			description: $_metadata["description"],
			industry: $_metadata["industry"],
			visibleOnCommunity: $_metadata["visible_on_community"],
			intents: $_intents,
			flow: $_flow,
		);
		return $res;
	}


	// $sarufi = new Sarufi('your_email', 'your_password')
	// $bot = $sarufi->updateBot(
	// 		id: 5,
	//      name: 'Maria',
	//      description: 'A chatbot that does this and that',
	//      intents: [],
	//      flow: [],
	//      industry: 'healthcare',
	//      visibleOnCommunity: True
	// );
	public function updateBot(
		$id,
		$name,
		$industry,
		$description,
		$intents,
		$flow,
		$visibleOnCommunity,
	) {
		$url = self::BASE_URL . "chatbot/{$id}";
		$data = [
			"name" => $name,
			"description" => $description,
			"intents" => $intents,
			"flows" => $flow,
			"industry" => $industry,
			"visible_on_community" => $visibleOnCommunity,
		];
		$res = $this->httpClient->put($url, [
			"headers" => ["Authorization" => "Bearer " . $this->token,],
			"json" => $data
		]);

        return json_decode((string) $res->getBody(), true);
    }

    // $sarufi = new Sarufi('your_email', 'your_password')
    // $bot = $sarufi->updateFromFile(
    //     id:5,
    //     intents:'data/intents.json',
    //     flow:'data/flow.json',
    //     metadata:'data/metadata.json'
    // );
    public function updateFromFile(
        $id,
        $intents,
        $flow,
        $metadata,
    ) {
        $_intents = $this->readFile($intents);
        $_flow = $this->readFile($flow);
        $_metadata = $this->readFile($metadata);

        $res = $this->updateBot(
            id: $id,
            name: $_metadata["name"],
            description: $_metadata["description"],
            industry: $_metadata["industry"],
            visibleOnCommunity: $_metadata["visible_on_community"],
            intents: $_intents,
            flow: $_flow,
        );

        return $res;
    }


	public function getBot($id)
	{
		$url = self::BASE_URL . "chatbot/{$id}";
		$response = $this->httpClient->get($url, [
			"headers" => ["Authorization" => "Bearer " . $this->token]
		]);
		return json_decode((string)$response->getBody(), true);
		// return Bot::fromJson((string)$response->getBody(),$this->token);
	}



	public function bots(): array
	{
		$url = self::BASE_URL . "chatbots";
		$response = $this->httpClient->get($url, [
			"headers" => ["Authorization" => "Bearer " . $this->token,]
		]);
		return json_decode((string)$response->getBody(), true);
		// return Bot::fromJson((string)$response->getBody(),$this->token);
	}

    private function fetchResponse(
        int $botId,
        string $chatId,
        string $message,
        string $messageType,
        string $channel
    ) {
        $url = self::BASE_URL . "conversation/";
        if ($channel == "whatsapp") {
            $url = $url . "whatsapp/";
        }

        $data = [
            "chat_id" => $chatId,
            "bot_id" => $botId,
            "message" => $message,
            "message_type" => $messageType,

		];
		$res =  $this->httpClient->post($url, [
			"headers" => ["Authorization" => "Bearer " . $this->token],
			"json" => $data
		]);

        return json_decode((string)$res->getBody(), true);
    }


	public function chat(
		int $botId,
		string $chatId /* = str(uuid4()) */,
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



	public function deleteBot($id)
	{
		$url = self::BASE_URL . "chatbot/{$id}";
		$response = $this->httpClient->delete(
			$url,
			["headers" => ["Authorization" => "Bearer " . $this->token],]
		);
		return json_decode((string)$response->getBody(), true);
	}
}
