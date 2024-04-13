# Genko-todo
Demonstration using graphQL managing a todo list
 - NuxtJS
 - Laravel + Lighthouse
 - Docker

***Running Backend in Local:***
1. Using `terminal` change directory `backend`
2. Run: `docker-compose run --rm php composer install`
3. *On first set up only*: 
    - Change directory inside the `todo` 
    - Copy the `env.example` to `.env`
4. Go back to `backend` folder then Run: `docker-compose up`
5. Run: `docker-compose exec php php artisan migrate`


---
## **Other info:**
1. Backend running in `localhost:3333`
2. Frontend default to run in `localhost:3000`