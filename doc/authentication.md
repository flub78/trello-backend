# Authentication and AUthorization

With Laravel Sanctum

    https://www.youtube.com/watch?v=DAXOWbug5JQ

    https://laravel.com/docs/10.x/authentication

    https://laravel.com/docs/10.x/authorization

    https://laravel.com/docs/10.x/sanctum

## Basic authentication

    composer require laravel/breeze --dev
    php artisan breeze:install

Once done there are several created routes:

    http://127.0.0.1:8000/ which contains links for login and registration

    http://127.0.0.1:8000/register
    http://127.0.0.1:8000/login
    http://127.0.0.1:8000/dashboard

    http://127.0.0.1:8000/profile

    http://127.0.0.1:8000/forgot-password   (mail not configured yet)



When using a web browser, a user will provide their username and password via a login form. If these credentials are correct, the application will store information about the authenticated user in the user's session. A cookie issued to the browser contains the session ID so that subsequent requests to the application can associate the user with the correct session. After the session cookie is received, the application will retrieve the session data based on the session ID, note that the authentication information has been stored in the session, and will consider the user as "authenticated".

When a remote service needs to authenticate to access an API, cookies are not typically used for authentication because there is no web browser. Instead, the remote service sends an API token to the API on each request. The application may validate the incoming token against a table of valid API tokens and "authenticate" the request as being performed by the user associated with that API token.


## Passport

Full and complex OAuth2authentication provider.

## Sanctum

Simpler and recommended authentication package.
 It can handle request from WEB browser and API requests via tokens.

Routes like /profile or /dashboard are already protected y the auth midleware. When the user is not authenticated she is redirected to the login page.

### Installation

    composer require laravel/sanctum

    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

    php artisan migrate

    uncomment  \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    in app/Http/Kernel.php

### Issuing API Tokens

The User model is already compliant. It includes the HasApiTokens trait.

### Testing

Using postman

    GET     http://127.0.0.1:8000/api/tag_colors
    with Headers KEY=Accept,VALUE=application/json

    {
        "message": "Unauthenticated."
    }

 	Postman
		POST	http://127.0.0.1:8000/api/register
				name=toto
				email=toto@gmail.com
				password=toto
				
				header
					accept	application/json
					
			"token": "2|6RExY7tXKV4frqRTE6fZnkraxalefXXoUI9gpWO48c9a58c1"   
    