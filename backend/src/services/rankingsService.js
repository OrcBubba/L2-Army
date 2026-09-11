const db = require('../db');
const mockData = require('./mockData');
const { getClassName } = require('./classNames');

/**
 * Ranking queries for L2J Mobius CT 2.6 HighFive (`dist/db_installer/sql/game/characters.sql`).
 *
 * Columns used (HighFive names; CT_0 Interlude used the same names for these fields):
 * - characters.charId        player unique id
 * - characters.char_name     character name
 * - characters.level         current level
 * - characters.pvpkills      lawful PvP kills
 * - characters.pkkills       PK count
 * - characters.clanid        clan id (0/null = no clan)
 * - characters.online        1 = currently in-game
 * - characters.accesslevel   GM / admin flag (exclude from public rankings)
 * - characters.deletetime    0 = active character; > 0 is scheduled for deletion
 * - characters.classid       current (active) class id — HighFive ClassId enum
 * - clan_data.clan_name      optional clan display name
 */

const PLAYER_SELECT = `
  SELECT
    c.charId,
    c.char_name,
    c.level,
    c.pvpkills,
    c.pkkills,
    c.online,
    c.classid,
    cd.clan_name
  FROM characters AS c
  LEFT JOIN clan_data AS cd ON cd.clan_id = c.clanid
  WHERE c.accesslevel = 0
    AND c.deletetime = 0
`;

function mapPlayer(row) {
  return {
    charId: row.charId,
    char_name: row.char_name,
    level: row.level,
    pvpkills: row.pvpkills || 0,
    pkkills: row.pkkills || 0,
    online: row.online === 1,
    clan_name: row.clan_name || null,
    classid: row.classid ?? null,
    class_name: getClassName(row.classid),
  };
}

function safeLimit(limit) {
  return Math.min(50, Math.max(1, Number(limit) || 10));
}

async function getTopPvp(limit = 10) {
  const take = safeLimit(limit);
  // LIMIT is interpolated after clamping to an integer; MySQL prepared statements
  // often reject placeholders in LIMIT.
  const sql = `${PLAYER_SELECT}
    ORDER BY c.pvpkills DESC, c.level DESC
    LIMIT ${take}`;

  const result = await db.query(sql);
  if (!result.ok) {
    return { source: 'mock', players: mockData.getMockPvp().slice(0, take).map(mapPlayer) };
  }
  return { source: 'database', players: result.rows.map(mapPlayer) };
}

async function getTopPk(limit = 10) {
  const take = safeLimit(limit);
  const sql = `${PLAYER_SELECT}
    ORDER BY c.pkkills DESC, c.level DESC
    LIMIT ${take}`;

  const result = await db.query(sql);
  if (!result.ok) {
    return { source: 'mock', players: mockData.getMockPk().slice(0, take).map(mapPlayer) };
  }
  return { source: 'database', players: result.rows.map(mapPlayer) };
}

module.exports = {
  getTopPvp,
  getTopPk,
};
