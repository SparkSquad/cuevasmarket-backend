require('dotenv').config();

const CuevasMarketAPI = require('./Server');

const server = new CuevasMarketAPI();

server.start();
