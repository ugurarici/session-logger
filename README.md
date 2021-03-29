# Session Logger

A basic Web app to log completed sessions (for example a readin session) and retrive reports of it, with a daily streak counter.

## How to Install

- Clone this repository.
- Copy `.env.example` as `.env` and set Database credentials
- Run `composer install`
- Run `php artisan migrate`
- You may set your application locale on `app/config/app.php` Reports durations text will be created on your default language. (ex; `1 hour 50 minutes` in English but `1 saat 50 dakika` in Turkish) 

## How to Use

- API has 4 endpoints. 1 for create new logs and 3 for reporting.

### To create a new log;

Send a `POST` request to `/api/log` endpoint with `user_id` and `duration` fields. Both of fields are required and has to be an integer. You need to send `duration` as seconds. If it fails, it returns validation or other errors with proper HTTP response code and error message. If it success; it returns `HTTP 201` and recorded log detail as JSON.

### To retrive reports;

There are 3 type of reports. Every report endpoint requires a `user_id` parameter (as a query string). It has to be an integer.

- Send a `GET` request to `/api/overall?user_id=1` to get this years and this months overall report for user with id `1`
- Send a `GET` request to `/api/last7DaysDuration?user_id=1` to get last 7 days session durations report for user with id `1`
- Send a `GET` request to `/api/activeDaysOfMonth?user_id=1` to get active days of this month report for user with id `1`
