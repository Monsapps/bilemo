# Administrator endpoints

All endpoints usable by administrator

## POST /products

Add product.

### Parameters: in body

*   name: name of product

    *   type: string
    *   require: true

*   brand: brand of product

    *   type: string
    *   require: true

*   details: details of product

    *   type: string
    *   require: true

*   releaseDate: release date of product

    *   type: datetime
    *   require: true

Example body
```json
{
    "name": "New product",
    "brand": "New brand",
    "details": "New product details",
    "releaseDate": "2022-02-25 12:00:00"
}
```

### Return value

```json
{
    "id": 1,
    "name": "New product",
    "brand": "New brand",
    "details": "New product details",
    "release_date": "2022-02-25T12:00:00+01:00"
}
```

## PACTH /products/{id}

Update the desired product details.

{id}: the unique identifier of the product.

### Parameters: in body

*   name: name of product

    *   type: string

*   brand: brand of product

    *   type: string

*   details: details of product

    *   type: string

*   releaseDate: release date of product

    *   type: datetime

Example body
```json
{
    "name": "New product updated"
}
```

### Return value

```json
{
    "id": 1,
    "name": "New product updated",
    "brand": "New brand",
    "details": "New product details",
    "release_date": "2022-02-25T12:00:00+01:00"
}
```

## DELETE /products/{id}

Delete product.

{id}: the unique identifier of the products.

## GET /clients

Get the list of all clients.

### Parameters: in query (optional)

*   keyword: the keyword search for.

    *   type: string
    *   default value: null

*   order: sort order (asc or desc)

    *   type: string
    *   default value: asc

*   limit: max products per page.

    *   type: integer
    *   default value: 15

*   page: the page number.

    *   type: integer
    *   default value: 1

    
### Return value

```json
{
    "data:": [
        {
            "id": 20,
            "useranme": "Client username",
            "email": "client@example.com",
            "_links": {
                "self": {
                    "href": "http://bilemo.local/clients/20"
                },
                "modify": {
                    "href": "http://bilemo.local/clients/20"
                },
                "delete": {
                    "href": "http://bilemo.local/clients/20"
                }
            }
        }
    ],
    "_links": {
        "current_page": {
            "href": "http://bilemo.local/clients?page=1"
        },
        "next_page": {
            "href": "http://bilemo.local/clients?page=2"
        }
    },
    "meta": {
        "limit": 15,
        "current_items": 15,
        "total_items": 50,
        "total_pages": 4
    }
}
```

## GET /clients/{id}

Get client details.

{id} : the unique identifier of the client.

### Return value

```json
{
    "id": 20,
    "username": "Client username",
    "email": "client@example.com"
}
```

## POST /clients

Add client.

### Parameters: in body

*   username:

    *   type: string

*   email:

    *   type: email

*   password:

    *   type: string

Example body
```json
{
    "username": "New client",
    "email": "new-client@example.com",
    "password": "password_1234",
}
```

### Return value

```json
{
    "id": 21,
    "useranme": "New client",
    "email": "new-client@example.com"
}
```

## PATCH /clients/{id}

Update the desired client details.

{id} : the unique identifier of the client.

### Parameters: in body

*   username:

    *   type: string

*   email:

    *   type: email

*   password:

    *   type: string

Example body
```json
{
    "username": "Old client"
}
```

### Return value

```json
{
    "id": 1,
    "username": "Old client",
    "email": "new-client@example.com"
}
```

## DELETE /clients/{id}

Delete client.

{id} : the unique identifier of the client.

## GET /clients/{id_client}/users

Get the list of client users

{id_client} : the unique identifier of the client.

### Parameters: in query (optional)

*   keyword: the keyword search for.

    *   type: string
    *   default value: null

*   order: sort order (asc or desc)

    *   type: string
    *   default value: asc

*   limit: max products per page.

    *   type: integer
    *   default value: 15

*   page: the page number.

    *   type: integer
    *   default value: 1

    
### Return value

```json
{
    "data:": [
        {
            "id": 0,
            "useranme": "Username",
            "email": "username@example.com",
            "_links": {
                "self": {
                    "href": "http://bilemo.local/clients/20/users/0"
                },
                "modify": {
                    "href": "http://bilemo.local/clients/20/users/0"
                },
                "delete": {
                    "href": "http://bilemo.local/clients/20/users/0"
                }
            }
        }
    ],
    "_links": {
        "current_page": {
            "href": "http://bilemo.local/clients/20/users?page=1"
        },
        "next_page": {
            "href": "http://bilemo.local/clients/20/users?page=2"
        }
    },
    "meta": {
        "limit": 15,
        "current_items": 15,
        "total_items": 50,
        "total_pages": 4
    }
}
```

## GET /clients/{id_client}/users/{id_user}

Get client details.

{id_client} : the unique identifier of the client.

{id_user} : the unique identifier of the user client.

### Return value

```json
{
    "id": 0,
    "useranme": "Username",
    "email": "username@example.com"
}
```

## POST /clients/{id_client}/users

Add user client.

{id_client} : the unique identifier of the client.

### Parameters: in body

*   username:

    *   type: string

*   email:

    *   type: email

*   password:

    *   type: string

Example body
```json
{
    "username": "New user client",
    "email": "new-user-client@example.com",
    "password": "password_1234",
}
```

### Return value

```json
{
    "id": 45,
    "useranme": "New user client",
    "email": "new-user-client@example.com"
}
```

## PATCH /clients/{id_client}/users/{id_client}

Update the desired user client details.

{id} : the unique identifier of the client.

{id_user} : the unique identifier of the user client.

### Parameters: in body

*   username:

    *   type: string

*   email:

    *   type: email

*   password:

    *   type: string

Example body
```json
{
    "username": "Old user client"
}
```

### Return value

```json
{
    "id": 45,
    "username": "Old user client",
    "email": "new-user-client@example.com"
}
```

## DELETE /clients/{id_client}/users/{id_user}

Delete user client.

{id_client} : the unique identifier of the client.

{id_user} : the unique identifier of the user client.
