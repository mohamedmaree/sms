# sms
## Installation

You can install the package via [Composer](https://getcomposer.org).

```bash
composer require maree/sms
```

## Usage

```php
use Maree\Sms\SMS;

SMS::send($sms_provider = 'yamamah',$user_name='test',$password='1234',$sender_name='Maree App',$phone='0020*********', $msg='sms sent successfuly');  


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







