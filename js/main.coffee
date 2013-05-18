app = app || {}


$ ->
	app.notes = new app.Notes()
	app.deskView = new app.DeskView()
	app.notes.fetch()
	hljs.initHighlightingOnLoad()