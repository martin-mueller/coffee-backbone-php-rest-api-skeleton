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
			@on "change:text", @saveText
			@on "all", (e) -> console.log "Note event:" + e

		savePos: ->
			@save @pos
		
		saveText: ->
			@save @text

		validate: (attrs, options) ->
			# if validate returns anything, save will not be executed

	class app.Notes extends Backbone.Collection
		url: 'server.php/notes'
		model: app.Note


	class app.NoteView extends Backbone.View
		template: 	_.template($('#item-template').html())
		className:	"notes ui-widget-content"
		isDragging:	false
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

		enableEdit: ->
			if not @isDragging
				$('.marked',@el).hide()
				$('textarea',@el).show()

		editDone: ->
			@model.set("text",$('textarea',@el).val())
			@showMarked()		

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
			@model.set("pos",position)		

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
			noteView.showMarked()

# some functions here ;)
	delay = (ms, func) -> setTimeout func, ms
