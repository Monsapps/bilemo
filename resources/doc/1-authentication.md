# Authentication

BileMo API require JWT token to access of content

## Usage

### 1. Obtain the token

The first step is to authenticate the user using its credentials.

You can test getting the token with a simple curl command like this (adapt host and port):

Linux or macOS
```bash
curl -X POST -H "Content-Type: application/json" http://bilemo.local/login -d '{"username":"admin","password":"pass_1234"}'
```
Windows
```bash
curl -X POST -H "Content-Type: application/json" http://bilemo.local/login --data {\"username\":\"admin\",\"password\":\"pass_1234\"}
```

If it works, you will receive something like this:

```json
{
   "token" : "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0MzQ3Mjc1MzYsInVzZXJuYW1lIjoia29ybGVvbiIsImlhdCI6IjE0MzQ2NDExMzYifQ.nh0L_wuJy6ZKIQWh6OrW5hdLkviTs1_bau2GqYdDCB0Yqy_RplkFghsuqMpsFls8zKEErdX5TYCOR7muX0aQvQxGQ4mpBkvMDhJ4-pE4ct2obeMTr_s4X8nC00rBYPofrOONUOR4utbzvbd4d2xT_tj4TdR_0tsr91Y7VskCRFnoXAnNT-qQb7ci7HIBTbutb9zVStOFejrb4aLbr7Fl4byeIEYgp2Gd7gY"
}
```

Store it (client side), the JWT is reusable until its ttl has expired (3600 seconds by default).

Some credentials for testing (loaded by doctrine fixtures)
```text
BILEMO_ROLE -> admin:pass_1234
CLIENT_ROLE -> client:pass_1234
USER_ROLE -> username0;pass_1234
```

### 2. Use the token

Simply pass the JWT on each request, either as an authorization header
or as a query parameter : `Authorization: Bearer {token}`
