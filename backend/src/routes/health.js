const express = require('express');
const db = require('../db');
const config = require('../config');

const router = express.Router();

router.get('/', async (_req, res) => {
  const connected = await db.pingDatabase();

  res.json({
    ok: true,
    backend: 'ok',
    database: connected ? 'connected' : 'disconnected',
    usingMockData: !connected,
    serverName: config.serverName,
    chronicle: 'L2J Mobius CT 2.6 HighFive',
  });
});

module.exports = router;
