# simple-passport
Simple passport, is a complete implementation of laravel/passport package, containing authentication, forgot passwort, recovery password, ... and all what you need to start your application that needs a complete authentication system

----------

**Installation**
----------------

First of all, you need to install the package into your laravel project, by running the below command:

    composer require heloufir/simple-passport

The package depend on the **laravel/passport** package, as mentionned below:

| Dependencies  | Version |
| ------------- | ------- |
| laravel/passport  | ^7.2 |

**Configuration**
-----------------

1/ After installing the package, you need to publish it, by running the command:

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

    <?php
    
    namespace App;
    
    use Laravel\Passport\HasApiTokens;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    
    class User extends Authenticatable
    {
        use HasApiTokens, Notifiable;
    }

It's done for the **laravel/passport** configuration, the rest of the configuration is done in the **heloufir/simple-passport** side.

> So from here you are ready to use **laravel/passport** and **heloufir/simple-passport** packages.

**Prerequisites**
-----------------

Before using the package, here is the main files that you need to know:

    ├───config
    │   ├───app.php
    │   └───...
    │   └───simple-passport.php <<<< 1
    └───resources
        ├───js
        ├───lang
        │   └───en
        │   └───...
        │   └───vendor
        │   │   └───simple-passport
        │   │   │   └───en
        │   │   │    │   └───forgot-password.php <<<< 2
        │   │   │    │   └───recover-password.php <<<< 3
        │   │   └───...
        ├───views
        │   └───simple-passport
        │   │   └───forgot-password.blade.php <<<< 4
        │   │   └───recover-password.blade.php <<<< 5
        └───...

1/ **config/simple-passport.php**

In this file you will find the 3 constants :

 - *revocer_url*: This is the **url** that will be accessible from the forgot password button
 - *mail_from*: This is the **mail** that will be displayed as the sender of the forgot password and the recover password mails
 - *mail_from_name*: This is the **name** that will be displayed as the sender of the forgot password and the recover password mails

> Take a look at this file to know more information about how to this constants and how to override them.

2/ **resources/lang/vendor/simple-passport/en/forgot-password.php**

This file contains the forgot password mail translations.

3/ **resources/lang/vendor/simple-passport/en/recover-password.php**

This file contains the recover password mail translations.

4/ **resources/views/simple-passport/forgot-password.php**

This is the mail template for the forgot passport mail.

5/ **resources/views/simple-passport/recover-password.php**

This is the mail template for the recover passport mail.

> You are free to customize all this above files as you want.

**How it works**
-----------------

The **heloufir/simple-passport** package add 2 routes to your **laravel/passport** package implementation.

Run `php artisan route:list` command to show your application routes:

| Method |       URI              |       Name                       |
| ------ | ---------------------- | -------------------------------- |
| POST   | oauth/forgot-password  | simple-passport.password.forgot  |
| PUT    | oauth/recover-password/{token} | simple-passport.password.recover |

The first route **oauth/forgot-password** let you run the forgot password system, here is an exemple:

**URL** : [POST] http://localhost:8000/oauth/forgot-password

**BODY**: 

If you are using the **email** field of your **User** model, the request body needs to be like below:

    {
    	"email": "j.doe@domain.com"
    }

If you are using the **username** field of your **User** model, the request body needs to be like below:

    {
    	"username": "j.doe"
    }

So, whatever field name you are using you need to send a body with the following format:

    {
    	"field_name": "field_value"
    }

By default, the **simple-passport** package use the **email** field, if you want to use another, you need to add the following function to your **User** model:

    public function getSimplePassportAttribute()
    {
        return 'username';
    }

> The example above, tell the **simple-passport** package to use the **username** field of the **User** model

**RESULTS**:

1/ If the **field_value** cannot be found on the database

    {
        "mail_sent": false,
        "errors": [
            "The selected user is invalid."
        ]
    }

2/ If the **field_value** was found on the database

    {
        "mail_sent": true,
        "errors": []
    }

 - *mail_sent*: This field is a **boolean**, if **true** that means the mail is successfully sent to the user, if **false** the mail is not sent, because there is an error(s) in the request body.
 - *errors*: This field is an array of errors found in the request body.

> The forgot mail format

![The forgot password mail sent to the user](https://lh3.googleusercontent.com/-X_kW5zD8Myc/XG7Hw3JvxLI/AAAAAAAAEqE/HfPsfsgBF5kb0-3Rx0Iy8DgNCKerYZ20ACLcBGAs/s0/Forgot+password+-+mail.PNG "Forgot password - mail.PNG")


----------

The second route **oauth/recover-password/{token}** let you run the recover password system, here is an exemple:

> The **{token}** is the value generated by the forgot password function, that is stored in the **password_token** field of the **User** model.

Here is how to use this route: 

**URL** : [PUT] http://localhost:8000/oauth/recover-password/g1OXnL65mkdZyUXN1liE0IzGNtFf74thcZzFtaGpb1a4MJARNCEnhk8fqSDlYgV5ggfMWE1NvurLxoT1XqCERb1xPbRXpcriYgQh 

**BODY**: 

    {
    	"password": "secret",
    	"password_confirmation": "secret"
    }

**RESULTS**:

1/ If the request body contains errors:

    {
        "password_recovered": false,
        "errors": [
            "The password field is required."
        ]
    }

2/ If the request body does not contains errors:

    {
        "password_recovered": true,
        "errors": []
    }

 - *password_recovered*: This field is a **boolean**, if **true** that means the password is successfully updated, if **false** the password is not updated, because there is error(s) in the request body. 
 - *errors*: This field is an array of errors found in the request body.

An email is sent to the user after password was updated:

![The recover password mail sent to the user](https://lh3.googleusercontent.com/-3kxxrhTTV1o/XG7Jq23MkLI/AAAAAAAAEqU/IFj7wm_7pasjr3PKEFNbsq-6uhg-H7FeACLcBGAs/s0/Recover+password+-+mail.PNG "Recover password - mail.PNG")


