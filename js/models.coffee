app = app || {}
$ ->
	class app.Widget extends Backbone.Model
		urlRoot: 'server.php/notes'
		defaults:
			"type": "note"
			"text": ""
			"pos":
				"top": 150
				"left": 200
			"size":
				"width": 250
				"height": 250
			"z-index": 1

		initialize: ->
			@on "change:pos", @savePos
			@on "change:size", @saveSize
			@on "change:text", @saveText
			@on "all", (e) -> console.log "Note event:" + e

		savePos: ->
			console.log @get("z-index")
			@save @pos

		saveSize: ->
			@save @size

		saveText: ->
			@save @text

		validate: (attrs, options) ->
			# if validate returns anything, save will not be executed


	class app.Note extends app.Widget



	class app.Widgets extends Backbone.Collection
		url: 'server.php/notes'
		model: (attrs, options) ->
			switch attrs.type
  				when 'note' then new app.Note attrs, options

		# the collection saves some states of the app here
		# editEl is set when a note is in edit mode
		editEl:	null
		# isLocked is set when editing elements is disabled (lock button of the deskView)
		isLocked: false

		
