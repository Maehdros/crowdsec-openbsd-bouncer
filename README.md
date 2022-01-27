# crowdsec-openbsd-bouncer

Bouncer Crowdsec for OpenBSD

## OpenBSD Requirements

The following packages are required :

- php-cli
- php-curl
- php-zip

Use the PKG_PATH variable with a CDN : 

For example : 

```
EXPORT PKG_PATH=https://ftp.eu.openbsd.org/pub/OpenBSD/6.9/packages/amd64/
pkg_add php-8.0.3 php-curl-8.0.3 php-zip-8.0.3
```

The specific version may vary to suit the current version

## Setup PF

Create a table named crowdsec and link it to a local persistent file (empty) :

table <crowdsec> persist file "/etc/pf.conf.d/crowdsec.txt"

## Setup for the script

You will need to run composer install to get the dependencies (not much) 

```
composer install
```


Create a .env file in the directory of the bouncer and add the following variables :

```
APIKEY=xxxxx
LAPIURL=https://LAPIHOST:8080
PFTABLE=crowdsec
```
  
## Run

Then manually run the bouncer : 

```
* * * * * /usr/bin/php /path/to/crowdsec-openbsd-bouncer/bouncer.php --startup
```


Finally, set a cron to execute it (without the startup option)

```
* * * * * /usr/bin/php /path/to/crowdsec-openbsd-bouncer/bouncer.php
```
