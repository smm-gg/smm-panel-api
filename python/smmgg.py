"""SMM.GG SMM panel API client (Python 3, requests). Usage: SMMGG_API_KEY=... python smmgg.py"""
import os

import requests


class SmmGG:
    url = "https://smm.gg/api/v2"

    def __init__(self, key):
        self.key = key

    def _call(self, **data):
        r = requests.post(self.url, data={"key": self.key, **data}, timeout=30)
        try:
            return r.json()  # errors come back as {"error": "..."} (HTTP 4xx included)
        except ValueError:
            r.raise_for_status()
            raise

    def services(self):
        return self._call(action="services")

    def balance(self):
        return self._call(action="balance")

    def order(self, service, link, quantity, **extra):
        return self._call(action="add", service=service, link=link, quantity=quantity, **extra)

    def status(self, order):
        return self._call(action="status", order=order)

    def multi_status(self, orders):
        return self._call(action="status", orders=",".join(map(str, orders)))

    def refill(self, order):
        return self._call(action="refill", order=order)

    def refill_status(self, refill):
        return self._call(action="refill_status", refill=refill)

    def cancel(self, orders):
        return self._call(action="cancel", orders=",".join(map(str, orders)))


if __name__ == "__main__":
    api = SmmGG(os.environ.get("SMMGG_API_KEY", "YOUR_API_KEY"))
    print(api.balance())
    # new = api.order(service=1, link="https://instagram.com/username", quantity=100)
    # print(api.status(new["order"]))
