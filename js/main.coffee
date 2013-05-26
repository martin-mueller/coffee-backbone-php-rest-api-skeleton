app = app || {}


$ ->
	app.widgets = new app.Widgets()
	app.deskView = new app.DeskView { collection : app.widgets }
	# normally, we do app.widgets.reset(app.data) because data is still there, but buggy when db empty
	app.widgets.fetch()
