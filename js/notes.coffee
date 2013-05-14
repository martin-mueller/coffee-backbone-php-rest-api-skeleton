app = app || {}
$ ->
	class app.Note extends Backbone.Model
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

	class app.Notes extends Backbone.Collection
		url: 'server.php/notes'
		model: app.Note

	app.notes = new app.Notes

	class app.NoteView extends Backbone.View
		initialize: ->
			@listenTo @model, 'destroy', @remove
			@$el.html @.template(@.model.toJSON())
		

		template: _.template($('#item-template').html()),	
		events:
			"click .close"	: "clear"


		clear: (e) ->
			e.stopImmediatePropagation()
			@model.destroy()				

	class app.DeskView extends Backbone.View
		initialize: ->
			@listenTo app.notes, 'add', @addOne

		el: '#wrapper'

		events:
			"click #add"		: "addNote"
			"add"				: "addOne"

		addNote: ->
			console.log "Add Clicked"
			app.notes.create({text: 'test'})
			
		
		addOne: (note) ->
			view = new app.NoteView { model: note, id: "note-" + note.cid }
			$('#wrapper').append(view.el)
			view.$el.draggable()

