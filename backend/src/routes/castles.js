const express = require('express');
const castlesService = require('../services/castlesService');

const router = express.Router();

router.get('/', async (_req, res) => {
  const data = await castlesService.getCastles();
  res.json({
    ok: true,
    usingMockData: data.source === 'mock',
    castles: data.castles,
  });
});

module.exports = router;
