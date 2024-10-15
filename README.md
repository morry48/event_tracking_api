

## Init Setting（Using Docker for Mac）

```
cd {project root} （event_tracking_api or event_tracking_api-main）
cp ./src/.env.example ./src/.env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate

```

## SetUp Database

```
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed --class InitSeeder
docker compose exec db mysql -hlocalhost -proot -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'user'@'%' WITH GRANT OPTION; FLUSH PRIVILEGES;"
docker compose exec db mysql -hlocalhost -proot -uroot -e "CREATE DATABASE IF NOT EXISTS test_event_tracking_db;"
```

## unit test


```

docker compose exec app ./vendor/bin/phpunit app

```


## API specifications

- openapi
    - https://github.com/morry48/event_tracking_api/blob/main/src/openapi.yaml



## Quick Manual Test

#### test user 


- staff_1@test.com（password）
- owner_1@test.com（password）
- warehouse_1@test.com（password）

### how to use

```
curl -X GET "localhost:8000/api/shipments \
  -H "Authorization: Bearer " \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"

```


- [POST]localhost:8000/api/login


```
request body

{
  "email": "staff_1@test.com",
  "password": "com（password）"
}

```


- [GET]localhost:8000/api/shipments

```
respons body


[
    {
        "id": "4567af17-ca8e-4c57-aa7f-579d0533adca",
        "internal_reference_name": "shipment_3",
        "user_id": "8978e39a-4d1d-4ece-80a4-12b7130ba21b",
        "events": [
            {
                "event_type": "port_arrival",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            },
            {
                "event_type": "port_departure",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            },
            {
                "event_type": "preparation",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            },
            {
                "event_type": "delivery",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            }
        ]
    },
    {
        "id": "974a0620-a94c-4eeb-847c-c33b487da3a4",
        "internal_reference_name": "shipment_1",
        "user_id": "d296f538-fccd-402f-8cd6-8f7f4790ca46",
        "events": [
            {
                "event_type": "delivery",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            },
            {
                "event_type": "preparation",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            },
            {
                "event_type": "port_departure",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            },
            {
                "event_type": "port_arrival",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            }
        ]
    },
    {
        "id": "bb015804-75c3-477a-8c9d-11a50f2123af",
        "internal_reference_name": "shipment_2",
        "user_id": "d296f538-fccd-402f-8cd6-8f7f4790ca46",
        "events": [
            {
                "event_type": "port_arrival",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            },
            {
                "event_type": "preparation",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            },
            {
                "event_type": "port_departure",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            },
            {
                "event_type": "delivery",
                "actual_started_at": "2024-10-15 22:45:59",
                "actual_completion_at": "2024-10-15 22:45:59"
            }
        ]
    }
]

```




- [POST]localhost:8000/api/shipments

```
request body

{
  "internal_reference_name": "2e3s2eeeeeeee23",
  "events": {
    "preparation": {
      "estimated_started_at": "2024-10-12 11:12:05",
      "estimated_completion_at": "2024-10-12 11:12:05"
    },
    "port_departure": {
      "estimated_started_at": "2024-10-12 11:12:05",
      "estimated_completion_at": "2024-10-12 11:12:05"
    },
    "port_arrival": {
      "estimated_started_at": "2024-10-12 11:12:05",
      "estimated_completion_at": "2024-10-12 11:12:05"
    },
    "delivery": {
      "estimated_started_at": "2024-10-12 11:12:05",
      "estimated_completion_at": "2024-10-12 11:12:05"
    }
  }
}

```

- [GET]localhost:8000/api/shipments/{shipment_id}


```

response body

{
    "id": "3fecb3ac-9acc-4590-ae8c-ca5e6e71425e",
    "internal_reference_name": "2e3s2eeeeeeee23",
    "user_id": "20ef73b1-9db6-47ac-bafd-95ab11d961ec",
    "events": {
        "delivery": {
            "estimated_started_at": "2024-10-12 11:12:05",
            "actual_started_at": null,
            "estimated_completion_at": "2024-10-12 11:12:05",
            "actual_completion_at": null
        },
        "port_arrival": {
            "estimated_started_at": "2024-10-12 11:12:05",
            "actual_started_at": null,
            "estimated_completion_at": "2024-10-12 11:12:05",
            "actual_completion_at": null
        },
        "port_departure": {
            "estimated_started_at": "2024-10-12 11:12:05",
            "actual_started_at": null,
            "estimated_completion_at": "2024-10-12 11:12:05",
            "actual_completion_at": null
        },
        "preparation": {
            "estimated_started_at": "2024-10-12 11:12:05",
            "actual_started_at": null,
            "estimated_completion_at": "2024-10-12 11:12:05",
            "actual_completion_at": null
        }
    }
}

```

- [PUT]localhost:8000/api/shipments/{shipment_id}

```
request body

{
  "internal_reference_name": "updarte_shipment_name",
  "events": {
    "preparation": {
      "actual_started_at": "2024-10-09 01:00:00",
      "actual_completion_at": "2024-10-10 01:00:00"
    }
  }
}



```


# Code Review Comments

## Key Considerations

### 1. **Is the code written with team development in mind?**
   - **Following the Inverse Conway’s Law with a package-by-feature architecture**
     - Features are packaged independently, making it easier for the team to work on distinct functionalities in parallel.

### 2. **Is it highly readable and maintainable?**
   - **Code is written following Laravel conventions and PSR-12**
     - Ensures consistent coding style and improves readability and maintainability.

### 3. **Is the implementation designed for actual use cases?**
   - **Fields like delivery are nullable, recognizing they won’t be finalized at the time of registration**
     - The design reflects real-world business processes.
   - **More events like Arrival Notice or Import Declaration are expected to be managed in the future**
     - The architecture anticipates the need for additional events, ensuring future scalability.
   - **Role-permission architecture for flexibility**
     - By attaching permissions to roles, roles like 'Warehouse Manager' and 'General Warehouse Staff' can be added or changed later without increasing complexity.
     - Business logic remains manageable even with future modifications.

### 4. **Is the code designed so that the development speed does not slow down even if the number of members increases?**
   - **Package by feature architecture**
     - Dividing the code by feature helps keep the development speed consistent, even as the team size grows.

### 5. **Is the code scalable to handle a 100-fold increase in the number of data?**
   - **Use of MySQL indexes**
     - Indexes are applied to improve performance as data volume increases.
   - **Avoiding N+1 query issues**
     - The code ensures optimal data fetching performance, handling large amounts of data efficiently.

---

## Additional Comments

### 1. **Strong typing for request and UseCase inputs**
   - Defining types for the data passed to requests and UseCases increases robustness.

---

## TODOs
- **Introduce static analysis and code formatters** to ensure code quality and style consistency.
- **Set up a CI/CD pipeline** for automated testing and deployment.
- **Create make commands** for common tasks to streamline the development process.

---

## Architecture Comments
- **Defining a custom type for fetching only the delivery event** can add robustness.
- **Bulk insert may be necessary for CSV batch registration** to handle large datasets efficiently.
- **`updated_at` for events might be better handled by a separate column for user operations**, as it may be updated by automated batch jobs.
- **For simplicity, the current UseCase architecture relies on ORM**. However, if business logic becomes more complex, separating pure domain objects from the infrastructure layer would improve scalability.
   - **In Go**, this would look something like this: [Go Example](https://github.com/morry48/api_det_voc).

---

## Unit Tests
- **Classical approach to testing** is preferred over London-style tests (mock-heavy).
- **Database is actively used in tests**, and dividing the code into packages helps keep the tests fast and focused.
