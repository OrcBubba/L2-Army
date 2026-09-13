const express = require('express');
const cors = require('cors');
const config = require('./config');
const healthRoutes = require('./routes/health');
const rankingsRoutes = require('./routes/rankings');
const statsRoutes = require('./routes/stats');
const castlesRoutes = require('./routes/castles');
const raidBossesRoutes = require('./routes/raidBosses');

const app = express();

const allowedOrigins = [
  config.frontendOrigin,
  process.env.CORS_ORIGIN,
  process.env.FRONTEND_ORIGIN,
  'https://l2website.onrender.com',
  'http://localhost:5173',
  'http://127.0.0.1:5173',
].filter(Boolean);

app.use(
  cors({
    origin(origin, callback) {
      if (!origin || allowedOrigins.includes(origin)) {
        callback(null, true);
        return;
      }
      callback(null, true);
    },
  })
);
app.use(express.json());

app.use('/api/health', healthRoutes);
app.use('/api/rankings', rankingsRoutes);
app.use('/api/stats', statsRoutes);
app.use('/api/castles', castlesRoutes);
app.use('/api/raid-bosses', raidBossesRoutes);

app.use((req, res) => {
  res.status(404).json({ ok: false, error: 'Not found', path: req.path });
});

app.listen(config.port, () => {
  console.log(`[api] ${config.serverName} website API listening on http://localhost:${config.port}`);
  console.log('[api] If MySQL is down, endpoints still return mock data for the tutorial.');
});
