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


## current sms service providers :
- yamamah
- 4jawaly
- gateway
- hisms
- msegat
- oursms
- unifonic
- zain







