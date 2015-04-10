(function(){
	var columns = kkjs.css.$("form .dbItem .column");
	var rows = kkjs.css.$("form .dbItem .row");
	
	for (var i = 0; i < Math.min(columns.length, rows.length); i += 1){
		var name = "position" + Math.random();
		var columnSelect = kkjs.css.$("select[name*=column]", {node: columns[i]})[0];
		var rowSelect = kkjs.css.$("select[name*=row]", {node: rows[i]})[0];
		
		if (columnSelect && rowSelect){
			kkjs.css.set([columns, rows], "display", "none");
			var positionTable = kkjs.node.create({
				tag: "table"
			});
			var positionHeadRow = kkjs.node.create({
				tag: "tr",
				childNodes: [{tag: "th"}],
				parentNode: positionTable
			});
			Array.prototype.forEach.call(columnSelect.options, function(option){
				kkjs.node.create({
					tag: "th",
					parentNode: positionHeadRow,
					innerHTML: option.text
				});
			})
			Array.prototype.forEach.call(rowSelect.options, function(rowOption){
				var row = kkjs.node.create({
					tag: "tr",
					parentNode: positionTable,
					childNodes: [{
						tag: "th",
						parentNode: positionTable,
						innerHTML: rowOption.text
					}]
				});
				
				Array.prototype.forEach.call(columnSelect.options, function(columnOption){
					kkjs.node.create({
						tag: "td",
						parentNode: row,
						childNodes: [{
								tag: "input",
								name: name,
								type: "radio",
								checked: rowOption.selected && columnOption.selected,
								events: {
									change: function(){
										if (this.checked){
											rowOption.selected = true;
											columnOption.selected = true;
										}
									}
								}
						}]
					});
				})
			})
			
			kkjs.node.create({
				tag: "tr",
				className: "position",
				childNodes: [
					{
						tag: "td",
						innerHTML: "position"
					},
					{
						tag: "td",
						childNodes: [positionTable]
					}
				],
				nextSibling: columns[i]
			});
		}
	}
}());

