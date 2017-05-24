// Include to activate tinymce and apply settings for WYSIWYG text boxes.
tinymce.init({
selector: ".wysiwyg",
plugins: [
	"advlist autolink lists link image charmap print preview anchor",
	"searchreplace visualblocks code fullscreen",
	"insertdatetime media table contextmenu paste"
],
toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"});