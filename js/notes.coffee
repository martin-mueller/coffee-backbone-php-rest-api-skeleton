class Note extends Backbone.Model
	urlRoot: 'server.php/notes'

	defaults:
		text: ""
		pos:
			x: 0
			y: 0
		size:
			width: 100
			height: 100

	initialize: ->
		@on "all", (e) -> console.log "Note event:" + e + ' text:' + @get "text"



	validate: (attrs, options) ->
		# if validate returns anything, save will not be executed

class Notes extends Backbone.Collection
	url: 'server.php/notes'
	model: Note
