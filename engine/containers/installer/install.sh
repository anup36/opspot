#!/bin/sh

echo "Step 1 INSTALLING OPSPOT"

cd /var/www/Opspot/engine
sh /var/www/Opspot/engine/tools/setup.sh

echo "Step 2 Setting up Keys"

php /var/www/Opspot/engine/cli.php install keys

echo "Step 3 Running install with ops creds"

php /var/www/Opspot/engine/cli.php install \
    --domain=ops.doesntexist.com \
    --username=opspot \
    --password="Pa\$\$w0rd" \
    --email=opspot@opspot.com \
    --email-private-key=/.dev/opspot.pem \
    --email-public-key=/.dev/opspot.pub \
    --phone-number-private-key=/.dev/opspot.pem \
    --phone-number-public-key=/.dev/opspot.pub \
    --cassandra-server=cassandra \
    --socket-server-uri=https://ops.doesntexist.com \
    --twilio-account-sid=AC96ea890adb1a883eede2c87401e1015c \
    --twilio-auth-token=7b472efc9c42e0e3bdc480b599dad997 \
    --twilio-from="+14245437007" \
    --mongodb-servers="mongo" \
    --mongodb-db="opspot" \
    --kickbox-secret="test_e66c58a9119f17ad0b44ad9a9ccbad5e214fd1fae187aa1682923c5487b5c370"
