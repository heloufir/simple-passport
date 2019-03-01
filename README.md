# Installation

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

    Migration table created successfully.
    Migrating: 2014_10_12_000000_create_users_table
    Migrated:  2014_10_12_000000_create_users_table
    Migrating: 2014_10_12_100000_create_password_resets_table
    Migrated:  2014_10_12_100000_create_password_resets_table
    Migrating: 2016_06_01_000001_create_oauth_auth_codes_table
    Migrated:  2016_06_01_000001_create_oauth_auth_codes_table
    Migrating: 2016_06_01_000002_create_oauth_access_tokens_table
    Migrated:  2016_06_01_000002_create_oauth_access_tokens_table
    Migrating: 2016_06_01_000003_create_oauth_refresh_tokens_table
    Migrated:  2016_06_01_000003_create_oauth_refresh_tokens_table
    Migrating: 2016_06_01_000004_create_oauth_clients_table
    Migrated:  2016_06_01_000004_create_oauth_clients_table
    Migrating: 2016_06_01_000005_create_oauth_personal_access_clients_table
    Migrated:  2016_06_01_000005_create_oauth_personal_access_clients_table
    Migrating: 2019_02_21_094018_add_password_token_to_users_table
    Migrated:  2019_02_21_094018_add_password_token_to_users_table


**Of course, you can see less/more migrations if necessary**.

Next, you should run the `passport:install` command. This command will create the encryption keys needed to generate secure access tokens. In addition, the command will create "personal access" and "password grant" clients which will be used to generate access tokens:

    php artisan passport:install

After running this command, add the **Laravel\Passport\HasApiTokens** trait to your **YOUR_NAMESPACE\User** model. This trait will provide a few helper methods to your model which allow you to inspect the authenticated user's token and scopes:

```php
<?php

namespace App;
    
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
    
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```

> Don't forget to update the guards in your **auth.php** configuration file for the `api` to **passport**

```php
'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport', // <- Here
            'provider' => 'users',
        ],
    ],
```


It's done for the **laravel/passport** configuration, the rest of the configuration is done in the **heloufir/simple-passport** side.

> So from here you are ready to use **laravel/passport** and **heloufir/simple-passport** packages.
