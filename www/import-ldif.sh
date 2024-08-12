#!/bin/bash

# Wait for the MySQL server to be ready
echo "Waiting for MySQL server to start..."
while ! nc -z mysql-container 3306; do
  sleep 1
done

# Generate the users.ldif file
echo "Generating users.ldif..."
php /var/www/html/export_users_to_ldap.php

# Wait for the LDAP server to be ready
# echo "Waiting for LDAP server to start..."
# while ! nc -z ldap-server 389; do
#   sleep 1
# done

# # Import the LDIF file into LDAP
# echo "Importing users.ldif into LDAP..."
# ldapadd -x -D "cn=admin,dc=mycompany,dc=com" -w admin -f /var/www/html/users.ldif

# echo "Import complete."

# Start Apache in the foreground to keep the container running
apache2-foreground
