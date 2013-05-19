app = app || {}


$ ->
	app.notes = new app.Notes()
	app.deskView = new app.DeskView { collection : app.notes }
	app.notes.fetch()
	
	
	
