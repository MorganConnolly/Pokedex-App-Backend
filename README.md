# Pokedex App Backend Laravel API

## Setup
1. Clone the repo: https://github.com/MorganConnolly/Pokedex-App-Backend
2. Install PHP dependancies: composer install
3. Create .env file: cp .env.example .env
4. Start Docker containers: ./vendor/bin/sail up -d
5. Insall Node dependnecies via Sail: ./vendor/bin/sail npm install
6. Generate Laravel app key: ./vendor/bin/sail artisan key:generate
7. (Optional) Open mailpit on localhost:8025

## Tech Stack
- Docker & OrbStack for containerisation & dependency handling.
- PHP to power the router, controllers, middleware, models and database access.
- SQL & PostgreSQL for database structure.
- Laravel with Sanctum for API authentication and Eloquent for database interaction.
- React Native, Zustand & JS for frontend: https://github.com/MorganConnolly/Pokedex-App-Backend

## Screenshots
### Email Sent After Login
<img width="453" height="465" alt="Screenshot 2025-07-23 at 15 31 29" src="https://github.com/user-attachments/assets/e53e3dad-f390-4683-a8aa-afa1db1b96bb" />

## Learning Summary
### Docker
Enables the API to be package with its dependencies using the docker-compose.yml file. So, running it is as simple as "sail up -d" which runs the container group including laravel, mailpit and postgres.
### PHP
Handles logic for authentication, request processing, database interaction and returning responses.
### Why Laravel?
A PHP framework designed to streamline web and API development with elegant syntax, built-in tools and modular structure. It achives this through controllers, services and models which each contain PHP to handle a separate part of the logic.
### Controllers
Laravel controllers handle HTTP requests and can employ services or models, keeping things clean and modularised.
### RESTAPIs
Has standardised HTTP methods: GET, POST, PUT, DELETE. Aims to provide a stateless, uniform interface that's scalable and easy to integrate. More info: https://aws.amazon.com/what-is/restful-api/
### API Authentication
There are a few types of authentication: API key, Bearer Token/Token-Based & OAuth. This API uses tokens, provided after a user registers or logs in, so Sanctum can securely identify which user a request originates from, and so access their data.
### Why PostgreSQL?
An open-source highly-scalable object-relational database system known for its reliability and advanced feature set.
### Migrations, Factories & Seeders
Migrations are version-controlled scripts which define and modify your database schema. Factories generate fake data for testing using eloquent. Seeders poplate the database with initial or sample data using factories.
### The Mail Component & Mailpit
The mail component allows the creation of a mail controller which can pass variables to propagate a blade template that's sent to the user. Mailpit intercepts these, preventing them from actually being sent to addresses and allowing developers to analyse their results.

## API Usage
All requests require the headers:
- 'Accept: application/json'
- 'Content-Type: application/json'
### Register 
- Method: POST
- /api/register
- Authentication Not Required
- Request Body: {
    email: value,
    name: value,
    password: value
}
- Reponse Body: {
    token: value
}
### Login
- Method: POST
- /api/login
- Authentication Not Required
- Request Body: {
    email: value,
    password: value
}
- Reponse Body: {
    token: value
}
### Logout
- Method: POST
- /api/logout
- Authentication Required: Bearer Token
### Favourite Pokemon Summary
- Method: GET
- /favs/
- Authentication Required: Bearer Token
- Response Body: {
    favourites: [
        { id : value },
        ...
    ]
}
### Create a Favourite
- Method: POST
- /favs/
- Authentication Required: Bearer Token
- Request Body: {
    name: value,
    colour: value,
    data: {...},
}
- Response Body: N/A
### Retrieve Details of a Favourite
- Method: GET
- /favs/[ID]
- Authentication Required: Bearer Token
- Reponse Body: {
    name: value,
    colour: value,
    data: {...},
}
### Delete a Favourite
- Method: DELETE
- /favs/[ID]
- Authentication Required: Bearer Token
