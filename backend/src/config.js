require('dotenv').config();

module.exports = {
  port: Number(process.env.PORT) || 3000,
  frontendOrigin: process.env.FRONTEND_ORIGIN || 'http://localhost:5173',
  db: {
    host: process.env.DB_HOST || '127.0.0.1',
    port: Number(process.env.DB_PORT) || 3306,
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'l2jhighfive',
  },
  rates: {
    xp: Number(process.env.XP_RATE) || 1,
    sp: Number(process.env.SP_RATE) || 1,
    adena: Number(process.env.ADENA_RATE) || 1,
    drop: Number(process.env.DROP_RATE) || 1,
  },
  serverName: process.env.SERVER_NAME || 'L2 Army',
  maxOnline: Number(process.env.MAX_ONLINE) || 2000,
  discordUrl: process.env.DISCORD_URL || 'https://discord.com',
  downloadUrl: process.env.DOWNLOAD_URL || '#',
};
