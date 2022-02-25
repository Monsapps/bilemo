# Common endpoints

All endpoints usable by everyone (admin, clients, users)

## GET /products

### Parameters: in query (optional)

*   keyword: the keyword search for.

    *   type: alphanumeric
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
            "name": "Product",
            "brand": "Brand name",
            "_links": {
                "self": {
                    "href": "http://bilemo.local/products/0"
                },
                "modify": {
                    "href": "http://bilemo.local/products/0"
                },
                "delete": {
                    "href": "http://bilemo.local/products/0"
                }
            }
        }
    ],
    "_links": {
        "current_page": {
            "href": "http://bilemo.local/products?page=1"
        },
        "next_page": {
            "href": "http://bilemo.local/products?page=2"
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
Note: "modify" and "delete" links is only visible for administrator.

## GET /products/{id}

Get product details.

{id}: the unique identifier of the product.

### Return value

```json
{
    "id": 0,
    "name": "Product",
    "brand": "Brand name",
    "details": "Example details",
    "release_date": "2022-02-15T10:57:13+01:00"
}
```
