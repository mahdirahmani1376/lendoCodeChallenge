# lendoCodeChallenge

## project description
this is an implementation of a wallet system where users can store credit_transactions on their 
wallet and spent their money
## Setup
- clone the repo and run the following commands
```sh
cp .env .env.example
composer install
php artisan migrate --seed
php artisan test
```

## Project description
all transactions on application are stored in as credit_transaction
a user can only have one wallet associated with it
and the wallet balance will be calculated based on its credit_transactions
if there is a withdrwal there will be a negative amount transaction
if there is a deposit there will be a positive amount transaction
## Tests
there is also feature tests available that test diffrent parts of the application
to ensure full functionality.
for security perposes the user_id field does not come in request body, but it gets from authenticated logged in users, so a user can not change another users wallet
## Futher developments
on productions environment the deposit action should be done after a user successfully paid an invoice via payment gateway but here for testing purposes i did not cover it.
also the withdraw part should include a third party API like bankgateways to send withdraw amount to user bank account


