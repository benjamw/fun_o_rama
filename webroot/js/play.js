

;
// Avoid `console` errors in browsers that lack a console
(function(window) {

	"use strict";

	window.console = window.console || { };

	var method,
		noop = function( ) { },
		methods = [
			'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
			'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
			'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
			'timeStamp', 'trace', 'warn'
		],
		length = methods.length,
		console = window.console;

	while (length) {
		method = methods[length];

		// Only stub undefined methods.
		if ( ! console[method]) {
			console[method] = noop;
		}

		length -= 1;
	}

}(window));

// the music player proper
(function(document) {

	"use strict";


	var time_disp = document.getElementById('time'),
		title_disp = document.getElementById('title'),
		audio = document.getElementById('song'),
		toggle_btn = document.getElementById('toggle'),
		fading = false,
		played = false,
		pulled = false,
		fade_timeout = false,


		mark_played = function( ) {
			played = true;

			jQuery.ajax({
				type: 'GET',
				url: ROOT_URL +'songs/played/'+ SONG.id
			});
		},


		do_play = function( ) {
			audio.play( );
			title_disp.innerHTML = SONG.title;

			if ( ! audio.paused) {
				toggle_btn.innerHTML = 'Stop';
			}
		},


		do_timeupdate = function( ) {
			var duration = parseInt(audio.duration, 10),
				currentTime = parseInt(audio.currentTime, 10),
				remaining = duration - currentTime,
				s, m;

			s = remaining % 60;
			m = Math.floor(remaining / 60) % 60;

			s = ((s < 10) ? "0"+ s : s);
			m = ((m < 10) ? "0"+ m : m);

			time_disp.innerHTML = m +":"+ s;

			if ( ! audio.paused && ! pulled && (audio.currentTime > (audio.duration - 30))) {
console.log('pre-loading next song');
				// pre-load next song
				get_next_song( );
			}

			if ( ! audio.paused && ! fading && (audio.currentTime > (audio.duration - 2))) {
console.log('start fading');
console.log(ROOT_URL.slice(0, -1) + SONG.__file);
				// fade out current, then play next song
				fade_out( );
			}

			if ( ! played && (audio.currentTime > (audio.duration / 2))) {
console.log('marking song as played');
				// mark song as played
				mark_played( );
			}

			if (audio.ended) {
console.log('song ended');
				play_next( );
			}
		},


		init = function( ) {
			audio.volume = 1;
			audio.load( );

			audio.addEventListener('timeupdate', do_timeupdate);

console.log(audio.src);
			audio.addEventListener('canplay', do_play);

			if (audio.HAVE_ENOUGH_DATA === audio.readyState) {
				do_play( );
			}
		},


		play_next = function( ) {
console.log('---play_next---');
console.log(arguments);
			audio.removeEventListener('timeupdate', do_timeupdate);
			audio.removeEventListener('canplay', do_play);

			audio.pause( );

			if (fade_timeout) {
				clearTimeout(fade_timeout);
			}

			fading = false;
			played = false;
			pulled = false;
			fade_timeout = false;
console.log(ROOT_URL.slice(0, -1) + SONG.__file);

			audio.src = ROOT_URL.slice(0, -1) + SONG.__file;

			init( );
		},


		fade_out = function( ) {
console.log('---fade_out---');
			var ramp = function( ) {
					audio.volume = Math.max(0, audio.volume - 0.025); // 1 / 40
					if (0 < audio.volume) {
console.log('fading = '+ audio.volume);
						fade_timeout = setTimeout(ramp, 50);
					}
					else {
console.log('starting next');
						play_next( );
					}
				};

			fading = true;

			ramp( );
		},


		get_next_song = function(play) {
console.log('---get_next_song---');
console.log(arguments);
			play = !! play;

			pulled = true;

			jQuery.ajax({
				type: 'GET',
				dataType: 'json',
				url: ROOT_URL +'songs/play/'+ T,
				success: function(msg) {
console.log(msg);
					if ('REFRESH' === msg) {
						window.location.reload( );
					}

					SONG = msg.Song;

					if (play) {
						play_next( );
					}
				}
			});
		},


		skip = function( ) {
console.log('---skip---');
console.log(arguments);
			get_next_song(true);
		},


		toggle = function( ) {
console.log('---toggling---');
console.log(arguments);
			if (audio.paused) {
				audio.play( );
				toggle_btn.innerHTML = 'Stop';
			}
			else {
				audio.pause( );
				toggle_btn.innerHTML = 'Play';
			}
		};


	document.getElementById('skip').addEventListener('click', skip);
	toggle_btn.addEventListener('click', toggle);


	// start the music...
	if ( ! SONG) {
		skip( );
	}
	else {
		init( );
	}

}(document));

