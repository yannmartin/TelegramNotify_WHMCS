# Telegram Notification Module for WHMCS

## Description

This module integrates Telegram with WHMCS, allowing you to send notifications directly to a Telegram chat. It uses the Telegram Bot API to send messages and can be configured easily through WHMCS.

## Features

- Send notifications to a Telegram chat or channel.
- Supports Markdown formatting for messages.
- Easy to set up and configure.

## Installation

1. **Download the Module:**
   Download the module package from the repository or source.

2. **Upload the Module:**
   Upload the `Telegram` directory to the `/modules/notifications/` directory of your WHMCS installation.

3. **Activate the Module:**
   - Log in to your WHMCS admin panel.
   - Go to **Setup** > **Notification Modules**.
   - Find the "Telegram" module in the list and activate it.

## Configuration

1. **Generate a Telegram Bot Token:**
   - Create a new bot by talking to the [BotFather](https://t.me/botfather) on Telegram.
   - Follow the instructions to get your bot token.

2. **Find Your Chat ID:**
   - Start a chat with your bot or add it to a channel.
   - Send a message to the chat/channel.
   - Use a tool like [this bot](https://t.me/userinfobot) to get your chat ID or use the Telegram API to get updates from your bot.

3. **Configure the Module:**
   - Go to **Setup** > **Notification Modules** in the WHMCS admin panel.
   - Click on "Configure" next to the "Telegram" module.
   - Enter your **Bot Token** and **Chat ID**.

## Usage

Once configured, the module will automatically send notifications to the specified Telegram chat when certain events occur in WHMCS.

### Testing the Connection

To test if the module is working correctly:
- Go to **Setup** > **Notification Modules**.
- Click "Test Connection" next to the "Telegram" module.
- You should receive a message saying "Connected with WHMCS" in your Telegram chat.

### Sending Notifications

The module automatically sends notifications based on the configured settings. You can customize the notifications from the WHMCS admin panel under the **Notification Settings**.

## Troubleshooting

- **No Response from API:** Ensure that your bot token and chat ID are correct and that your server can make outbound requests to the Telegram API.
- **cURL Errors:** Check the server's cURL configuration and verify that outbound HTTPS requests are not blocked.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue if you find any bugs or have suggestions for improvements.

## License

This module is licensed under the [MIT License](LICENSE).
