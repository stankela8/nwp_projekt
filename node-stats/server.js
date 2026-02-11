const express = require('express');
const cors = require('cors');
const axios = require('axios');

const app = express();
app.use(cors());
app.use(express.json());

const LARAVEL_API_BASE = 'http://127.0.0.1:8000';

async function fetchRawStats() {
  try {
    const res = await axios.get(`${LARAVEL_API_BASE}/stats/raw`);
    console.log('RAW STATS RESPONSE:', res.data); // log u konzolu
    return res.data.games || [];
  } catch (e) {
    console.error('Error fetching raw stats:', e.message);
    throw e;
  }
}


app.get('/api/stats/genres', async (req, res) => {
  try {
    const games = await fetchRawStats();

    const byGenre = {};
    for (const g of games) {
      const genre = g.genre || 'Unknown';
      // ako duration_minutes dolazi kao string, parsiraj u broj
      const minutes = Number(g.total_minutes) || 0;
      if (!byGenre[genre]) byGenre[genre] = 0;
      byGenre[genre] += minutes;
    }

    const labels = Object.keys(byGenre);
    const values = labels.map(l => byGenre[l]); // sada su brojevi

    res.json({ labels, values });
  } catch (e) {
    console.error(e.message);
    res.status(500).json({ error: 'Cannot compute genre stats' });
  }
});


app.get('/api/stats/top-games', async (req, res) => {
  try {
    const games = await fetchRawStats();

    const aggregated = games.map(g => ({
      name: g.name || `Game #${g.id}`,
      minutes: Number(g.total_minutes) || 0
    }));

    aggregated.sort((a, b) => b.minutes - a.minutes);
    const top = aggregated.slice(0, 5);

    res.json({
      labels: top.map(t => t.name),
      values: top.map(t => t.minutes)
    });
  } catch (e) {
    console.error(e.message);
    res.status(500).json({ error: 'Cannot compute top games stats' });
  }
});


const port = 3001;
app.listen(port, () => {
  console.log(`Node stats service running on http://localhost:${port}`);
});
