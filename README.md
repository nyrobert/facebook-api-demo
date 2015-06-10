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

* Registration
* Login
* Login or register with Facebook
* Connect / disconnect already registered user with Facebook
* Login with the JavaScript SDK, API calls with PHP SDK
* Extend and store access token (for further usage in the server side)
* Re-asking for declined permissions
* App uninstall (with deauthorize callback)

## License

This project is licensed under the terms of the [MIT License (MIT)](LICENSE).
