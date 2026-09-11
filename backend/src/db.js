const mysql = require('mysql2/promise');
const config = require('./config');

let pool = null;
let lastError = null;

function createPool() {
  pool = mysql.createPool({
    host: config.db.host,
    port: config.db.port,
    user: config.db.user,
    password: config.db.password,
    database: config.db.database,
    waitForConnections: true,
    connectionLimit: 8,
    namedPlaceholders: true,
    timezone: 'Z',
  });
  return pool;
}

function getPool() {
  if (!pool) {
    createPool();
  }
  return pool;
}

async function pingDatabase() {
  try {
    const connection = await getPool().getConnection();
    await connection.ping();
    connection.release();
    lastError = null;
    return true;
  } catch (error) {
    lastError = error;
    console.error('[db] Connection failed:', error.message);
    return false;
  }
}

/**
 * Run a query against the L2J game database.
 * Returns { ok, rows } or { ok: false, error }.
 */
async function query(sql, params = []) {
  try {
    const [rows] = await getPool().execute(sql, params);
    lastError = null;
    return { ok: true, rows };
  } catch (error) {
    lastError = error;
    console.error('[db] Query failed:', error.message);
    console.error('[db] SQL:', sql);
    return { ok: false, error };
  }
}

function getLastError() {
  return lastError;
}

module.exports = {
  getPool,
  pingDatabase,
  query,
  getLastError,
};
