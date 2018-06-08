(function($, undefined) {
	'use strict';
	$.fn.yahooWeather = function(p1) {
		const fahrenheitToCelsius = (f) => Math.ceil((f-32)/1.8);
		const add0 = (x) => (x.toString()[0] !== '0') ? ( (x<10) ? '0' + x : x ) : x;
		const addPlus = (x) => (x>0) ? '+' + x : x;
		const kharkivLocation = {
			coords : {
				latitude : 30.044420,
				longitude : 31.235712
			}
		}
		const config = {
			method : 'GET',
			mode : 'cors'
		}
		const def = {}
		const location = new Promise(function(res,rej){
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(res, rej);
			} else rej('Browser doesn\'t support Geolocation');
		});
		location
			.catch(()=> Promise.resolve(kharkivLocation))
			.then((location)=>{
				const query = `
					select * from weather.forecast where woeid in (
						SELECT woeid FROM geo.places 
						WHERE text="(${location.coords.latitude},${location.coords.longitude})"
					)
				`;  
				const uri = `https://query.yahooapis.com/v1/public/yql?q=${query}&format=json`;
				return fetch(uri, config)
			})
			.then(res => res.json())
			.then(res => {
				const item = res.query.results.channel.item;
				def.city = res.query.results.channel.location.city;
				def.country = res.query.results.channel.location.country;
				def.date = new Date(res.query.created);
				def.tHigh = fahrenheitToCelsius(item.forecast[0].high);
				def.tLow = fahrenheitToCelsius(item.forecast[0].low);
				def.condition = item.condition.code;
				def.temp = fahrenheitToCelsius(item.condition.temp);
				def.template = template;
				this.html(def.template());
			})
			.catch((err)=>{console.log(err)});
		return this;

		function template() {
			return `
				<div class="media">
                <div class="media-body text-xs-left">
                <h3 class="danger">${addPlus(this.temp)}&deg;C </h3>
                <span>${this.city}, ${this.country}</span>
            </div>
            <div class="media-right media-middle">
                <i class="wi wi-yahoo-${this.condition} danger font-large-2 float-xs-right"></i>
                </div>
                <progress class="progress progress-sm progress-danger mt-1 mb-0" value="100" max="100"></progress>
                </div>
			`;







		};
	}
})(jQuery)