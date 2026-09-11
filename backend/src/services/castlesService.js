const db = require('../db');
const mockData = require('./mockData');

/**
 * Castle ownership for L2J Mobius CT 2.6 HighFive.
 *
 * castle.id / castle.name / castle.taxPercent / castle.siegeDate
 *   HighFive game schema (`sql/game/castle.sql`).
 *   siegeDate is stored as a Unix timestamp in milliseconds (bigint).
 *   CT_0 Interlude used the same castle column names for id, name, taxPercent, siegeDate.
 *
 * clan_data.hasCastle
 *   Castle id owned by the clan (0 = none). Same join pattern as Interlude.
 *
 * clan_data.clan_name, clan_level, reputation_score, leader_id
 *
 * siege_clans.castle_id, clan_id, type, castle_owner
 *   Registration table; type typically: 1 attacker, 2 defender (owner also flagged).
 */

function toIso(ms) {
  const value = Number(ms) || 0;
  if (!value) {
    return null;
  }
  return new Date(value).toISOString();
}

function mapCastle(row) {
  return {
    id: row.id,
    name: row.name,
    taxPercent: row.taxPercent,
    siegeDate: Number(row.siegeDate) || 0,
    nextSiegeIso: toIso(row.siegeDate),
    ownerClan: row.clan_name || null,
    ownerClanId: row.clan_id || null,
    ownerClanLevel: row.clan_level ?? null,
    reputationScore: row.reputation_score ?? null,
    leaderId: row.leader_id || null,
    registeredClans: Number(row.registered_clans) || 0,
  };
}

async function getCastles() {
  const sql = `
    SELECT
      castle.id,
      castle.name,
      castle.taxPercent,
      castle.siegeDate,
      clan_data.clan_id,
      clan_data.clan_name,
      clan_data.clan_level,
      clan_data.reputation_score,
      clan_data.leader_id,
      (
        SELECT COUNT(*)
        FROM siege_clans
        WHERE siege_clans.castle_id = castle.id
      ) AS registered_clans
    FROM castle
    LEFT JOIN clan_data ON clan_data.hasCastle = castle.id
    ORDER BY castle.id ASC
  `;

  const result = await db.query(sql);
  if (!result.ok) {
    return { source: 'mock', castles: mockData.getMockCastles() };
  }

  return { source: 'database', castles: result.rows.map(mapCastle) };
}

module.exports = {
  getCastles,
};
