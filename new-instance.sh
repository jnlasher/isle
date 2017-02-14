#!/bin/bash
# Creates a new instance based on new instance name
echo Enter a new instance name:

read new_instance

cp -r /var/www/instances/myinstance/. /var/www/instances/$new_instance  # Copy original instance to new directory
find /var/www/instances/$new_instance -type f -name "*.log" -delete	# Delete all log files

# Update the data.sql and init.sql files with the new instance name
sed -i'' -e 's/\myinstance/'$new_instance'/g' /var/www/instances/$new_instance/data.sql
sed -i'' -e 's/\myinstance/'$new_instance'/g' /var/www/instances/$new_instance/init.sql

# Find all file names that contain myinstance and replace them with the new instance name
find /var/www/instances/$new_instance -type f -name "*myinstance*" | while read F; do 
	nf="$(echo ${F} | sed -e 's/\myinstance/'$new_instance'/')" ; 
	mv "${F}" "${nf}" ; 
done

# Copy over the webroot information and remove all files, excluding .htaccess files
cp -r /var/www/webroot/myinstance/. /var/www/webroot/$new_instance
find /var/www/webroot/$new_instance ! -name ".htaccess" -type f -exec rm {} \;

# Append to isle-init.sh with necessary database, apache configuration, and log instructions
# DO NOT edit the initial comments in the isle-init.sh file or it will cause the below functions not to work
sed -i "/Initialize db tables/ a mysql -uroot -p'root' -h localhost isle_dev < \"/var/www/instances/$new_instance/init.sql\"\nmysql -uroot -p'root' -h localhost isle_dev < \"/var/www/instances/$new_instance/data.sql\"" /var/www/isle-init.sh

sed -i "/Create apache conf/ a cp /var/www/instances/$new_instance/isle.local.$new_instance.conf /etc/apache2/sites-available/isle.local.$new_instance.conf\na2ensite isle.local.$new_instance" /var/www/isle-init.sh

sed -i "/Create logrotate conf/ a cat <<EOT >> /etc/logrotate.d/isle-foobar\n/var/www/instances/$new_instance/logs/*.log {\n\tyearly\n\tmaxsize 2M\n\trotate 5\n\tnotifempty\n\tmissingok\n\tsu vagrant vagrant\n}\nEOT" /var/www/isle-init.sh


sed -i'' -e 's/\myinstance/'$new_instance'/g' /var/www/instances/$new_instance/isle.local.$new_instance.conf
