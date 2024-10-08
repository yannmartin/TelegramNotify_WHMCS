<?php

namespace WHMCS\Module\Notification\Telegram;

use WHMCS\Config\Setting;
use WHMCS\Exception;
use WHMCS\Module\Notification\DescriptionTrait;
use WHMCS\Module\Contracts\NotificationModuleInterface;
use WHMCS\Notification\Contracts\NotificationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Telegram notification implementation for WHMCS.
 */
class Telegram implements NotificationModuleInterface
{
    use DescriptionTrait;

    const ERROR_NO_RESPONSE = 'No response received from API';
    const ERROR_REQUEST = 'HTTP request error: ';
    const ERROR_TELEGRAM_API = 'Telegram API error: ';

    /**
     * Constructor sets display name and logo for the module.
     */
    public function __construct()
    {
        $this->setDisplayName('Telegram')
             ->setLogoFileName('logo.png');
    }

    /**
     * Define settings for the Telegram notification module.
     * 
     * @return array Configuration settings
     */
    public function settings()
    {
        return [
            'botToken' => [
                'FriendlyName' => 'Token',
                'Type' => 'text',
                'Description' => 'Token of the Telegram Bot.',
                'Placeholder' => 'Enter your bot token here',
            ],
            'botChatID' => [
                'FriendlyName' => 'Chat ID',
                'Type' => 'text',
                'Description' => 'ChatID of the user/channel.',
                'Placeholder' => 'Enter your chat ID here',
            ],
        ];
    }

    /**
     * Test connection to the Telegram API with provided settings.
     * 
     * @param array $settings Module settings
     * @throws Exception If the API does not respond
     */
    public function testConnection($settings)
    {
        $this->validateSettings($settings);
        $response = $this->sendMessage($settings, "Connected with WHMCS");
        if (!$response) { 
            throw new Exception(self::ERROR_NO_RESPONSE);
        }
    }

    /**
     * Send a notification message via Telegram.
     * 
     * @param NotificationInterface $notification Notification object
     * @param array $moduleSettings Module settings
     * @param array $notificationSettings Additional notification settings
     * @throws Exception If the API does not respond
     */
    public function sendNotification(NotificationInterface $notification, $moduleSettings, $notificationSettings)
    {
        $this->validateSettings($moduleSettings);
        $messageContent = "*" . $notification->getTitle() . "*\n\n" . $notification->getMessage() . "\n\n[Open »](" . $notification->getUrl() . ")";
        $this->sendMessage($moduleSettings, $messageContent, "Markdown");
    }

    /**
     * Returns additional notification settings.
     * 
     * @return array Notification settings
     */
    public function notificationSettings()
    {
        return [];  // Define notification settings if applicable
    }

    /**
     * Get dynamic fields based on the field name and settings.
     * 
     * @param string $fieldName Name of the field
     * @param array $settings Settings array
     * @return mixed Field value or structure
     */
    public function getDynamicField($fieldName, $settings)
    {
        // Implement logic to retrieve dynamic fields based on input settings
        return [];  // Return an appropriate structure or value based on $fieldName
    }

    /**
     * Helper function to send a message using the Telegram API via GuzzleHTTP.
     *
     * @param array $settings Bot settings
     * @param string $message Message to send
     * @param string $parseMode (optional) Specify the parse mode (default: none)
     * @return mixed Response from the Telegram API
     * @throws Exception If there is a GuzzleHTTP error or Telegram API error
     */
    private function sendMessage($settings, $message, $parseMode = "")
    {
        $this->validateSettings($settings);
        $botToken = $settings['botToken'];
        $botChatID = $settings['botChatID'];
        $url = "https://api.telegram.org/bot$botToken/sendMessage";

        $client = new Client();

        $postData = [
            'chat_id' => $botChatID,
            'text' => $message,
            'parse_mode' => $parseMode
        ];

        try {
            $response = $client->post($url, [
                'form_params' => $postData,
                'verify' => true, // SSL verification in production
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (isset($responseData['ok']) && $responseData['ok'] === false) {
                throw new Exception(self::ERROR_TELEGRAM_API . $responseData['description']);
            }

            return $responseData;
        } catch (RequestException $e) {
            throw new Exception(self::ERROR_REQUEST . $e->getMessage());
        }
    }

    /**
     * Validate settings to ensure they are present and valid.
     * 
     * @param array $settings Settings array
     * @throws Exception If required settings are missing
     */
    private function validateSettings($settings)
    {
        if (empty($settings['botToken']) || empty($settings['botChatID'])) {
            throw new Exception('Bot Token and Chat ID are required.');
        }
    }
}
