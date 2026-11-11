// SMM.GG SMM panel API client (Node.js 18+, no dependencies). Usage: SMMGG_API_KEY=... node smmgg.js
class SmmGG {
  constructor(key, url = 'https://smm.gg/api/v2') {
    this.key = key;
    this.url = url;
  }

  async call(data) {
    const res = await fetch(this.url, { method: 'POST', body: new URLSearchParams({ key: this.key, ...data }) });
    try {
      return await res.json(); // errors come back as {"error": "..."} (HTTP 4xx included)
    } catch {
      throw new Error(`HTTP ${res.status}`);
    }
  }

  services() { return this.call({ action: 'services' }); }
  balance() { return this.call({ action: 'balance' }); }
  order(fields) { return this.call({ action: 'add', ...fields }); }
  status(order) { return this.call({ action: 'status', order }); }
  multiStatus(orders) { return this.call({ action: 'status', orders: orders.join(',') }); }
  refill(order) { return this.call({ action: 'refill', order }); }
  refillStatus(refill) { return this.call({ action: 'refill_status', refill }); }
  cancel(orders) { return this.call({ action: 'cancel', orders: orders.join(',') }); }
}

module.exports = SmmGG;

if (require.main === module) {
  const api = new SmmGG(process.env.SMMGG_API_KEY || 'YOUR_API_KEY');
  api.balance().then(console.log);
  // api.order({ service: 1, link: 'https://instagram.com/username', quantity: 100 }).then(console.log);
}
