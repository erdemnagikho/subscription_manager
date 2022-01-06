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


# REPORTS
- Records Count Today
```sql
SELECT COUNT(*) 
FROM subscriptions
WHERE date(created_at) = current_date;
```

- All Android Records Count Today
```sql
SELECT COUNT(*)
FROM subscriptions s
INNER JOIN devices d ON d.id = s.device_id AND d.os = 'Android'
WHERE date(s.created_at) = current_date;
```

- All Ios Records Count Today
```sql
SELECT COUNT(*)
FROM subscriptions s
INNER JOIN devices d ON d.id = s.device_id AND d.os = 'Ios'
WHERE date(s.created_at) = current_date;
```

- All Started Records Today
```sql
SELECT COUNT(*)
FROM subscriptions s
WHERE s.status = 'Started'
WHERE date(s.created_at) = current_date;
```

- All Renewed Records Today
```sql
SELECT COUNT(*)
FROM subscriptions s
WHERE s.status = 'Renewed'
WHERE date(s.created_at) = current_date;
```

- All Cancelled Records Today
```sql
SELECT COUNT(*)
FROM subscriptions s
WHERE s.status = 'Cancelled'
WHERE date(s.created_at) = current_date;
```

- All Android Started Records Today
```sql
SELECT COUNT(*)
FROM subscriptions s
INNER JOIN devices d ON d.id = s.device_id AND d.os = 'Android'
WHERE s.status = 'Started'
WHERE date(s.created_at) = current_date;
```

- All Android Renewed Records Today
```sql
SELECT COUNT(*)
FROM subscriptions s
INNER JOIN devices d ON d.id = s.device_id AND d.os = 'Android'
WHERE s.status = 'Renewed'
WHERE date(s.created_at) = current_date;
```

- All Android Cancelled Records Today
```sql
SELECT COUNT(*)
FROM subscriptions s
INNER JOIN devices d ON d.id = s.device_id AND d.os = 'Android'
WHERE s.status = 'Cancelled'
WHERE date(s.created_at) = current_date;
```

- All Ios Started Records Today
```sql
SELECT COUNT(*)
FROM subscriptions s
INNER JOIN devices d ON d.id = s.device_id AND d.os = 'Ios'
WHERE s.status = 'Started'
WHERE date(s.created_at) = current_date;
```

- All Ios Renewed Records Today
```sql
SELECT COUNT(*)
FROM subscriptions s
INNER JOIN devices d ON d.id = s.device_id AND d.os = 'Ios'
WHERE s.status = 'Renewed'
WHERE date(s.created_at) = current_date;
```

- All Ios Cancelled Records Todayy
```sql
SELECT COUNT(*)
FROM subscriptions s
INNER JOIN devices d ON d.id = s.device_id AND d.os = 'Ios'
WHERE s.status = 'Cancelled'
WHERE date(s.created_at) = current_date;
```

