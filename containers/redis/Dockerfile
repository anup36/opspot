FROM redis:4.0-alpine

COPY master.conf /usr/local/etc/redis/master.conf
#COPY slave.conf /usr/local/etc/redis/slave.conf
COPY docker-healthcheck /usr/local/bin/

HEALTHCHECK CMD ["docker-healthcheck"]

CMD [ "redis-server", "/usr/local/etc/redis/master.conf" ]
