# Require Auth Users REST Endpoint

This WordPress plugin modifies the `/wp-json/wp/v2/users` endpoint to require authentication.

By default on a WordPress site you can list the users that have posted content on the site by visiting this endpoint.

This plugin requires the user to be authenticated to view the list of users.

## What this means

If you go directly to the URL `/wp-json/wp/v2/users` you will get a `401 Unauthorized` response.

But if you open a block editor page and run the following from the browser console,

```js
await wp.apiFetch({path: 'wp/v2/users'});
```

you will get a list of users (because the `wp.apiFetch()` function authenticates the user's call to the WordPress REST API).

## Author

Sal Ferrarello / [salferrarello.com](https://salferrarello.com)
