// Generated by CoffeeScript 1.6.2
var app,
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

app = app || {};

$(function() {
  var delay, _ref, _ref1, _ref2, _ref3;

  app.Note = (function(_super) {
    __extends(Note, _super);

    function Note() {
      _ref = Note.__super__.constructor.apply(this, arguments);
      return _ref;
    }

    Note.prototype.urlRoot = 'server.php/notes';

    Note.prototype.defaults = {
      "text": "",
      "pos": {
        "top": 150,
        "left": 200
      },
      "size": {
        "width": 250,
        "height": 250
      },
      "z-index": 1
    };

    Note.prototype.initialize = function() {
      this.on("change:pos", this.savePos);
      this.on("change:size", this.saveSize);
      this.on("change:text", this.saveText);
      return this.on("all", function(e) {
        return console.log("Note event:" + e);
      });
    };

    Note.prototype.savePos = function() {
      console.log(this.get("z-index"));
      return this.save(this.pos);
    };

    Note.prototype.saveSize = function() {
      return this.save(this.size);
    };

    Note.prototype.saveText = function() {
      return this.save(this.text);
    };

    Note.prototype.validate = function(attrs, options) {};

    return Note;

  })(Backbone.Model);
  app.Notes = (function(_super) {
    __extends(Notes, _super);

    function Notes() {
      _ref1 = Notes.__super__.constructor.apply(this, arguments);
      return _ref1;
    }

    Notes.prototype.url = 'server.php/notes';

    Notes.prototype.model = app.Note;

    Notes.prototype.editEl = null;

    Notes.prototype.isLocked = false;

    return Notes;

  })(Backbone.Collection);
  app.NoteView = (function(_super) {
    __extends(NoteView, _super);

    function NoteView() {
      _ref2 = NoteView.__super__.constructor.apply(this, arguments);
      return _ref2;
    }

    NoteView.prototype.template = _.template($('#item-template').html());

    NoteView.prototype.className = "notes ui-widget-content";

    NoteView.prototype.isDragging = false;

    NoteView.prototype.isResizing = false;

    NoteView.prototype.events = {
      "mousedown .close": "clear",
      "mouseup .close": "stopClear",
      "click .marked": "enableEdit",
      "focusout": "editDone"
    };

    NoteView.prototype.initialize = function() {
      this.listenTo(this.model, 'destroy', this.remove);
      return this.render();
    };

    NoteView.prototype.render = function() {
      this.$el.html(this.template(this.model.toJSON()));
      this.$el.offset(this.model.get("pos"));
      this.$el.width(this.model.get("size").width);
      this.$el.height(this.model.get("size").height);
      return this.$el;
    };

    NoteView.prototype.enableEdit = function(e) {
      if (!this.isDragging && !this.isResizing && !this.collection.isLocked) {
        e.preventDefault();
        if (this.collection.editEl !== null) {
          this.collection.editEl.editDone();
        }
        $('.marked', this.el).hide();
        $('textarea', this.el).show().focus();
        this.oldText = $('textarea', this.el).val();
        return this.collection.editEl = this;
      }
    };

    NoteView.prototype.editDone = function() {
      var text;

      text = $('textarea', this.el).val();
      if (text !== this.oldText) {
        this.model.set("text", text);
      }
      this.showMarked();
      return this.collection.editEl = null;
    };

    NoteView.prototype.showMarked = function() {
      var $m, marked_v, text_v;

      $m = $('.marked', this.el);
      text_v = $('textarea', this.el).val();
      marked_v = marked(text_v);
      $m.html(marked_v);
      $('textarea', this.el).hide();
      return $m.show();
    };

    NoteView.prototype.startDrag = function() {
      return this.isDragging = true;
    };

    NoteView.prototype.stopDrag = function(position) {
      var _this = this;

      delay(100, function() {
        return _this.isDragging = false;
      });
      this.model.set("z-index", this.$el.css('z-index'));
      return this.model.set("pos", position);
    };

    NoteView.prototype.startResize = function() {
      return this.isResizing = true;
    };

    NoteView.prototype.stopResize = function(size) {
      var _this = this;

      delay(100, function() {
        return _this.isResizing = false;
      });
      return this.model.set("size", size);
    };

    NoteView.prototype.clear = function(e) {
      var _this = this;

      return this.$el.fadeOut(1000, function() {
        return _this.model.destroy();
      });
    };

    NoteView.prototype.stopClear = function(e) {
      return this.$el.stop().css("opacity", "1");
    };

    return NoteView;

  })(Backbone.View);
  app.DeskView = (function(_super) {
    __extends(DeskView, _super);

    function DeskView() {
      _ref3 = DeskView.__super__.constructor.apply(this, arguments);
      return _ref3;
    }

    DeskView.prototype.initialize = function() {
      this.listenTo(this.collection, 'add', this.addOne);
      return marked.setOptions({
        breaks: true
      });
    };

    DeskView.prototype.el = '#wrapper';

    DeskView.prototype.events = {
      "click #add": "createNote",
      "add": "addOne",
      "click #toggleEdit": "toggleEdit"
    };

    DeskView.prototype.createNote = function() {
      var l, z;

      z = 1;
      l = this.collection.last();
      if (l !== void 0) {
        z = l.get("z-index") + 1;
      }
      return this.collection.create({
        "z-index": z
      });
    };

    DeskView.prototype.addOne = function(note) {
      var noteView;

      noteView = new app.NoteView({
        collection: this.collection,
        model: note,
        id: "note-" + note.cid
      });
      $('#desk').append(noteView.el);
      noteView.$el.draggable({
        stack: ".notes",
        delay: 100,
        start: function(event, ui) {
          return noteView.startDrag();
        },
        stop: function(event, ui) {
          return noteView.stopDrag(ui.position);
        }
      });
      noteView.$el.resizable({
        start: function(event, ui) {
          return noteView.startResize();
        },
        stop: function(event, ui) {
          return noteView.stopResize(ui.size);
        }
      });
      return noteView.showMarked();
    };

    DeskView.prototype.toggleEdit = function() {
      if (this.collection.isLocked) {
        this.collection.isLocked = false;
        return $("#toggleEdit span").removeClass("ui-icon-locked").addClass("ui-icon-unlocked");
      } else {
        this.collection.isLocked = true;
        return $("#toggleEdit span").removeClass("ui-icon-unlocked").addClass("ui-icon-locked");
      }
    };

    return DeskView;

  })(Backbone.View);
  delay = function(ms, func) {
    return setTimeout(func, ms);
  };
});
