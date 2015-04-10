(function(){
	function createSelectFromArray(values, selectedValue){
		var sel = kkjs.node.create({
			tag: "select"
		});
		if (Array.isArray(values)){
			values.forEach(function(value){
				kkjs.node.create({
					tag: "option",
					parentNode: sel,
					value: value,
					text: value,
					selected: value === selectedValue
				});
			});
		}
		else {
			Object.keys(values).forEach(function(key){
				kkjs.node.create({
					tag: "option",
					parentNode: sel,
					value: values[key],
					text: key,
					selected: values[key] === selectedValue
				});
			});
		}
		return sel;
	}
	
	var separators = {
		",": ",",
		";": ";",
		"tab": "\t"
	};
	
	kkjs.css.$(".csvFile").forEach(function(fileInput){
		if (!fileInput.files){
			return;
		}
		
		var separator = createSelectFromArray(separators, kkjs.dataset.get(fileInput, "defaultSeparator", ","));
		
		var assignment = kkjs.node.create({
			tag: "table",
			previousSibling: fileInput
		});
		
		kkjs.node.create({
			tag: "label",
			childNodes: [
				"separator: ",
				kkjs.node.set(
					separator,
					{
						name: fileInput.name + "[separator]",
						events: {
							change: updateHeaderAssignment
						}
					}
				)
			],
			previousSibling: fileInput
		});
		
		var fileContent = "";
		kkjs.event.add(fileInput, "change", function(){
			fileContent = "";
			var file = fileInput.files[0];
			if (file){
				var r = new FileReader();
				r.onload = function(){
					fileContent = r.result;
					updateHeaderAssignment();
				};
				r.readAsText(file);
			}
			else {
				updateHeaderAssignment();
			}
		});
		
		function updateHeaderAssignment(){
			kkjs.node.clear(assignment);
			if (fileContent){
				var data = kkjs.parser.csv(fileContent, {separator: separator.value});
				if (data[0]){
					var options = kkjs.node.create({tag: "fragment"});
					data[0].forEach(function(header){
						if (header){
							kkjs.node.create({
								tag: "option",
								parentNode: options,
								text: header,
								value: header
							});
						}
					});
					JSON.parse(kkjs.dataset.get(fileInput, "header", "[]")).forEach(function(header){
						var currentOptions = options.cloneNode(true);
						Array.prototype.forEach.call(currentOptions.childNodes, function(option){
							option.selected = option.value.toLowerCase().indexOf(header.toLowerCase()) !== -1;
						});
						kkjs.node.create({
							tag: "tr",
							className: "header",
							childNodes: [
								{
									tag: "th",
									childNodes: [header + ": "]
								},
								{
									tag: "td",
									childNodes: [
										{
											tag: "select",
											name: fileInput.name + "[columnNames][" + header + "]",
											childNodes: [currentOptions]
										}
									]
								}
							],
							parentNode: assignment
						});
					});
				}
			}
		}
	});
}());