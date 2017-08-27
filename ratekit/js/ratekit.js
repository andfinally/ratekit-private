/*!
 * RateKit
 * @version 1.0.0
 * https://ratekit.com
 */

$ = jQuery;

$(function() {

	var $input = $('.rating');
	if ($input.length) {
		$input.each(function(index, el) {
			if ($(el).attr('data-readonly') == 'true') {
				initReadOnlyRating(el);
				return;
			}
			fetchRating(el);
		});
	}

	function initReadOnlyRating(el) {
		var $input = $(el);
		var value = $input.val();
		$input
			.removeClass('rating-loading')
			.addClass('rating-loading')
			.rating({
				showCaption: false,
				showClear  : false,
				animate    : false,
				readonly   : true
			});
	}

	function fetchRating(el) {
		var $input = $(el),
			id = $input.attr('id');
		$.getJSON('ratekit/api/rating.php',
			{
				item: id
			}
		).done(function(data) {
			var rating = parseFloat(data.overall_rating);
			var count = parseInt(data.count);
			$input
				.attr({
					'value'     : rating,
					'data-count': count
				})
				.removeClass('rating-loading')
				.addClass('rating-loading')
				.rating({
					showCaption: false,
					showClear  : false,
					animate    : false
				});
			if ($input.data('show-label')) {
				showLabel($input, rating, count);
			}
			// Add event handler
			var eventHandler = makeEventHandler($input);
			$input.on('rating.change', eventHandler);
		}).fail(function(data) {
			console.log(data);
			throw new Error('Error fetching rating');
		});
	}

	function showLabel($input, rating, count) {
		var label = $('<div>').attr({
			'class': 'ratekit-label'
		});
		var labelHTML = makeLabelHTML(rating, count);
		label.append(labelHTML);
		var wrapper = $('<div>').attr({
			'class'    : 'ratekit-rating',
			'itemprop' : 'aggregateRating',
			'itemscope': '',
			'itemtype' : 'http://schema.org/AggregateRating'
		});
		$input
			.closest('.star-rating')
			.wrap(wrapper)
			.after(label);
	}

	function makeLabelHTML(rating, count) {
		var ratingValue = $('<span/>').attr({
			'class'   : 'ratekit-rating-value',
			'itemprop': 'ratingValue'
		}).text(rating);
		var ratingCount = $('<span/>').attr({
			'class'   : 'ratekit-rating-count',
			'itemprop': 'ratingCount'
		}).text(count);
		var labelHTML = [];
		labelHTML.push('Average rating ');
		labelHTML.push(ratingValue);
		labelHTML.push(', based on ');
		labelHTML.push(ratingCount);
		labelHTML.push(count > 1 ? ' reviews' : ' review');
		return labelHTML;
	}

	function makeEventHandler($input) {
		return function(event, value) {
			var id = $input.attr('id');
			$.getJSON('ratekit/api/rating.php',
				{
					item  : id,
					rating: value
				}
			).done(function(data) {
				if (data.status === 'error') {
					resetAndLockRating($input, data.rating);
				} else {
					acceptRating($input, data);
				}
			}).fail(function(data) {
				throw new Error(data);
			});
		}
	}

	function resetAndLockRating($input, rating) {
		$input.rating('clear');
		renderCaption($input, 'You rated this ' + rating, 'label-danger').delay(1500).fadeOut(500);
		setTimeout(function() {
			$input.rating('reset');
			$input.rating('refresh', {readonly: true, showCaption: false});
			renderCaption($input, 'Overall rating ' + parseFloat($input.val())).delay(2250).fadeOut(500);
		}, 2250);
	}

	function acceptRating($input, data) {
		var rating = parseFloat(data.rating);
		var overall_rating = parseFloat(data.overall_rating);
		var count = parseInt(data.count);
		renderCaption($input, 'Your rating ' + rating, 'label-success').delay(1500).fadeOut(500);
		setTimeout(function() {
			$input.rating('clear');
			$input.rating('update', overall_rating);
			$input.rating('refresh', {readonly: true, showCaption: false});
			$input.attr('value', overall_rating);
			renderCaption($input, 'Overall rating ' + overall_rating).delay(2250).fadeOut(500);
			if ($input.data('show-label') === true) {
				var wrapper = $input.closest('.ratekit-rating');
				wrapper
					.find('.ratekit-label')
					.empty()
					.append(makeLabelHTML(overall_rating, count));
			}
		}, 2000);
	}

	function renderCaption($input, message, cssClass) {
		if (cssClass) {
			cssClass = 'label ' + cssClass;
		} else {
			cssClass = 'label label-black';
		}
		var ratingContainer = $input.closest('.star-rating');
		ratingContainer.find('.caption').remove();
		var caption = $('<div/>', {'class': 'caption'});
		var span = $('<span/>', {
			'class': cssClass,
			text   : message
		});
		caption.append(span);
		ratingContainer.append(caption);
		return caption;
	}

})
