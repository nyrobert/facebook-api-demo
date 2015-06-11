# Facebook API demo

## Requirements

* [PHP](http://php.net) >=5.4
* [MySQL](http://www.mysql.com)
* [Bower](http://bower.io)
* [Facebook App](https://developers.facebook.com/apps)

## Installation

1. Install Composer in the project directory
  
  ```shell
  curl -sS https://getcomposer.org/installer | php
  ```
2. Download PHP dependencies via Composer
  
  ```shell
  php composer.phar install
  ```
3. Download front-end dependencies via Bower

  ```shell
  bower install
  ```
4. Set required environment variables

  * `MYSQL_HOST`
  * `MYSQL_USERNAME`
  * `MYSQL_PASSWORD`
  * `DB_NAME`
  * `FACEBOOK_APP_ID`
  * `FACEBOOK_APP_SECRET`

## Features

This demo app was built for testing the latest Facebook API features, best
practices and recommendations. The app has standard login and registration
primarily for basic users. The app also offers login and registration for
Facebook users too. In this case a basic users will be created in the background
(with generated password). The app has Facebook connect feature which could be
useful for basic users. The login happens on the client side with JavaScript
SDK and re-asks for declined permissions. The server (PHP SDK) makes direct
calls to Graph API with long-term access token (offline posting). The app
uninstall on Facebook (with deauthorize callback) was also implemented in this
app.

*

## License

This project is licensed under the terms of the [MIT License (MIT)](LICENSE).
