# Provisioning

## Running websockets

```sh
sail artisan websockets:serve
```

## Running queues

```sh
sail artisan queue:listen --timeout=1800
```

## Stripe

Run ngrok to receive Stripe webhooks. (Remember to update the webhook url in stripe to the ngrok url).

```sh
ngrok http 80
```
