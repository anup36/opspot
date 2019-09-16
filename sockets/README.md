Opspot Sockets
=============

Sockets server for Opspot.

### Building

```
# install dependencies
npm install -g ts-node typescript typings
npm install

# build from typescript to js
tsc

# run the container from main
docker-compose up sockets
```

### Note

By default it starts at 3030 , on server , nginx is configured to handle this for /socket.io/ at same https port
