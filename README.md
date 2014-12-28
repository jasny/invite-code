Jasny Invite code
=================

[![Build Status](https://secure.travis-ci.org/jasny/db.png?branch=master)](http://travis-ci.org/jasny/invite-code)

This library can be used for requiring invitation codes at registration. This is often the case when an application is
in private beta phase.

### Installation

Jasny DB is registred at packagist as [jasny/invite-code](https://packagist.org/packages/jasnyinvite-codedb) and can be
easily installed using [composer](http://getcomposer.org/).

    composer require jasny/invite-code

### Generation

To create 100 random invitation codes run the following on the command line

```
mkdir invite-codes
cd invite-codes
for i in {1..100}; do
   CODE=$(cat /dev/urandom | env LC_CTYPE=C tr -dc 'A-Z0-9' | fold -w 8 | head -n 1)
   touch $CODE
   echo $CODE
done
```

### Usage

```php
Jasny\InviteCode::setDir('invite-codes');

$invite = new Jasny\InviteCode($_POST['invite']);

if (!$invite->isValid()) {
    echo "Invalid invite code";
    exit();
}

if ($invite->isUsed()) {
    echo "Invite code is already used";
    exit();
}

$invite->useBy($_POST['name']);
```
