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
    - Copy the `.env.example` to `.env`
4. Go back to `backend` folder then Run: `docker-compose up -d`
5. Run: `docker-compose exec php php artisan migrate`


***Running Frontend in Local:***
1. Using `terminal` change directory `frontend > todo`
2. Run: `npm i`
3. Run: `npm run dev`
4. Browse: the URL provided by the `run dev`


---
### **Other info:**
1. Backend running in `localhost:3333`
2. Frontend default to run in `localhost:3000`
   
### **Running the Unit Test:**
1. Using `terminal` change directory `backend`
2. Run: `docker-compose exec php php artisan test`

### **Challenges in development:**
 - CORS issue from the frontend when calling graphQL backend. Workaround, did axios proxy 
 - Some minor bugs encounter when testing end to end.

### **Future enhancement**
 - More validation for both frontend and backend
 - Enhancement in UX like a loading screen when there is an asynchronous HTTP request.
 - More cleaner code structure.
