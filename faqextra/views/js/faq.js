$(document).ready(function() {
	$('#faqs h3').each(function() {
		var tis = $(this), state = false, answer = tis.next('div').slideUp();
		if (location.hash != '') {
			answerToShow = location.hash.replace("qa", "answer");
			if ('#'+answer.attr('id') == answerToShow) {state = true; answer.slideToggle(state); tis.toggleClass('active',state);}
		}
		tis.click(function() {
			state = !state;
			answer.slideToggle(state);
			tis.toggleClass('active',state);
		});
		tis.mouseover(function() {$(this).addClass("over");});
		tis.mouseout(function() {$(this).removeClass("over");});
	});
});