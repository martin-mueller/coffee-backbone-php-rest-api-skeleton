# Simple coffeescript backbone.js example app with php-REST api

## description

### server side (file: server.php)

* Single file php - REST and database api (https://github.com/martin-mueller/coffee-backbone-php-rest-api-skeleton/blob/simple/server.php)
* takes model requests from backbone client
* REST -> CRUD data	from simple id, key->value table
* nearly **zero config** , you only have to define allowed models,i.e.:

```$allowed_models = array('notes');```

	no database setup, no data structure setup



### client side

* example coffeescript/ backbone notes app (_index.html + js-Directory + css -dir_)

