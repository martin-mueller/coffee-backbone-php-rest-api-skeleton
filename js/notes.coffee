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
			@on "change:pos", @savePos
			@on "all", (e) -> console.log "Note event:" + e

		savePos: ->
			@save @pos
				

		validate: (attrs, options) ->
			# if validate returns anything, save will not be executed

	class app.Notes extends Backbone.Collection
		url: 'server.php/notes'
		model: app.Note


	class app.NoteView extends Backbone.View
		template: 	_.template($('#item-template').html())
		className:	"draggable ui-widget-content"
		isDragging:	false
		events:
			"click .close"	: "clear"

		initialize: ->
			@listenTo @model, 'destroy', @remove
			@listenTo @model, 'change:id', @render

			@render()
			console.log @

		render: ->
			@$el.html @template(@.model.toJSON())
			@$el.offset @model.get "pos"

		startDrag: ->
			@isDragging = true
			

		stopDrag: (position) ->
			@isDragging = false
			@model.set("pos",position)

			
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
			app.notes.create({text: 'test'})
			
		
		addOne: (note) ->
			noteView = new app.NoteView { model: note, id: "note-" + note.cid }
			$('#wrapper').append(noteView.el)
			noteView.$el.draggable
				stack: ".draggable"
				delay: 100
				start: (event, ui) ->
					noteView.startDrag
				stop:  (event, ui) ->
					noteView.stopDrag ui.position


