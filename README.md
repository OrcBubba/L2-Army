# L2 Army — L2J Mobius CT 2.6 HighFive website

Vue 3 + Vite frontend and Express + mysql2 backend for a High Five private-server landing page.

Credentials stay in `backend/.env`. If MySQL is down, the API still returns mock data.

See the architecture notes in the project README body of the setup conversation, or start both apps with the commands below.

## Local run

```powershell
cd c:\Apache24\htdocs\l2_site\backend
copy .env.example .env
npm install
npm run dev
```

```powershell
cd c:\Apache24\htdocs\l2_site\frontend
npm install
npm run dev
```

Open http://localhost:5173 — Vite proxies `/api` to http://localhost:3000
