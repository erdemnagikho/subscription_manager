## For creating table
php artisan migrate

## For inserting applications
php artisan db:seed --class=ApplicationSeeder

## Caching Settings
Change CACHE_DRIVER=files to CACHE_DRIVER=redis

## For running app
php artisan serve

## Register Device
```bash
/api/v1/devices [POST]

Example request
{
    "uid": "123e4567-e89b-12d3-a456-426655440001",
    "app_id": 1,
    "language": "tr",
    "os": "Android"
}

Example response
{
    "device": {
        "uid": "123e4567-e89b-12d3-a456-426655440001",
        "app_id": 1,
        "client_token": "1a0bRSme3pA0gq2VI6ma"
    }
}
```

## Subscription
```bash
/api/v1/subscriptions [POST]

- This endpoint needs credentials on header.
- You can send with keys 'username' and 'password'
- For android: username: 'android', password: 'oreo1'
- For ios: username: 'ios', password: 'ios15'

Example request
{
    "client_token": "1a0bRSme3pA0gq2VI6ma",
    "receipt": 1
}

Example response
{
    "subscription": {
        "status": "Started",
        "expire_date": "2023-01-06 20:47:48"
    }
}
```

```bash
/api/v1/subscriptions/{token} [GET]

- This endpoint needs token param.

Example response
{
    "subscription": {
        "status": "Started",
        "expire_date": "2023-01-06 20:40:03"
    }
}
```
## For running command
php artisan expired-subscriptions

- This command provides to make renewed subscription if it is expired.
