requirejs.config
    baseUrl: 'js/lib',
    paths: 
        app: '../app'
    



requirejs ['jquery', 'underscore','backbone', 'app/notes'],
	($, Note) ->
		note = new Note {"text": "Nur ein Test"}
		console.log note.toJSON()		      	


 