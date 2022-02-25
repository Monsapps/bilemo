# Client endpoints

All endpoints usable by clients

## GET /users

Get the list of all users.

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
                    "href": "http://bilemo.local/users/0"
                },
                "modify": {
                    "href": "http://bilemo.local/users/0"
                },
                "delete": {
                    "href": "http://bilemo.local/users/0"
                }
            }
        }
    ],
    "_links": {
        "current_page": {
            "href": "http://bilemo.local/users?page=1"
        },
        "next_page": {
            "href": "http://bilemo.local/users?page=2"
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

## GET /users/{id}

Get user details.

{id} : the unique identifier of the user.

### Return value

```json
{
    "id": 0,
    "username": "Username",
    "email": "username@example.com"
}
```

## POST /users

Add user.

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
    "username": "john",
    "email": "john@example.com",
    "password": "password_1234",
}
```

### Return value

```json
{
    "id": 1,
    "username": "john",
    "email": "john@example.com"
}
```

## PATCH /users/{id}

Update the desired user details.

{id} : the unique identifier of the user.

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
    "username": "john doe"
}
```

### Return value

```json
{
    "id": 1,
    "username": "john doe",
    "email": "username@example.com"
}
```

## DELETE /users/{id}

Delete user.

{id} : the unique identifier of the user.
