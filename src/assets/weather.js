/*-------------------------------------------------------------------------
| City field search/autocomplete module
|--------------------------------------------------------------------------
|BLAHBLAH...
|
-------------------------------------------------------------------------*/
( function(jq) {
	jq(function() {
		
		const currentWeatherContainer = jq( '#dashboard_wpweather .container #current-weather' );
		const forecastContainer = jq( '#dashboard_wpweather .container #forecast' );
		const weatherKey = '948c1c590d8b49f8f2ef7b34b91ff6aa';


		if ( wpweather_ajax.city !== '' && wpweather_ajax.country !== '' )	{
			const request_url = `https://api.openweathermap.org/data/2.5/weather?q=${wpweather_ajax.city},${wpweather_ajax.country}&appid=${weatherKey}&units=metric`;
			// const request_url = `${wpweather_ajax.plugin_dir_url}src/data/weather.json`;

			renderCurrentWeather( request_url );

		} else {
			/**
			 * Get IP based location 
			 * @dev - IP would need validation in a good scenario - but can get away with it Heer..
			 * 
			 */
			// const ipv4_address = wpweather_ajax.client_ip;
			const ipv4_address = '96.47.238.100';

			jq.ajax({
				method: 'GET',
				url: `https://ipapi.co/${ipv4_address}/json/`
				// url: `http://ip-api.com/json/${ipv4_address}`

			}).done( ( location ) => {
				const request_url = `https://api.openweathermap.org/data/2.5/weather?lat=${location.latitude}&lon=${location.longitude}&appid=${weatherKey}&units=metric`;
				// const request_url = `${wpweather_ajax.plugin_dir_url}src/data/weather.json`;

				renderCurrentWeather( request_url );
				// renderForecast(request_url);

			}).fail( ( xhr, status, errorThrown ) => {
				console.log(xhr);
				console.log(status);
				console.log(errorThrown);

			});
		}

		/**
		 * [renderForecast description]
		 * @param  {string} request_url [description]
		 * @return {void}             [description]
		 */
		function renderForecast(request_url)
		{

			jq.ajax({
				method: 'GET',
				url: request_url

			}).done( ( data ) => {
				console.log(data);
				
				let forecastHTML = '';

				forecastHTML = `
					<h3> 5 days forecats (${ ( new Date( data.dt * 1000 ) ).toDateString()}) </span> </h3>
					<div class="forecast">
						<img class="icon" src="https://openweathermap.org/img/w/${data}.png" alt="${data} Weather" />
						<p class="temperature">${data}&deg; C</p>
						<p class="description">${data}</p>
						<table>
							<tr>
								<td> Humidity </td>
							</tr>
							<tr>
								<td> ${data} % </td>
							</tr>
						</table>
					</div>
				`;

				// forecastContainer.append( forecastHTML );

			}).fail( ( xhr, status, errorThrown ) => {
				console.log(xhr);
				console.log(status);
				console.log(errorThrown);

			});

		}

		/**
		 * [renderCurrentWeather description]
		 * @param  {string} request_url [description]
		 * @return {void}             
		 */
		function renderCurrentWeather(request_url)
		{

			jq.ajax({
				method: 'GET',
				url: request_url

			}).done( ( data ) => {

				const currentWeather = data.weather[0];
				const city = `${data.name}, ${data.sys.country}`;
				let currentWeatherHTML = '';

				currentWeatherHTML = `
					<h3> Current weather in ${city} <span class="date-now"> (${ ( new Date( data.dt * 1000 ) ).toDateString() }) </span> </h3>
					<div class="weather">
						<img class="icon" src="https://openweathermap.org/img/w/${currentWeather.icon}.png" alt="${city} Weather" />
						<p class="description">${currentWeather.description}</p>
						<p class="temperature">${data.main.temp}&deg; C</p>
						<table>
							<tr>
								<td> ${wpweather_ajax.humidity} </td>
								<td> ${wpweather_ajax.wind} </td>
								<td> ${wpweather_ajax.sunrise} </td>
								<td> ${wpweather_ajax.sunset} </td>
								<td> ${wpweather_ajax.min_temp} </td>
								<td> ${wpweather_ajax.max_temp} </td>
							</tr>
							<tr>
								<td> ${data.main.humidity} % </td>
								<td> ${data.wind.speed} m/s</td>
								<td> ${formatTime( data.sys.sunrise )} </td>
								<td> ${formatTime( data.sys.sunset )} </td>
								<td> ${data.main.temp_min}&deg; C </td>
								<td> ${data.main.temp_max}&deg; C </td>
							</tr>
						</table>
					</div>
				`;

				currentWeatherContainer.append( currentWeatherHTML );

			}).fail( ( xhr, status, errorThrown ) => {
				currentWeatherContainer.append( `Sorry, couldn't get anything 4 ya pal` );
				console.log(xhr);
				console.log(status);
				console.log(errorThrown);

			});

		}

		/**
		 * Extracts time(hours:minutes) from unix timestamp.
		 * @param  {number} timestamp A Unix timestamp.
		 * @return {string}           String representation of given timestamp.
		 */
		function formatTime(timestamp)
		{

			if( typeof timestamp === 'undefined' || typeof timestamp !== 'number' ) {
				throw new Error( `Expect argument to be a valid timestamp. ${typeof timestamp}:${timestamp} given` );
			}

			let date = new Date(timestamp*1000);
			
			return `${ date.getHours() }:${ date.getMinutes() }`;
		}


	});

})(jQuery);