#reCAPTCHA
#### reCAPTCHA plugin for atk4

## WHAT IS reCAPTCHA
[http://www.google.com/recaptcha/learnmore](http://www.google.com/recaptcha/learnmore)

## Get API Keys for your project
[https://www.google.com/recaptcha/admin/create](https://www.google.com/recaptcha/admin/create)

[http://www.google.com/recaptcha/whyrecaptcha](http://www.google.com/recaptcha/whyrecaptcha)

## Google Developer's Guide
[https://developers.google.com/recaptcha/intro](https://developers.google.com/recaptcha/intro)

## Using reCAPTCHA with PHP
[https://developers.google.com/recaptcha/docs/php](https://developers.google.com/recaptcha/docs/php)

## ATK4 Usage

Add to config of your project API keys (it should work fine on localhost with no keys)
    
    $config['reCAPTCHA']['publickey']
    $config['reCAPTCHA']['privatekey']

In Form

    $this->add('x_recaptcha/Controller_ReCaptcha');

After Form submition

    if (!$this->recaptcha->isCaptchaOk) {
        $this->js(null,'Recaptcha.reload()')->univ()->alert('wrong')->execute();
    }