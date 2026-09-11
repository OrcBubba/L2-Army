const db = require('../db');
const mockData = require('./mockData');
const { getBossName, getBossLevel, compareBossLevel } = require('./bossNames');

/**
 * Raid / epic status from L2J Mobius CT 2.6 HighFive.
 *
 * grandboss_data.status
 *   0 = DORMANT, 1 = WAITING, 2 = FIGHTING, 3 = DEAD
 *   (same status values as CT_0 Interlude GrandBossManager)
 * grandboss_data.respawn_time / raidboss_spawnlist.respawn_time
 *   Unix milliseconds; > now means the boss is still dead and waiting to spawn
 */

const GRAND_DEAD_STATUS = 3;

function isDead(respawnTime, status) {
  const respawn = Number(respawnTime) || 0;
  if (respawn > Date.now()) {
    return true;
  }
  if (status === undefined || status === null) {
    return false;
  }
  return Number(status) === GRAND_DEAD_STATUS;
}

function mapBoss(row, type) {
  const dead = isDead(row.respawn_time, row.status);
  return {
    bossId: row.boss_id,
    name: getBossName(row.boss_id),
    level: getBossLevel(row.boss_id),
    type,
    alive: !dead,
    status: dead ? 'dead' : 'alive',
    respawnTime: Number(row.respawn_time) || 0,
  };
}

async function getRaidBosses() {
  const grandSql = `
    SELECT boss_id, respawn_time, status
    FROM grandboss_data
    ORDER BY boss_id ASC
  `;
  const raidSql = `
    SELECT boss_id, respawn_time
    FROM raidboss_spawnlist
    ORDER BY boss_id ASC
  `;

  const grandResult = await db.query(grandSql);
  const raidResult = await db.query(raidSql);

  if (!grandResult.ok && !raidResult.ok) {
    return { source: 'mock', bosses: mockData.getMockRaidBosses() };
  }

  const bosses = [];
  if (grandResult.ok) {
    bosses.push(...grandResult.rows.map((row) => mapBoss(row, 'epic')));
  }
  if (raidResult.ok) {
    bosses.push(...raidResult.rows.map((row) => mapBoss(row, 'raid')));
  }

  if (!bosses.length) {
    return { source: 'mock', bosses: mockData.getMockRaidBosses() };
  }

  bosses.sort(compareBossLevel);
  return { source: 'database', bosses };
}

module.exports = {
  getRaidBosses,
};
