#!/bin/sh

echo "PULLING SECRETS" 

mkdir /var/secure;

aws s3 cp $S3_BUCKET/settings.php /var/www/Opspot/engine/settings.php
aws s3 cp $S3_BUCKET/var/secure/email-public.key /var/secure/email-public.key
aws s3 cp $S3_BUCKET/var/secure/email-private.key /var/secure/email-private.key
aws s3 cp $S3_BUCKET/apns.pem /var/secure/apns-production.pem

# OAuth
aws s3 cp $S3_BUCKET/var/secure/oauth-priv.key /var/secure/oauth-priv.key
aws s3 cp $S3_BUCKET/var/secure/oauth-pub.key /var/secure/oauth-pub.key

# Sessions
aws s3 cp $S3_BUCKET/var/secure/sessions-priv.key /var/secure/sessions-priv.key
aws s3 cp $S3_BUCKET/var/secure/sessions-pub.key /var/secure/sessions-pub.key

# Cockroach
aws s3 cp $S3_BUCKET/var/secure/cockroachdb /var/secure/cockroachdb --recursive
chown -R www-data /var/secure/cockroachdb/

chmod -xr /var/secure/

# Cockroachdb permissions
chmod -R 600 /var/secure/cockroachdb/

echo "PULLED SECRETS";
