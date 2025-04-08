# Redirector Plug-in

The Redirector Plug-in is a lightweight PHP-based solution for handling URL redirections in a WordPress environment outside the wordpress root folder. It ensures that users are redirected to the correct pages or posts based on their requests, even when the requested URLs are not directly accessible.

## Features

- **Dynamic Redirection**: Redirects users to the appropriate WordPress posts or pages based on slugs or similar titles.
- **Error Handling**: Gracefully handles non-PHP URLs and prevents redirect loops.
- **Database Integration**: Connects to the WordPress database to fetch post and page details.
- **Lightweight**: No external libraries or dependencies required.

## Installation

1. Clone or download this repository.
2. Place the `redirector.php` file in the root directory of your WordPress installation or a custom directory.
3. Configure your `.htaccess` file to include custom error handling if needed.
4. Ensure your database credentials are set as environment variables:
   - `DB_HOST`
   - `DB_USERNAME`
   - `DB_PASSWORD`
   - `DB_DATABASE`

## Usage

1. Place the `redirector.php` file in the desired directory.
2. Access the plugin by navigating to URLs that require redirection.
3. The plugin will automatically handle redirections based on the logic defined in `redirector.php`.

## File Structure

```
/var/www/html/              → Root of site.com
│
├── db_config.php.php       → This file handles database connection details from environment variables
├── redirector.php          → This file handles redirection logic
├── .htaccess               → Add custom error handling here
├── .env                    → Add database connection details
└── wordpress/              → Your WordPress installed here (site.com/wordpress)
```

## Example `.htaccess` File

To ensure proper redirection, you can use the following example `.htaccess` file:

```
# Redirect all requests to redirector.php
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /redirector.php [L]
```

OR

```
# Redirect error requests to redirector.php
ErrorDocument 404 /redirector.php
ErrorDocument 403 /redirector.php
ErrorDocument 410 /redirector.php
```

## Environment Variables

This plugin uses PHP's built-in `getenv()` function to retrieve environment variables. Ensure your environment variables are properly set in your server configuration or `.env` file.

### Example `.env` File

If your server supports `.env` files, you can create one in the root directory with the following content:

```
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=yourpassword
DB_DATABASE=wordpress

# Your website WordPress root folder
BASE_PATH=/wordpress/
```

### Server Configuration

If your server does not support `.env` files, you can set environment variables directly in your server configuration:

- **Apache**: Use the `SetEnv` directive in your `.htaccess` file:
  ```
  SetEnv DB_HOST localhost
  SetEnv DB_USERNAME root
  SetEnv DB_PASSWORD yourpassword
  SetEnv DB_DATABASE wordpress
  ```

- **Nginx**: Set environment variables in your server block.

## Contributing

Contributions are welcome! Feel free to open issues or submit pull requests to improve the plugin.

## License

This project is licensed under the MIT License. See the LICENSE file for details.
