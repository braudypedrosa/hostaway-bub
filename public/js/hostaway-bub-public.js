

// var elementHeights = $('.team-member-info').map(function() {
//     return $(this).height();
// }).get();

// // Math.max takes a variable number of arguments
// // `apply` is equivalent to passing each height as an argument
// var maxHeight = Math.max.apply(null, elementHeights);

// // Set each height to the max height
// $('.team-member-info').height(maxHeight);


(function( $ ) {
	'use strict';

	
	$(document).ready(function(){
	
	// change active on hash
	if(getSelectorFromHash()) {
		$('.listings-filter li.active').removeClass('active');
		$('.filter_'+getSelectorFromHash().substring(1)).addClass('active');
	} else {
		$('.filter_all').addClass('active');
	}

	function getSelectorFromHash() {
		var hash = window.location.hash.replace(/^#/g, '');

		var selector = hash ? '.' + hash : targetSelector;

		return selector;
	}

	function setHash(state) {
		var selector = state.activeFilter.selector;
		var newHash = '#' + selector.replace(/^\./g, '');

		if (selector === targetSelector && window.location.hash) {
			// Equivalent to filter "all", remove the hash

			history.pushState(null, document.title, window.location.pathname); // or history.replaceState()
		} else if (newHash !== window.location.hash && selector !== targetSelector) {
			// Change the hash

			history.pushState(null, document.title, window.location.pathname + newHash); // or history.replaceState()
		}
	}

	var dataContainer = jQuery('.listings-wrapper');

	if(dataContainer.length != 0) { // avoid running script on pages without team block

		var targetSelector = '.mix';
	

		var dataMixer = mixitup(dataContainer, {
			selectors: {
				target: targetSelector
			},
			load: {
				filter: getSelectorFromHash() // Ensure that the mixer's initial filter matches the URL on startup
			},
			callbacks: {
				onMixEnd: setHash // Call the setHash() method at the end of each operation
			}
		});

		window.onhashchange = function() {
			var selector = getSelectorFromHash();

			if (selector === dataMixer.getState().activeFilter.selector) return; // no change

			dataMixer.filter(selector);
		};

		$('.listings-filter li').each(function(){
			$(this).click(function(){
				$('.listings-filter li.active').removeClass('active');
				var selectedValue = $(this).attr('value');
				$(this).addClass('active');

				if(selectedValue == 'all') {
					dataMixer.filter(selectedValue);
				} else {
					dataMixer.filter('.'+selectedValue)
				}
			});
		});


		// console.log(dataMixer);
	}


});


})( jQuery );
