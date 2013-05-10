define ['backbone','jquery'], (Backbone, $)->
	class Note extends Backbone.Model
		initialize: ->
			# init stuff
		defaults:
			text: ""
			pos:
				x: 0
				y: 0
			size:
				width: 100
				height: 100	