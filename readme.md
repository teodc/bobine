# Bobine

> ⚠️Work in progress

Discussion thread.

## Requirements

- Git
- PHP 7.3
- Composer
- Docker
- Docker Compose

## Installation

Get the sources:

```
git clone https://github.com/teodc/bobine.git
```

```
cp .env.example .env
```

```
docker-compose up -d
```

```
php artisan key:generate
```

```
composer install -o
```

```
php artisan migrate --seed
```

> You can alternatively execute the PHP & composer commands directly in the PHP container if you prefer or if you host machine doesn't have PHP or composer installed.
> Fot that just prefix the command with: `docker-compose exec php <command>`.

## Usage

Add `127.0.0.1  api.bobine.local` to your `/etc/hosts` file.

Check if the app is running and reachable:

```
curl -X POST https://api.bobine.local/poke
```

Check the API documentation below to perform actions against the app.

Here is a cURL sample of an API request:

```
curl -X POST \
  https://api.bobine.local/v1/comments \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer {your_auth_token_}' \
  -d '{ \
    "body" : "This is a comment." \
  }'
```

⚠️ For the protected routes, don't forget to provide your API token as a `Bearer` token in the `Authorization` header of the request.
In the modest documentation below, you can identify the routes that are protected or not with the marks 🔒 and  🔓.

## API Documentation

### Request an auth token

#### URL

🔓 `POST /auth/tokens`

#### Request

- `name`: required|string _(The name of the user)_

#### Response

Status:
- `201` or `200` _(Depending if the user is new)_

Payload:
- `data`
  * `token`: string
  * `user_created`: boolean

### Show a user

#### URL

🔒 `GET /v1/users/{id}`

#### Response

Status:
- `200`

Payload:
- `data`
  * `id`: string
  * `name`: string
  * `email`: string|null
  * `created_at`: dateTime|null
  * `updated_at`: dateTime|null
  * `comments_count`: integer

### List user comments

#### URL

🔒 `GET /v1/comments`

#### Response

Status:
- `200`

Payload:
- `data`
  * Comment
    - `id`: string
    - `author_id`: string
    - `body`: string
    - `created_at`: dateTime|null
    - `updated_at`: dateTime|null
    - `author`: User (see `GET /users/{id}` response)
  * ...

### Show a comment

#### URL

🔒 `GET /v1/comments/{id}`

#### Response

Status:
- `200`

Payload:
- `data`
  * `id`: string
  * `author_id`: string
  * `body`: string
  * `created_at`: dateTime|null
  * `updated_at`: dateTime|null
  * `author`: User (see `GET /users/{id}` response)

### Store a comment

#### URL

🔒 `POST /v1/comments`

#### Request

- `body`: required|string _(The content of the comment)_

#### Response

Status:
- `201`

Payload:
- `data`
  * `id`: string
  * `author_id`: string
  * `body`: string
  * `created_at`: dateTime|null
  * `updated_at`: dateTime|null
  * `author`: User (see `GET /users/{id}` response)

### Update a comment

#### URL

🔒 `PUT /v1/comments/{id}`

#### Request

- `body`: required|string _(The content of the comment)_

#### Response

Status:
- `200`

Payload:
- `data`
  * `id`: string
  * `author_id`: string
  * `body`: string
  * `created_at`: dateTime|null
  * `updated_at`: dateTime|null
  * `author`: User (see `GET /users/{id}` response)

### Delete a comment

> You can only delete the comments you authored!

#### URL

🔒 `DELETE /v1/comments/{id}`

#### Response

Status:
- `204`

Payload:
- `data`: null

## Testing

### Feature tests

Use the following commands to run the tests for each test suite:

```
vendor/bin/phpunit --testsuite feature_api_auth
```

```
vendor/bin/phpunit --testsuite feature_api_v1
```

### Unit tests

TODO
