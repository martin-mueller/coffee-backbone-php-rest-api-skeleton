note = new Note {"text": "Nur ein Test"}
# console.log note.toJSON()
# first call, position change is bound to save in notes.coffee
# server request method should be POST, server returns id
note.save {text: "bla bla"}, {wait: true}, success: () -> console.log note.toJSON()

notes = new Notes  



