/**
 * CuredHosting theme scripts — deliberately tiny.
 *
 * Every feature on this site works without JavaScript:
 *  - the FAQ uses native <details>/<summary>,
 *  - form validation starts with native HTML + server-side checks,
 *  - the navigation block provides its own mobile behavior.
 *
 * What this file adds is progressive enhancement only:
 *  1. Focus the error summary when a form comes back with errors.
 *  2. Optional single-open behavior for accordion groups.
 *  3. Keep footer year stamps current.
 */
( function () {
	'use strict';

	var doc = document;

	/* 1. Focus the error summary after a failed submission (PRG redirect). */
	function focusErrorSummary() {
		if ( window.location.search.indexOf( 'ch_status=error' ) === -1 ) {
			return;
		}
		var box = doc.querySelector( '.ch-form-errors' );
		if ( box ) {
			box.setAttribute( 'tabindex', '-1' );
			box.focus();
		}
	}

	/* 2. Single-open accordions for containers marked [data-ch-accordion]. */
	function enhanceAccordions() {
		var wrappers = doc.querySelectorAll( '[data-ch-accordion]' );

		Array.prototype.forEach.call( wrappers, function ( wrapper ) {
			wrapper.addEventListener(
				'toggle',
				function ( event ) {
					var target = event.target;
					if ( ! target || ! target.open ) {
						return;
					}
					var openOnes = wrapper.querySelectorAll( 'details[open]' );
					Array.prototype.forEach.call( openOnes, function ( other ) {
						if ( other !== target ) {
							other.open = false;
						}
					} );
				},
				true
			);
		} );
	}

	/* 3. Footer year stamps. */
	function stampYears() {
		var year = String( new Date().getFullYear() );
		var stamps = doc.querySelectorAll( '[data-ch-year]' );
		Array.prototype.forEach.call( stamps, function ( el ) {
			el.textContent = year;
		} );
	}

	if ( doc.readyState === 'loading' ) {
		doc.addEventListener( 'DOMContentLoaded', function () {
			focusErrorSummary();
			enhanceAccordions();
			stampYears();
		} );
	} else {
		focusErrorSummary();
		enhanceAccordions();
		stampYears();
	}
} )();
