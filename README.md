# BileMo

API Rest project 7 -
Bilemo Company supplies to their customers a catalogue of mobile phone via an Rest API
## Technologies
<ul>
 <li>PHP 8.1.2</li>
 <li>Symfony 6.2</li> 
 <li>MySQL 5.7.34</li> 
</ul>

<hr>

## Installation

### step1: **Copy the link** on GitHub and **clone it** on your local repository
https://github.com/PavelKlimovich/BileMo.git

**Clone** the repository to your local path. Use command `git clone`
inside your directory:  `cd my-project`

**Open** your **terminal** and **run**: `composer install`


In server MySQL

**Database configuration**
**Open file** `.env` and write your configuration **username** and **password** 

> DATABASE_URL: `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7.34&charset=utf8"`
**Create database** with: `php bin/console doctrine:database:create` (or with symfony Client: `symfony console doctrine:database:create`)
**Create table on database with: `php bin/console doctrine:schema:update -f`

**Run the migration**: `php bin/console doctrine:migrations:migrate`

**Run** the server : `symfony server:start`
<hr>

### Add test data
**Load the fixture** with :  `php bin/console doctrine:fixtures:load`
<hr>

#### Generate keys

Install ***LexikJWT*** : `composer require lexik/jwt-authentication-bundle` 

#### Create public and private key 

`php bin/console lexik:jwt:generate-keypair`

(install ***OpenSSL*** if needed check official documentation)

#### In your .env.local

#### Fill up your passphrase :

### > lexik/jwt-authentication-bundle ###

 >JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem`
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem 
 JWT_PASSPHRASE=fdd719e8855fdf770a5141fd0afb817b`
### > lexik/jwt-authentication-bundle ###
<hr>

To test the API you will need a token

Go to https://127.0.0.1:8000/api/doc

add :

"username":"admin@pommemail.com",
"password":"123456"

## Use API

### Documentation access

> API Documentation :  http://yourAdress.domain.fr/doc/api