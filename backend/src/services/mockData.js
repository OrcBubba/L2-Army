/**
 * Tutorial fallback data used when the HighFive game database is unreachable.
 * The frontend can still render rankings, stats, and castles.
 */
const { getBossLevel, compareBossLevel } = require('./bossNames');

const mockPlayers = [
  { charId: 1, char_name: 'Seraphine', level: 85, classid: 90, pvpkills: 1842, pkkills: 12, clan_name: 'Dawn Guard', online: 1 },
  { charId: 2, char_name: 'Kaelthorn', level: 85, classid: 89, pvpkills: 1604, pkkills: 91, clan_name: 'Night Banner', online: 1 },
  { charId: 3, char_name: 'Lysara', level: 84, classid: 103, pvpkills: 1411, pkkills: 8, clan_name: 'Ivory Oath', online: 0 },
  { charId: 4, char_name: 'Dreadmoor', level: 85, classid: 91, pvpkills: 1288, pkkills: 220, clan_name: 'Blood Orchid', online: 1 },
  { charId: 5, char_name: 'Elowen', level: 83, classid: 102, pvpkills: 1102, pkkills: 4, clan_name: 'Moonveil', online: 0 },
  { charId: 6, char_name: 'Vexar', level: 85, classid: 110, pvpkills: 974, pkkills: 155, clan_name: 'Night Banner', online: 1 },
  { charId: 7, char_name: 'Mirielle', level: 82, classid: 97, pvpkills: 821, pkkills: 2, clan_name: 'Dawn Guard', online: 0 },
  { charId: 8, char_name: 'Thorne', level: 85, classid: 113, pvpkills: 766, pkkills: 44, clan_name: 'Iron Hymn', online: 1 },
  { charId: 9, char_name: 'Aurelia', level: 80, classid: 105, pvpkills: 612, pkkills: 1, clan_name: 'Ivory Oath', online: 0 },
  { charId: 10, char_name: 'Rook', level: 85, classid: 131, pvpkills: 501, pkkills: 310, clan_name: 'Blood Orchid', online: 1 },
];

function nextSundaySiegeMs() {
  const now = new Date();
  const day = now.getUTCDay();
  const daysUntilSunday = (7 - day) % 7 || 7;
  const siege = new Date(Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate() + daysUntilSunday, 18, 0, 0));
  return siege.getTime();
}

const siegeDate = nextSundaySiegeMs();

const mockCastles = [
  { id: 1, name: 'Gludio', taxPercent: 0, siegeDate, ownerClan: 'Dawn Guard', ownerClanLevel: 8, reputationScore: 42000 },
  { id: 2, name: 'Dion', taxPercent: 10, siegeDate, ownerClan: 'Night Banner', ownerClanLevel: 7, reputationScore: 31000 },
  { id: 3, name: 'Giran', taxPercent: 15, siegeDate, ownerClan: 'Ivory Oath', ownerClanLevel: 8, reputationScore: 58000 },
  { id: 4, name: 'Oren', taxPercent: 0, siegeDate, ownerClan: null, ownerClanLevel: null, reputationScore: null },
  { id: 5, name: 'Aden', taxPercent: 12, siegeDate, ownerClan: 'Moonveil', ownerClanLevel: 8, reputationScore: 64000 },
  { id: 6, name: 'Innadril', taxPercent: 0, siegeDate, ownerClan: null, ownerClanLevel: null, reputationScore: null },
  { id: 7, name: 'Goddard', taxPercent: 8, siegeDate, ownerClan: 'Iron Hymn', ownerClanLevel: 6, reputationScore: 18000 },
  { id: 8, name: 'Rune', taxPercent: 5, siegeDate, ownerClan: 'Blood Orchid', ownerClanLevel: 7, reputationScore: 27500 },
  { id: 9, name: 'Schuttgart', taxPercent: 0, siegeDate, ownerClan: null, ownerClanLevel: null, reputationScore: null },
];

function getMockPvp() {
  return [...mockPlayers].sort((a, b) => b.pvpkills - a.pvpkills);
}

function getMockPk() {
  return [...mockPlayers].sort((a, b) => b.pkkills - a.pkkills);
}

function getMockStats(rates, serverName, maxOnline) {
  return {
    onlinePlayers: mockPlayers.filter((p) => p.online === 1).length,
    totalCharacters: 1840,
    maxLevelCharacters: 96,
    serverStatus: 'offline',
    usingMockData: true,
    serverName,
    maxOnline,
    rates,
  };
}

function getMockCastles() {
  return mockCastles.map((castle) => ({
    ...castle,
    nextSiegeIso: new Date(castle.siegeDate).toISOString(),
  }));
}

function getMockRaidBosses() {
  const later = Date.now() + 6 * 60 * 60 * 1000;
  const bosses = [
    { bossId: 29001, name: 'Queen Ant', type: 'epic', alive: true, status: 'alive', respawnTime: 0 },
    { bossId: 29006, name: 'Core', type: 'epic', alive: true, status: 'alive', respawnTime: 0 },
    { bossId: 29014, name: 'Orfen', type: 'epic', alive: false, status: 'dead', respawnTime: later },
    { bossId: 29020, name: 'Baium', type: 'epic', alive: true, status: 'alive', respawnTime: 0 },
    { bossId: 29068, name: 'Antharas', type: 'epic', alive: false, status: 'dead', respawnTime: later },
    { bossId: 29028, name: 'Valakas', type: 'epic', alive: true, status: 'alive', respawnTime: 0 },
    { bossId: 29118, name: 'Beleth', type: 'epic', alive: false, status: 'dead', respawnTime: later },
    { bossId: 25035, name: "Shilen's Messenger Cabrio", type: 'raid', alive: true, status: 'alive', respawnTime: 0 },
    { bossId: 25054, name: 'Kernon', type: 'raid', alive: false, status: 'dead', respawnTime: later },
    { bossId: 25092, name: 'Korim', type: 'raid', alive: true, status: 'alive', respawnTime: 0 },
    { bossId: 25126, name: 'Longhorn Golkonda', type: 'raid', alive: true, status: 'alive', respawnTime: 0 },
    { bossId: 25143, name: 'Fire of Wrath Shuriel', type: 'raid', alive: false, status: 'dead', respawnTime: later },
    { bossId: 25220, name: 'Death Lord Hallate', type: 'raid', alive: true, status: 'alive', respawnTime: 0 },
    { bossId: 25325, name: 'Flame of Splendor Barakiel', type: 'raid', alive: true, status: 'alive', respawnTime: 0 },
    { bossId: 25450, name: 'Cherub Galaxia', type: 'raid', alive: false, status: 'dead', respawnTime: later },
    { bossId: 25527, name: 'Uruka', type: 'raid', alive: true, status: 'alive', respawnTime: 0 },
  ].map((boss) => ({ ...boss, level: getBossLevel(boss.bossId) }));

  return bosses.sort(compareBossLevel);
}

module.exports = {
  getMockPvp,
  getMockPk,
  getMockStats,
  getMockCastles,
  getMockRaidBosses,
};
