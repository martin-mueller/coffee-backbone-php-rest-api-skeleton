app = app || {}
$ ->
	class app.Note extends Backbone.Model
		urlRoot: 'server.php/notes'

		defaults:
			"text": ""
			"pos":
				"x": 0
				"y": 0
			"size":
				"width": 100
				"height": 100
			"z": 1

		initialize: ->
			@on "change:pos", @savePos
			@on "change:size", @saveSize
			@on "change:text", @saveText
			@on "all", (e) -> console.log "Note event:" + e

		savePos: ->
			console.log @get("z")
			@save @pos

		saveSize: ->
			@save @size

		saveText: ->
			@save @text

		validate: (attrs, options) ->
			# if validate returns anything, save will not be executed

	class app.Notes extends Backbone.Collection
		url: 'server.php/notes'
		model: app.Note
		editEl:	null

	class app.NoteView extends Backbone.View
		template: 	_.template($('#item-template').html())
		className:	"notes ui-widget-content"
		isDragging:	false
		isResizing: false

		events:
			"click .close"	: "clear"
			"mouseup"		: "enableEdit"
			"focusout"		: "editDone"
		
		initialize: ->
			@listenTo @model, 'destroy', @remove
			@listenTo @model, 'change:id', @render
			@render()

		render: ->
			@$el.html @template(@.model.toJSON())
			@$el.offset @model.get "pos"
			@$el.css "width", (@model.get "size").  width
			@$el.css "height", (@model.get "size"). height
			# @$el.css 'z-index', @model.get "z"

		enableEdit: ->
			if not @isDragging and not @isResizing
				if app.notes.editEl isnt null
					app.notes.editEl.editDone()
				$('.marked',@el).hide()
				$('textarea',@el).show().focus()
				@oldText = $('textarea',@el).val()
				app.notes.editEl = @


		editDone: ->
			text = $('textarea',@el).val()
			@model.set("text",text) if text isnt @oldText
			@showMarked()
			app.notes.editEl = null

		showMarked: ->
			text_v   = $('textarea',@el).val()
			marked_v = marked(text_v)
			$('.marked',@el).html(marked_v)
			$('textarea',@el).hide()
			$('.marked',@el).show()


		startDrag: ->
			@isDragging = true
			

		stopDrag: (position) ->
			delay 100, => @isDragging = false
			@model.set("pos", position)
			z = @$el.css('z-index')
			@model.set("z", z)		
		
		startResize: ->
			@isResizing = true
			

		stopResize: (size) ->
			delay 100, => @isResizing = false
			@model.set("size", size)
			

		clear: (e) ->
			e.stopImmediatePropagation()
			@model.destroy()				

	class app.DeskView extends Backbone.View
		initialize: ->
			@listenTo app.notes, 'add', @addOne
			# markdown options
			marked.setOptions breaks: true


		el: '#wrapper'

		events:
			"click #add"		: "addNote"
			"add"				: "addOne"

		addNote: ->
			app.notes.create()
			
		
		addOne: (note) ->
			noteView = new app.NoteView { model: note, id: "note-" + note.cid }
			$('#wrapper').append(noteView.el)
			noteView.$el.draggable
				stack: ".notes"
				delay: 100
				start: (event, ui) ->
					noteView.startDrag()
				stop:  (event, ui) ->
					noteView.stopDrag ui.position
			.resizable
				start: (event, ui) ->
					noteView.startResize()
				stop:  (event, ui) ->
					noteView.stopResize ui.size

			noteView.showMarked()

# some functions here ;)
	delay = (ms, func) -> setTimeout func, ms
	return
	
