# crowdsec-openbsd-bouncer

Bouncer Crowdsec for OpenBSD

## Setup

Create a table named crowdsec and link it to a local persistent file (empty) :

table <crowdsec> persist file "/etc/pf.conf.d/crowdsec.txt"

Create a .env file in the directory of the bouncer and add the following variables :

```
APIKEY=xxxxx
LAPIURL=https://LAPIHOST:8080
PFTABLE=crowdsec
```
  
## Run

Then manually run the bouncer : php bouncer.php --startup

Finally, set a cron to execute it (without the startup option)

```
* * * * * /usr/bin/php /path/to/crowdsec-openbsd-bouncer/bouncer.php
```
