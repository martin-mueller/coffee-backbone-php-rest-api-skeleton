app = app || {}


$ ->
	app.widgets = new app.Widgets()
	app.deskView = new app.DeskView { collection : app.widgets }
	app.widgets.reset(app.data);
