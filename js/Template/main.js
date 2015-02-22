var functionSelection = function() {
	var isActive = false;
	var functionName = '';
	$('.list-group-item').click(function(e) {
		e.preventDefault();
		isActive 		= $(this).children('span').hasClass('glyphicon-remove-circle');
		functionName 	= $(this).data('functionname');
		setActiveFunction(functionName, isActive);
	});
}

var setActiveFunction = function(functionName, isActive) {
	if(isActive) {
		$('[data-functionname=' + functionName + ']').children('span').removeClass('glyphicon-remove-circle gray');
		$('[data-functionname=' + functionName + ']').children('span').addClass('glyphicon-ok-circle green');

		$('.' + functionName).removeClass('hidden');
		$('.' + functionName).animate({
			opacity: 1,
		}, 800);
	} else {
		$('[data-functionname=' + functionName + ']').children('span').removeClass('glyphicon-ok-circle green');
		$('[data-functionname=' + functionName + ']').children('span').addClass('glyphicon-remove-circle gray');
		$('.' + functionName).animate({
			opacity: 0,
		}, 800, function() {
			$('.' + functionName).addClass('hidden');
		});
	}
}

var templateSelection = function() {
	$('.selectedTemplate').click(function() {
		var template_id = $(this).attr('id');
		$.ajax({
			url: '/api/templates/loadTemplate',
			type: 'POST',
			data: { id: +template_id }
		}).done(function(template_structure) {
			$("html").animate({
				opacity: 0
			}, 800, function() {
				$("html").removeAttr('style');
				$("html").fadeOut('fast', function() {
					$("head").html($.htmlDoc(template_structure).find('head').html());
					$("body").html($.htmlDoc(template_structure).find('body').html());
					$("html").fadeIn('slow');
				});
			});
		});
	});
}

var showHideOptionsPanel = function() {
	$('.showHideOptions').click(function(e) {
		e.preventDefault();
		var arrow = $(this).children('span');
		if(arrow.hasClass('glyphicon-chevron-left')) {
			$('.options-panel').animate({
				marginLeft: -200
			}, 500, function() {
				arrow.removeClass('glyphicon-chevron-left');
				arrow.addClass('glyphicon-chevron-right');
			});
		} else {
			$('.options-panel').animate({
				marginLeft: 0
			}, 500, function() {
				arrow.removeClass('glyphicon-chevron-right');
				arrow.addClass('glyphicon-chevron-left');
			});
		}
	});
}

var saveTemplate = function() {
	$('.save-template').children('button').click(function() {
		var templateId = $('input[name=templateId]').val();
		var selectedFunctions = '';
		$.each($('.functions-panel').children('div.list-group').children('a'), function(index, value) {
			var selectedSpan = $(value).children('span');
			if(selectedSpan.hasClass('green')) {
				selectedFunction = selectedSpan.parent('a').data('functionname');
				selectedFunctions += selectedFunction + ',';
			}
		});
		if(templateId) {
			$('input[name="templateId"').val(templateId);
			$('input[name="selectedFunctions').val(selectedFunctions);
			$('#save-template-form').submit();
		} else {
			alert('Du måste välja ett tema innan vi kan gå vidare.');
		}
	});
}

$( document ).ready(function() {
	functionSelection();
	$('.dropdown-toggle').dropdown();
	templateSelection();
	showHideOptionsPanel();
	saveTemplate();
});