import {argv} from 'yargs';

export const PORT = argv['PORT'] || 8010;

export const MULTI = argv['MULTI'] || false;

export const TWILIO = {
  ID: argv['TWILIO_ID'] || '' ,
  SECRET: argv['TWILIO_SECRET'] || '' ,
};

export const REDIS = {
  HOST: argv['REDIS_HOST'] || '127.0.0.1',
  PORT: argv['REDIS_PORT'] || 6379
};

export const CASSANDRA = {
  SERVERS: argv['CASSANDRA_SERVERS'] ? [ argv['CASSANDRA_SERVERS'] ] : [ '127.0.0.1' ],
  KEYSPACE: argv['CASSANDRA_KEYSPACE'] || 'opspot'
};

export const JWT_SECRET = argv['JWT_SECRET'];
