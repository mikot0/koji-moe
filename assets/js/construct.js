var koji = koji || {},
	$ = jQuery;
function kojiAjaxErrors(jqXHR, exception) {
	if (jqXHR.status === 0) {
		alert('Not connect.n Verify Network.');
	} else if (jqXHR.status == 404) {
		alert('Requested page not found. [404]');
	} else if (jqXHR.status == 500) {
		alert('Internal Server Error [500].');
	} else if (exception === 'parsererror') {
		alert('Requested JSON parse failed.');
	} else if (exception === 'timeout') {
		alert('Time out error.');
	} else if (exception === 'abort') {
		alert('Ajax request aborted.');
	} else {
		alert('Uncaught Error.n' + jqXHR.responseText);
	}
}

function kojiToggleAttribute($element, attribute, trueVal, falseVal) {

	if (typeof trueVal === 'undefined') { trueVal = true; }
	if (typeof falseVal === 'undefined') { falseVal = false; }

	if ($element.attr(attribute) !== trueVal) {
		$element.attr(attribute, trueVal);
	} else {
		$element.attr(attribute, falseVal);
	}
}

koji.intervalScroll = {

	init: function () {

		didScroll = false;

		$(window).on('scroll load', function () {
			didScroll = true;
		});

		setInterval(function () {
			if (didScroll) {
				didScroll = false;

				$(window).triggerHandler('did-interval-scroll');

			}

		}, 250);

	},

}

koji.toggles = {

	init: function () {

		koji.toggles.toggle();

	},

	toggle: function () {

		$('*[data-toggle-target]').on('click toggle', function (e) {

			var $toggle = $(this);

			var targetString = $toggle.data('toggle-target');

			if (targetString == 'next') {
				var $target = $toggle.next();
			} else {
				var $target = $(targetString);
			}

			$target.trigger('will-be-toggled');

			var classToToggle = $toggle.data('class-to-toggle') ? $toggle.data('class-to-toggle') : 'active';

			$target.toggleClass(classToToggle);

			if ($toggle.data('toggle-type') == 'slidetoggle') {
				var duration = $toggle.data('toggle-duration') ? $toggle.data('toggle-duration') : '400';
				$target.slideToggle(duration);
			}

			kojiToggleAttribute($target, 'aria-expanded');

			$('*[data-toggle-target="' + targetString + '"]').each(function () {
				$(this).toggleClass('active');

				kojiToggleAttribute($(this), 'aria-pressed');
			});

			if ($toggle.is('.active') && $toggle.data('set-focus')) {
				var $focusElement = $($toggle.data('set-focus'));

				if ($focusElement.length) {
					$focusElement.focus();
				}
			}

			if ($toggle.data('lock-scroll')) {
				koji.scrollLock.setTo(true);
			} else if ($toggle.data('unlock-scroll')) {
				koji.scrollLock.setTo(false);
			} else if ($toggle.data('toggle-scroll-lock')) {
				koji.scrollLock.setTo();
			}

			$target.trigger('toggled');

			return false;

		});
	},

}

koji.searchModal = {

	init: function () {

		if ($('.search-overlay').length) {

			koji.searchModal.conditionalScrollLockOnToggle();

			koji.searchModal.outsideUntoggle();

			koji.searchModal.closeOnEscape();

		}

	},

	conditionalScrollLockOnToggle: function () {

		$('.search-overlay').on('toggled', function () {

			var winWidth = $(window).width();

			if (winWidth >= 1000) {
				koji.scrollLock.setTo();
			}

		});

	},

	outsideUntoggle: function () {

		$(document).on('click', function (e) {

			var $target = $(e.target),
				modal = '.search-overlay',
				modalActive = modal + '.active';

			if ($(modalActive).length && $target.not($(modal)) && !$target.parents($(modal))) {
				$('.search-untoggle').trigger('click');
			}

		});

	},

	closeOnEscape: function () {

		$(document).keyup(function (e) {
			if (e.keyCode == 27 && $('.search-overlay').hasClass('active')) {
				$('.search-untoggle').trigger('click');
			}
		});

	},

}

koji.elementInView = {

	init: function () {

		$targets = $('.do-spot');
		koji.elementInView.run($targets);

		$(window).on('ajax-content-loaded', function () {
			$targets = $('.do-spot');
			koji.elementInView.run($targets);
		});

	},

	run: function ($targets) {

		if ($targets.length) {

			$targets.each(function () {
				$(this).addClass('will-be-spotted');
			});

			koji.elementInView.handleFocus($targets);
		}

	},

	handleFocus: function ($targets) {

		winHeight = $(window).height();

		$(window).on('load resize orientationchange', function () {
			winHeight = $(window).height();
		});

		$(window).on('resize orientationchange did-interval-scroll', function () {

			var winTop = $(window).scrollTop();
			winBottom = winTop + winHeight;

			$targets.each(function () {

				var $this = $(this);

				if (koji.elementInView.isVisible($this, checkAbove = true)) {
					$this.addClass('spotted').triggerHandler('spotted');
				}

			});

		});

	},

	isVisible: function ($elem, checkAbove) {

		if (typeof checkAbove === 'undefined') {
			checkAbove = false;
		}

		var winHeight = $(window).height();

		var docViewTop = $(window).scrollTop(),
			docViewBottom = docViewTop + winHeight,
			docViewLimit = docViewBottom - 50;

		var elemTop = $elem.offset().top,
			elemBottom = $elem.offset().top + $elem.outerHeight();

		if (checkAbove && (elemBottom <= docViewBottom)) {
			return true;
		}

		return (docViewLimit >= elemTop);

	}

}

koji.mobileMenu = {

	init: function () {

		koji.mobileMenu.onToggle();

		koji.mobileMenu.resizeChecks();

		koji.mobileMenu.focusLoop();

	},

	onToggle: function () {

		$('.mobile-menu-wrapper').on('will-be-toggled', function () {
			window.scrollTo(0, 0);
		});

	},

	resizeChecks: function () {

		$(window).on('load resize orientationchange', function () {

			var $siteHeader = $('#site-header'),
				headerHeight = $siteHeader.outerHeight(),
				$mobileMenuWrapper = $('.mobile-menu-wrapper');

			$mobileMenuWrapper.css({ 'padding-top': headerHeight + 'px' });

			if ($(window).width() >= 1000 && $('.nav-toggle').hasClass('active')) {
				$('.nav-toggle').trigger('click');
			}
		});

	},

	focusLoop: function () {
		$('*').on('focus', function () {
			if ($('.mobile-menu-wrapper').hasClass('active')) {
				if ($(this).parents('#site-content').length) {
					$('.nav-toggle').focus();
				}
			}
		});
	}

}

koji.intrinsicRatioEmbeds = {

	init: function () {

		var vidSelector = 'iframe, object, video';
		var resizeVideo = function (sSel) {
			$(sSel).each(function () {
				var $video = $(this),
					$container = $video.parent(),
					iTargetWidth = $container.width();

				if (!$video.attr('data-origwidth')) {
					$video.attr('data-origwidth', $video.attr('width'));
					$video.attr('data-origheight', $video.attr('height'));
				}

				var ratio = iTargetWidth / $video.attr('data-origwidth');

				$video.css('width', iTargetWidth + 'px');
				$video.css('height', ($video.attr('data-origheight') * ratio) + 'px');
			});
		};

		resizeVideo(vidSelector);

		$(window).resize(function () {
			resizeVideo(vidSelector);
		});

	},

}

koji.masonry = {

	init: function () {

		$wrapper = $('.posts');

		if ($wrapper.length) {

			$grid = $wrapper.imagesLoaded(function () {

				$grid = $wrapper.masonry({
					columnWidth: '.grid-sizer',
					itemSelector: '.preview',
					percentPosition: true,
					stagger: 0,
					transitionDuration: 0,
				});

			});

			$grid.on('layoutComplete', function () {
				$('.posts').css('opacity', 1);
				$(window).triggerHandler('scroll');
			});

		}

	}

}

koji.smoothScroll = {

	init: function () {

		$('a[href*="#"]')
			.not('[href="#"]')
			.not('[href="#0"]')
			.not('.skip-link')
			.click(function (event) {
				if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
					var target = $(this.hash);
					target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
					if (target.length) {
						event.preventDefault();
						$('html, body').animate({
							scrollTop: target.offset().top
						}, 1000);
					}
				}
			});

	},

}

koji.scrollLock = {

	init: function () {

		window.scrollLocked = false,
			window.prevScroll = {
				scrollLeft: $(window).scrollLeft(),
				scrollTop: $(window).scrollTop()
			},
			window.prevLockStyles = {},
			window.lockStyles = {
				'overflow-y': 'scroll',
				'position': 'fixed',
				'width': '100%'
			};

		koji.scrollLock.saveStyles();

	},

	saveStyles: function () {

		var styleAttr = $('html').attr('style'),
			styleStrs = [],
			styleHash = {};

		if (!styleAttr) {
			return;
		}

		styleStrs = styleAttr.split(/;\s/);

		$.each(styleStrs, function serializeStyleProp(styleString) {
			if (!styleString) {
				return;
			}

			var keyValue = styleString.split(/\s:\s/);

			if (keyValue.length < 2) {
				return;
			}

			styleHash[keyValue[0]] = keyValue[1];
		});

		$.extend(prevLockStyles, styleHash);
	},

	lock: function () {

		var appliedLock = {};

		if (scrollLocked) {
			return;
		}

		prevScroll = {
			scrollLeft: $(window).scrollLeft(),
			scrollTop: $(window).scrollTop()
		};

		koji.scrollLock.saveStyles();

		$.extend(appliedLock, lockStyles, {
			'left': - prevScroll.scrollLeft + 'px',
			'top': - prevScroll.scrollTop + 'px'
		});

		$('html').css(appliedLock).addClass('html-locked');
		$(window).scrollLeft(0).scrollTop(0);
		$('body').addClass('scroll-locked');

		scrollLocked = true;
	},

	unlock: function () {

		if (!scrollLocked) {
			return;
		}

		$('html').attr('style', $('<x>').css(prevLockStyles).attr('style') || '').removeClass('html-locked');
		$(window).scrollLeft(prevScroll.scrollLeft).scrollTop(prevScroll.scrollTop);
		$('body').removeClass('scroll-locked');

		scrollLocked = false;
	},

	setTo: function (on) {

		if (arguments.length) {
			if (on) {
				koji.scrollLock.lock();
			} else {
				koji.scrollLock.unlock();
			}
		} else {
			if (scrollLocked) {
				koji.scrollLock.unlock();
			} else {
				koji.scrollLock.lock();
			}
		}

	},

}

koji.loadMore = {

	init: function () {

		var $pagination = $('#pagination');

		if ($pagination.length) {

			window.loading = false;
			window.lastPage = false;

			koji.loadMore.prepare($pagination);

		}

	},

	prepare: function ($pagination) {

		var query_args = JSON.parse($pagination.attr('data-query-args'));

		if (query_args.paged == query_args.max_num_pages) {
			$pagination.addClass('last-page');
		} else {
			$pagination.removeClass('last-page');
		}

		var loadMoreType = 'button';
		if ($('body').hasClass('pagination-type-scroll')) {
			loadMoreType = 'scroll';
		} else if ($('body').hasClass('pagination-type-links')) {
			return;
		}

		if (loadMoreType == 'scroll') {
			koji.loadMore.detectScroll($pagination, query_args);
		} else if (loadMoreType == 'button') {
			koji.loadMore.detectButtonClick($pagination, query_args);
		}

	},

	detectScroll: function ($pagination, query_args) {

		$(window).on('did-interval-scroll', function () {

			if (lastPage || loading) {
				return;
			}

			var paginationOffset = $pagination.offset().top,
				winOffset = $(window).scrollTop() + $(window).outerHeight();

			if ((winOffset > paginationOffset)) {
				koji.loadMore.loadPosts($pagination, query_args);
			}

		});

	},

	detectButtonClick: function ($pagination, query_args) {

		$('#load-more').on('click', function () {

			if (loading) {
				return;
			}

			koji.loadMore.loadPosts($pagination, query_args);
			return false;
		});

	},

	loadPosts: function ($pagination, query_args) {

		loading = true;
		$pagination.addClass('loading').removeClass('last-page');

		query_args.paged++;

		var json_query_args = JSON.stringify(query_args);

		$.ajax({
			url: koji_ajax_load_more.ajaxurl,
			type: 'post',
			data: {
				action: 'koji_ajax_load_more',
				json_data: json_query_args
			},
			success: function (result) {

				var $result = $(result),
					$articleWrapper = $($pagination.data('load-more-target'));

				if (!$result.length) {
					loading = false;
					$articleWrapper.addClass('no-results');
					$pagination.addClass('last-page').removeClass('loading');
				}

				if ($result.length) {

					$articleWrapper.removeClass('no-results');

					$result.each(function () {
						$(this).addClass('post-from-page-' + query_args.paged);
					});

					$result.imagesLoaded(function () {

						$articleWrapper.append($result).masonry('appended', $result);

						$(window).triggerHandler('ajax-content-loaded');
						$(window).triggerHandler('did-interval-scroll');

						koji.loadMore.updateHistory(query_args.paged);

						loading = false;
						$pagination.removeClass('loading');

						if (query_args.paged == query_args.max_num_pages) {
							$pagination.addClass('last-page');
							lastPage = true;
						} else {
							$pagination.removeClass('last-page');
							lastPage = false;
						}

						$('.post-from-page-' + query_args.paged + ':first .preview-title a').focus();

					});

				}

			},

			error: function (jqXHR, exception) {
				kojiAjaxErrors(jqXHR, exception);
			}
		});

	},

	updateHistory: function (paged) {

		var newUrl,
			currentUrl = document.location.href;

		if (currentUrl.substr(currentUrl.length - 1) !== '/') {
			currentUrl += '/';
		}

		var hasPaginationRegexp = new RegExp('^(.*/page)/[0-9]*/(.*$)');

		if (hasPaginationRegexp.test(currentUrl)) {
			newUrl = currentUrl.replace(hasPaginationRegexp, '$1/' + paged + '/$2');
		} else {
			var beforeSearchReplaceRegexp = new RegExp('^([^?]*)(\\??.*$)');
			newUrl = currentUrl.replace(beforeSearchReplaceRegexp, '$1page/' + paged + '/$2');
		}

		history.pushState({}, '', newUrl);

	}

}

$(document).ready(function () {

	koji.intervalScroll.init();

	koji.toggles.init();

	koji.searchModal.init();

	koji.elementInView.init();

	koji.mobileMenu.init();

	koji.intrinsicRatioEmbeds.init();

	koji.masonry.init();

	koji.smoothScroll.init();

	koji.loadMore.init();

	koji.scrollLock.init();

});