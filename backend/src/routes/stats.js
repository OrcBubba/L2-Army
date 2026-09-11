const express = require('express');
const statsService = require('../services/statsService');
const config = require('../config');

const router = express.Router();

router.get('/', async (_req, res) => {
  const stats = await statsService.getStats();
  res.json({
    ok: true,
    usingMockData: stats.usingMockData,
    onlinePlayers: stats.onlinePlayers,
    totalCharacters: stats.totalCharacters,
    maxLevelCharacters: stats.maxLevelCharacters,
    serverStatus: stats.serverStatus,
    serverName: stats.serverName,
    maxOnline: stats.maxOnline,
    rates: stats.rates,
    discordUrl: config.discordUrl,
    downloadUrl: config.downloadUrl,
  });
});

module.exports = router;
