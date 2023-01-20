# PROGRAMMING TEST PT. ESB

## Information
This repository contains projects to fulfill programming test assessments at `PT. Esensi Solusi Buana`.

## Installation
Clone the repository
```
git clone https://github.com/randijulio13/esb-programming-test.git
```

Swith to the repo folder
```
cd esb-programming-test
```

Install all the dependencies using composer
```
composer install
```

Copy the example env file and make the required configuration changes in the .env file
```
cp .env.example .env
```

Generate a new application key
```
php artisan key:generate
```

Run the database migrations (Set the database connection in .env before migrating)
```
php artisan migrate:fresh --seed
```

Install NPM dependencies and run vite development server
```
npm install && npm run dev
```

Start the local development server
```
php artisan serve
```

You can now access the server at http://localhost:8000. Use the following credential to login:
```
email: randijulio13@gmail.com
password: password
```

## API Usage
You cant hit `{base_url/api/invoice}` using `GET` method to get all invoice data <br />
Response example:
```
{
    "data": [
        {
            "subject": "Spring Marketing Campaign",
            "customer_id": 1,
            "issue_date": "2017-05-06",
            "due_date": "2017-05-06",
            "subtotal": 28510,
            "tax": 10,
            "total": 31361,
            "payments": null,
            "amount_due": 0,
            "is_paid": 0
        }
    ]
}
```

You can also hit `{base_url/api/invoice/:id}` using `GET` method to get detailed invoice information <br />
Response example:
```
{
    "data": {
        "subject": "Spring Marketing Campaign",
        "customer_id": 1,
        "issue_date": "2017-05-06",
        "due_date": "2017-05-06",
        "subtotal": 28510,
        "tax": 10,
        "total": 31361,
        "payments": null,
        "amount_due": 0,
        "is_paid": 0,
        "items": [
            {
                "name": "Design",
                "type": "Service",
                "price": "230",
                "quantity": 8
            },
            {
                "name": "Development",
                "type": "Service",
                "price": "330",
                "quantity": 2
            },
            {
                "name": "Meetings",
                "type": "Service",
                "price": "60",
                "quantity": 5
            }
        ]
    }
}
```