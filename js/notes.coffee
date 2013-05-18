app = app || {}
$ ->
	class app.Note extends Backbone.Model
		urlRoot: 'server.php/notes'

		defaults:
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

	class app.Notes extends Backbone.Collection
		url: 'server.php/notes'
		model: app.Note
		editEl:	null
		isLocked: false

	class app.NoteView extends Backbone.View
		template: _.template($('#item-template').html())
		className:	"notes ui-widget-content"
		isDragging:	false
		isResizing: false

		events:
			"mousedown .close"	: "clear"
			"mouseup .close"	: "stopClear"
			"click .marked"		: "enableEdit"
			"focusout"			: "editDone"
		
		initialize: ->
			@listenTo @model, 'destroy', @remove
			# @listenTo @model, 'change', @render
			@render()

		render: ->
			@$el.html   @template(@.model.toJSON())
			@$el.offset @model.get "pos"
			@$el.width  @model.get("size").width
			@$el.height @model.get("size").height
			# return $el for chaining (?)
			return @$el
			# @$el.css 'z-index', @model.get "z"

		enableEdit: (e) ->
			if not @isDragging and not @isResizing and not app.notes.isLocked
				# prevent default in case a link in the marked el was clicked
				e.preventDefault()
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
			@model.set("z-index", @$el.css('z-index'))		
			@model.set("pos", position)

		
		startResize: ->
			@isResizing = true
			

		stopResize: (size) ->
			delay 100, => @isResizing = false
			@model.set("size", size)
			

		clear: (e) ->
			@$el.fadeOut 1000, =>
				@model.destroy()

		stopClear: (e) ->
			@$el.stop().css("opacity","1");


	class app.DeskView extends Backbone.View
		initialize: ->
			@listenTo app.notes, 'add', @addOne
			# markdown options
			marked.setOptions breaks: true


		el: '#wrapper'

		events:
			"click #add"		: "createNote"
			"add"				: "addOne"
			"click #toggleEdit"	: "toggleEdit"

		createNote: ->
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
			noteView.$el.resizable
				start: (event, ui) ->
					noteView.startResize()
				stop:  (event, ui) ->
					noteView.stopResize ui.size
			noteView.showMarked()

		toggleEdit: ->
			if app.notes.isLocked 
				app.notes.isLocked = false;
				$("#toggleEdit span").removeClass("ui-icon-locked")
								.addClass("ui-icon-unlocked")
			else					
				app.notes.isLocked = true;
				$("#toggleEdit span").removeClass("ui-icon-unlocked")
								.addClass("ui-icon-locked")


# some functions here ;)
	delay = (ms, func) -> setTimeout func, ms
	return
	
