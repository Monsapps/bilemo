# Bilemo API project
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/1cb9821185054b9c8cc5302f53dd76d8)](https://www.codacy.com/gh/Monsapps/bilemo-api/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Monsapps/bilemo-api&amp;utm_campaign=Badge_Grade)

## Installation

### Installation requirements
*   PHP (>8.0)
*   MySQL (>5.7)
*   Apache (>2.4)
*   Symfony bundle (6.0)
*   Composer (>2.2)
*   OpenSSL (>3.0)

### First step : install bilemo dependencies
In your installation directory open terminal and type
```text
composer install
```

### Second step : Create .env.local file
*   Copy the content of .env file in your new .env.local file
*   Edit .env.local file with your database server info
```text
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```

### Third step : database
On your terminal
*   Create all tables
```text
php bin/console doctrine:migrations:migrate
```
*   Insert products and users samples to your database
```text
php bin/console doctrine:fixtures:load
```

### Fourth step : securization
On your terminal
*   Generate SSL keys
```text
php bin/console lexik:jwt:generate-keypair
```
*   Configure the SSL keys path and passphrase in your .env
```text
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=
```
*   Add those rules to your Apache VirtualHost configuration
```text
SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
```

## Documentation

*   [Authentication](resources/doc/1-authentication.md.md)