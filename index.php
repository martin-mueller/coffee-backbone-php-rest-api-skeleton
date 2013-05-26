<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Coffee Backbone Project</title>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"/>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>

<body>
<div id="wrapper">
	<div id="controls">
			<button id="add" title="add a new note"><span class="ui-icon ui-icon-plus"></span></button>
			<button id="toggleEdit" title="toggle edit lock"><span class="ui-icon ui-icon-unlocked"></span></button>
		<button id="help" title="faq"><span class="ui-icon ui-icon-help"></span></button>
		<button id="upLoadImage"><span class="ui-icon ui-icon-image "></span></button>
		<button id="trash"><span class="ui-icon ui-icon-trash "></span></button>
	</div>

	<div id="desk">
	</div>

	<script type="text/template" id="item-template">
		<button class="close ui-icon ui-icon-closethick" href="#"></button>
		<textarea><%- text %></textarea>
		<div class="marked"></div>
	</script>
</div>
	<script>
		var app = app || {};
		app.data = <?php echo file_get_contents('http://localhost/coffee-bb-skel/server.php/notes/')?>;
	</script>

	<script type="text/javascript" src="js/lib/jquery.js"></script>
	<script type="text/javascript" src="js/lib/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/lib/underscore.js"></script>
	<script type="text/javascript" src="js/lib/backbone.js"></script>
	<script type="text/javascript" src="js/lib/marked.js"></script>
	<script type="text/javascript" src="js/models.js"></script>
	<script type="text/javascript" src="js/views.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

	

</body>
</html>