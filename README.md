# Provisioning

## Running websockets

```sh
sail artisan websockets:serve
```

## Running queues

```sh
sail artisan queue:listen
```

## Stripe

Run ngrok to receive Stripe webhooks. (Remember to update the webhook url in stripe to the ngrok url).

```sh
ngrok http 80
```
