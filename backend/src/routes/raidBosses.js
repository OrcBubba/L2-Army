const express = require('express');
const raidBossService = require('../services/raidBossService');

const router = express.Router();

router.get('/', async (_req, res) => {
  const data = await raidBossService.getRaidBosses();
  res.json({
    ok: true,
    usingMockData: data.source === 'mock',
    bosses: data.bosses,
  });
});

module.exports = router;
