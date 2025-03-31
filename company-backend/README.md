# Company Backend
This is the backend part of the My Project application, built with Laravel.

## Prerequisites
- PHP (version 8.2.12 )
-  Composer (version 2.8.3)
- MySQl (optional) ; by default it uses Sqlite

## Installation
 1. Clone the repository
 ```
 git clone  https://github.com/Hedara444/company-test.git
 ```
 2. Navigate to the backed directory

 ```
 cd company-backend
 ```

 3. Install the dependencies
 ```
 composer install
 ```
 4. rename `.env.example` to `.env`


 5. Generate the application key
```
php artisan key:generate
```

6. Run the database migrations and seeders

```
php artisan migrate --seed
```


## API Configuration
 you will need to set up the api key for phone validation : 
 1. go to abstractapi.com.
    https://www.abstractapi.com/phone-validation-api
 2. sign up then navigate to phone number validation.
 3. look for the api key and copy it.
 4. go to the `.env` file and paste the api key in the `ABSTRACT_API_KEY` variable.

----
## Run The Project
 in the terminal :
```
php artisan serve
```

----
## Api documentation

open new tab in your browser and navigate to : http://127.0.0.1:8000/docs/api.

now you should se the api documentation information

----
## APi testing
1. open the `credentials` directory  , you will find postman collection. 
2. open postman on your machine.
3. navigate to collections then  click on import button.
4. drag and drop the postman collection in the `credentials` directory or navigate to it then choose it.
5. as the last step you will need to set up your environment variables
  go to `Environment Variables` in postman ( the globals section)
6. create new variables as shown bellow 
```
baseUrl ==> it value : http://127.0.0.1:8000/
authToken ==> its value : your generated token from login phase
```

----
## Run tests 
NOte that the testing environment uses MySQL with Xammp , so you will need to configure it in the .env.testing file.
1. after configuration , go to  your terminal , paste this:
 
``` 
php artisan migrate --env=testing
```

2. paste this in your terminal
```
   php artisan test 
```
