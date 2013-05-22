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
* replace _:id_ with model id, ( **must be integer** !)
* do not send an id on POST, server.php will create it for you and send it in the response body
* response header code is alway 200 "OK" for now, will add more codes later

#### logging

requests are logged into log.txt , so take a look what's going on!



### client side

* example coffeescript/ backbone notes app ( _index.html + js-Directory + css -dir_ )




	Copyright (c) <year> <copyright holders>
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.