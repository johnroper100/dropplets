/*
Author Marks script by Matt Gemmell
Web: http://mattgemmell.com/
Twitter: http://twitter.com/mattgemmell

Use the HTML5 <mark>...</mark> tag (with CSS class "author-mark" applied) to indicate important, quotable or key points. This script allows toggling highlights for those sections, by adding or removing the CSS "marks-highlighted" class from each <mark> tag with the "author-mark" CSS class applied.

This script expects at least one marks-toggling link (<a href="...">...</a>) to be present in the document, using the CSS "toggle-marks-highlight" class. The toggling link(s) themselves will also have the "marks-highlighted" class added or removed when toggling occurs.

This script requires jQuery.
*/

$(document).ready(function() {
	// This runs automatically when the document has loaded.
    setupAuthorMarks();
});


function setupAuthorMarks() {
	// Locate toggling links.
	var toggleLinks = $(".toggle-marks-highlight");
	// Configure links to trigger toggling.
	toggleLinks.bind("click", toggleAuthorMarks);
	// Set initial highlighting state.
	window.author_marks_highlighted = false;
}


function toggleAuthorMarks() {
	// Locate marks and toggling links.
	var marks = $(".author-mark");
	var toggles = $(".toggle-marks-highlight");

	// Add or remove highlighting CSS class depending on current status.
	var highlightedClass = "marks-highlighted";
	var highlighted = window.author_marks_highlighted;
	if (!highlighted) {
		marks.addClass(highlightedClass);
		toggles.addClass(highlightedClass);
		toggles.html("<i class=\"icon-eye-close\">");
	} else {
		marks.removeClass(highlightedClass);
		toggles.removeClass(highlightedClass);
		toggles.html("<i class=\"icon-eye-open\">");
	}

	// Update current status.
	window.author_marks_highlighted = !highlighted;

	// Don't actually follow the toggling links.
	return false;
}
