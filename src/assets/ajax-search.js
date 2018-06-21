/*-------------------------------------------------------------------------
| City field search/autocomplete module
|--------------------------------------------------------------------------
|BLAHBLAH...
|
-------------------------------------------------------------------------*/
( function(jq) { 
	jq(function() {

		/* Renders country drpdown */
		jq('#country').countrySelect();

		const citySearchResult = document.querySelector( '#cities' );
		const inputCity = document.querySelector( '#wpweather_city' );

		inputCity.addEventListener('input', function () {
			const query = inputCity.value.trim();

			if ( query.length >= 4 ) {
				const selectedCountry = document.querySelector( '#country_code' ).value;

				jq.ajax({
					method: 'GET',
					url: `${wpweather_ajax.plugin_dir_url}src/data/${selectedCountry}-cities.json`

				}).done( ( cities ) => {
					const queryPattern = new RegExp( `^${query}`, 'i' );
					let matchedCities = [];

					/*@dev: query response for matched cities*/
					cities.map( ( city ) => { 
						if ( city.name.search( queryPattern ) !== -1 ) {
							matchedCities.push(city.name);
						}

					});

					/*@dev: render cities only if query returns matched cities*/
					if ( matchedCities.length > 0 ) {
						rernderSearchResult(matchedCities);
						citySelect();
						
					}

				}).fail( ( xhr, status, errorThrown ) => {
					console.log(xhr);
					console.log(status);
					console.log(errorThrown);

				});

			}

		});


		/**
		 * Renders query result.
		 * @param  {array} cities -> array of cities name.
		 * @return {void}
		 */
		function rernderSearchResult(cities)
		{
			let searchResultHTML = '<ul id="city-list" >';
			searchResultHTML += cities.map( (city) => `<li class="city"> ${city} </li>` ).join('');
			searchResultHTML += '</ul>';

			citySearchResult.innerHTML = searchResultHTML;
		}
		
		/* function rernderSearchResult(cities)
		{
			let searchResultHTML = '<select id="city-list" >';
			searchResultHTML += cities.map( (city) => `
				<option class="city" value="${city}" > ${city} </option>
			`).join('');

			searchResultHTML += '</select>';

			citySearchResult.innerHTML = searchResultHTML;
		}*/

		/**
		 * Update city field with selected city.
		 * @return {void}
		 */
		function citySelect()
		{
			jq('.city').click( function () {
				inputCity.value = jq( this ).text();
				citySearchResult.innerHTML = '';
			});
		}


	});

})(jQuery);