ISLE - Inventory System For Lab Equipment
============

UPDATE LOG - Documenting changes to the original Github clone

##auth.php
*Updates to auth mechanism (beginning at ln. 21)
*Added command to query database for entered username
*Created Login and Register functions
*Enabled hashing for registered accounts
*Added password hashing verify function for hashed passwords
*Added email functionality to the registration process

##pagestart.php
*Added login page that directs if session is unverified (ln. 30)
*Added register button (ln. 41)
*Added register button and register variable
*Created registration page (ln. 50)
*Line 20: Cosmetic changes to HTML output to make the login and registration pages make sense
*Line 20: Hide default user if session is not valid

##Vagrantfile
*Updated to support static IP address on Public network

##assetForm.js
*Updated unrestrict to ISLE_ADMIN (ln. 328)
*TODO - allow unrestrict to user who issued restriction or ADMIN 

##/etc/apache2.conf
*Deactivate sendfile to correct issue with non-updating files
*(EnableSendfile Off)

##new-instance.sh
*Created new instance file to facilitate creation of instances
*must be run server-side
*called with bash ./new-instance.sh


**** In case the gateway gets reset, Ubuntu can add the gateway by sending `sudo ip route add default via 192.168.0.1`
Since provisioning the server calls isle-init.sh, this can be added there or /etc/rc.local can have the command added
(but destroying the server will re-write that file)
