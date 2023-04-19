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

## Installation and configuration

1. Clone project with `git clone ttps://github.com/PavelKlimovich/BileMo.git`
2. Install dependencies with `cd BileMo && composer install`
3. Rename `.env.local` to `.env` and fill up your database configuration
example config in MYSQL: `DATABASE_URL: `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7.34&charset=utf8"`
4. Create database with: `php bin/console doctrine:database:create` (or with symfony Client: `symfony console doctrine:database:create`)
5. Create schema on database with: `php bin/console doctrine:migrations:migrate -n`
6. Load the fixture with :  `php bin/console doctrine:fixtures:load`
7. Create public and private key for JWT Authentication with : `php bin/console lexik:jwt:generate-keypair`
8. Fill up your passphrase in your .env
example: 

`JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem`

`JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem`

`JWT_PASSPHRASE=fdd719e8855fdf770a5141fd0afb817b`
9. Run the server : `symfony server:start`

## Use API

### Documentation access

To test the API you will need a token

Go to http://127.0.0.1:8000/api/doc


## Admin connection access

"username": `admin@bilemo.fr`,
"password": `password`


<hr>

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


## Use API

### Documentation access

To test the API you will need a token

Go to https://127.0.0.1:8000/api/doc


## Admin connection access

"username": `admin@bilemo.fr`,
"password": `password`

