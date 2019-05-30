<h3>Due to a time constraint, unfortunately this repository is no longer maintained.</h3>
<br/>

# Installation
1/ Install Laravel passport official package, explained in the documentation [here](https://laravel.com/docs/5.8/passport#installation)
    Then install the simple-passport package via composer

    composer require heloufir/simple-passport

# Configuration
1/ After installing the package, you need to publish it, by running the command:

To do it, simply execute the following command 

    php artisan vendor:publish --provider=Heloufir\SimplePassport\SimplePassportServiceProvider

Or follow the steps below:

    php artisan vendor:publish

You will be asked to choose what **tag** you want to publish (see the image below)

![Publish heloufir/simple-passport package](https://lh3.googleusercontent.com/-gmOs-xKPf9I/XG65d1UKb0I/AAAAAAAAEpk/SUOSSF-Mj7AwydQWc8HkvIIIluGg5pXmwCLcBGAs/s0/Publish+simple-passport.png "Publish simple-passport.png")

Next you just need to choose the tag number to publish and tap **Enter**.

> For my case I choosed **3** then **Enter**

![Package heloufir/simple-passport published](https://lh3.googleusercontent.com/-iUEq5k_GwhM/XG66QrxTV9I/AAAAAAAAEp0/gtScqMDmGy0BamsJe9qik3PdCA3JF7-SACLcBGAs/s0/Package+published.png "Package published.png")

This is the message you need to see if everything is OK.

2/ Now you need to configure the **laravel/passport** package, do it by following the below steps:

Execute the migration command: 

    php artisan migrate

This will show you the following message:

    Migrating: 2019_04_06_105400_create_simple_tokens_table
    Migrated:  2019_04_06_105400_create_simple_tokens_table

After running this command, add the **Heloufir\SimplePassport\Helpers\CanResetPassword** trait to your **YOUR_NAMESPACE\User** model. This trait will provide a few helper methods:

```php
<?php

namespace App;
  
use Heloufir\SimplePassport\Helpers\CanResetPassword;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
    
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, CanResetPassword;
}
```

> You can override two methods **getEmailField** and  **getPasswordField** for providing the names of the fields

3/ A configuration file **config/simple-passport.php** will be published. its containing the information such as the user model and more things.
```php
return [

    /*
    |--------------------------------------------------------------------------
    | Recover url
    |--------------------------------------------------------------------------
    |
    | This value is the recover password url, where the user will be redirected
    | after he clicked on the forgot password email button.
    | >> To customize this value please set a new variable into the application
    |    .env file with the following name: "SP_RECOVER_URL"
    |
    */
    'recover_url' => env('SP_RECOVER_URL', 'http://localhost:4200/auth/recover/'),

    /*
    |--------------------------------------------------------------------------
    | Mail sender
    |--------------------------------------------------------------------------
    |
    | This value is the address of the sender, which be displayed in the mail
    | sent to the user after he request to recover his password or after his
    | password is recovered
    | >> To customize this value please set a new variable into the application
    |    .env file with the following name: "SP_MAIL_FROM"
    |
    */
    'mail_from' => env('SP_MAIL_FROM', 'noreply@application.com'),

    /*
    |--------------------------------------------------------------------------
    | Recover url
    |--------------------------------------------------------------------------
    |
    | This value is the name of the send, which be displayed in the mail
    | sent to the user after he request to recover his password or after his
    | password is recovered
    | >> To customize this value please set a new variable into the application
    |    .env file with the following name: "SP_MAIL_FROM_NAME"
    |
    */
    'mail_from_name' => env('SP_MAIL_FROM_NAME', 'Application'),

    /*
    |--------------------------------------------------------------------------
    | model
    |--------------------------------------------------------------------------
    |
    | The model that can use simple-passport features
    |
    */

    'model' => \App\User::class,

    /*
    |--------------------------------------------------------------------------
    | after_seconds
    |--------------------------------------------------------------------------
    |
    | How many seconds before dispatch the jobs to send mails
    |
    */

    'after_seconds' => 10,
];

```


It's done for the **laravel/passport** configuration, the rest of the configuration is done in the **heloufir/simple-passport** side.

> So from here you are ready to use **laravel/passport** and **heloufir/simple-passport** packages.

# Usage
## Generate token
1/ You can generate a token for an existing user via a POST HTTP request to http://localhost/oauth/forgot-password containing an **email** field.

2/ You can recover the password for an existing user via a PUT HTTP request to http://localhost/oauth/recover-password/some-random-token containing an **email** and new **password** field.
