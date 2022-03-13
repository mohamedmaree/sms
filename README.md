# sms
## Installation

You can install the package via [Composer](https://getcomposer.org).

```bash
composer require maree/sms
```
Publish your sms config file with

```bash
php artisan vendor:publish --provider="maree\sms\SMSServiceProvider" --tag="sms"
```
## Usage

```php
use maree\sms\SMS;

SMS::send($phone='0020*********', $msg='sms sent successfuly');  


```
prefer to use jobs in sending many sms

## current sms service providers :
- yamamah
- 4jawaly
- hisms
- msegat
- oursms
- unifonic
- zain







