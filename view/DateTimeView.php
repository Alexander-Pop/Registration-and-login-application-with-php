<?php

class DateTimeView {


	public function show() {
		$date = new DateTime("now", new DateTimeZone('Europe/Stockholm'));
		$date = $date->format('l, \t\h\e jS \o\f F Y, \T\h\e\ \t\i\m\e \i\s H:i:s');

		return '<p>' . $date . '</p>';
	}

}
