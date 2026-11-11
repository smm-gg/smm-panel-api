<?php
// SMM.GG SMM panel API client. Usage: php SmmGG.php  (set SMMGG_API_KEY first)
class SmmGG
{
    private string $url = 'https://smm.gg/api/v2';

    public function __construct(private string $key) {}

    public function services(): array { return $this->call(['action' => 'services']); }
    public function balance(): array { return $this->call(['action' => 'balance']); }
    public function order(array $fields): array { return $this->call(['action' => 'add'] + $fields); }
    public function status(int $order): array { return $this->call(['action' => 'status', 'order' => $order]); }
    public function multiStatus(array $orders): array { return $this->call(['action' => 'status', 'orders' => implode(',', $orders)]); }
    public function refill(int $order): array { return $this->call(['action' => 'refill', 'order' => $order]); }
    public function refillStatus(int $refill): array { return $this->call(['action' => 'refill_status', 'refill' => $refill]); }
    public function cancel(array $orders): array { return $this->call(['action' => 'cancel', 'orders' => implode(',', $orders)]); }

    private function call(array $data): array
    {
        $ch = curl_init($this->url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(['key' => $this->key] + $data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ]);
        $body = curl_exec($ch);
        if ($body === false) throw new RuntimeException(curl_error($ch));
        return json_decode($body, true) ?? ['error' => 'Invalid response'];
    }
}

if (PHP_SAPI === 'cli' && realpath($argv[0]) === __FILE__) {
    $api = new SmmGG(getenv('SMMGG_API_KEY') ?: 'YOUR_API_KEY');
    print_r($api->balance());
    // $order = $api->order(['service' => 1, 'link' => 'https://instagram.com/username', 'quantity' => 100]);
    // print_r($api->status($order['order']));
}
