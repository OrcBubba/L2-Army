const express = require('express');
const rankingsService = require('../services/rankingsService');

const router = express.Router();

router.get('/pvp', async (_req, res) => {
  const data = await rankingsService.getTopPvp(10);
  res.json({
    ok: true,
    usingMockData: data.source === 'mock',
    players: data.players,
  });
});

router.get('/pk', async (_req, res) => {
  const data = await rankingsService.getTopPk(10);
  res.json({
    ok: true,
    usingMockData: data.source === 'mock',
    players: data.players,
  });
});

module.exports = router;
