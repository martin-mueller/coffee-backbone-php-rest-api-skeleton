# Simple coffeescript backbone.js example app with php-REST api

## description

### server side (file: server.php)

* Single file php - REST and database api (https://github.com/martin-mueller/coffee-backbone-php-rest-api-skeleton/blob/simple/server.php)
* takes model requests from backbone client
* REST -> CRUD data	from simple id, key->value table
* nearly **zero config** , you only have to define allowed models,i.e.:

```$allowed_models = array('notes');```

	no database setup, no data structure setup

#### doing requests

* urls for models and collection are the same

_Notes example_  (coffeescript with backbone.js)

````coffeescript
class app.Note extends Backbone.Model
urlRoot: 'server.php/notes'
````

````coffeescript
class app.Notes extends Backbone.Collection
url: 'server.php/notes'
````

* request format is always **application/json**

**Routes**

route/ method	|   GET    | POST          |   PUT    |   PATCH    |   DELETE
----------------|----------|---------------|----------|------------|------
/:model/:id     | get one  |  create one   |update one| update one| delete one
                |          |  sends back id|          |           |
/:model/        | get all  | n.a.          |not implemented| n.a.| n.a
            
* replace _:model_ with your model name (plural)
* replace _:id_ with model id, ( **must be numeric** !)

### client side

* example coffeescript/ backbone notes app ( _index.html + js-Directory + css -dir_ )

