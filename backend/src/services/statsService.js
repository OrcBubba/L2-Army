const db = require('../db');
const config = require('../config');
const mockData = require('./mockData');

/**
 * Server stats from L2J Mobius CT 2.6 HighFive.
 *
 * characters.online     1 = logged in (same column name as CT_0 Interlude)
 * characters.level      High Five cap is 85
 * characters.accesslevel / deletetime  hide GMs and pending deletions
 */

async function getStats() {
  const rates = config.rates;
  const connected = await db.pingDatabase();

  if (!connected) {
    const mock = mockData.getMockStats(rates, config.serverName, config.maxOnline);
    return { source: 'mock', ...mock };
  }

  const onlineResult = await db.query(
    `SELECT COUNT(*) AS total
     FROM characters
     WHERE online = 1
       AND accesslevel = 0
       AND deletetime = 0`
  );

  const totalResult = await db.query(
    `SELECT COUNT(*) AS total
     FROM characters
     WHERE deletetime = 0`
  );

  const maxLevelResult = await db.query(
    `SELECT COUNT(*) AS total
     FROM characters
     WHERE level = 85
       AND accesslevel = 0
       AND deletetime = 0`
  );

  if (!onlineResult.ok || !totalResult.ok || !maxLevelResult.ok) {
    const mock = mockData.getMockStats(rates, config.serverName, config.maxOnline);
    return { source: 'mock', ...mock };
  }

  return {
    source: 'database',
    usingMockData: false,
    onlinePlayers: Number(onlineResult.rows[0].total) || 0,
    totalCharacters: Number(totalResult.rows[0].total) || 0,
    maxLevelCharacters: Number(maxLevelResult.rows[0].total) || 0,
    serverStatus: 'online',
    serverName: config.serverName,
    maxOnline: config.maxOnline,
    rates,
  };
}

module.exports = {
  getStats,
};
