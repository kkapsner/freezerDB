(function(){
	kkjs.css.$("input").forEach(function(input){
		if (input.value === "CURRENT_TIMESTAMP"){
			var interval = window.setInterval(
				function(){
					input.value = kkjs.date.format("%Y-%m-%d %H:%M:%S", new Date());
				},
				200
			);
			kkjs.event.add(input, "focus", function(){
				window.clearInterval(interval);
			});
		}
	});
}());