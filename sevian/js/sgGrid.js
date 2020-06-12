// JavaScript Document
var sgGrid = false, _sgGrid = false;
var _sgGrid = (function($){

	var setHeader = function(table){
		
		var _t = $(table);
		if(!_t){
			return false;
		}
		
		_t.addClass("sgGrid");
		
		var t = _t.get(), w = false, w1 = false;
		
		if(!t.rows[0] || !t.rows[1]){
			return false;
		}
		
		for(var i=0; i< t.rows[0].cells.length; i++){
			w = t.rows[0].cells[i].offsetWidth ;
			w1 = t.rows[1].cells[i].offsetWidth ;
			if(w1 >= w){
				t.rows[0].cells[i].style.minWidth = w1 + "px";
				t.rows[1].cells[i].style.minWidth = w1 + "px";
			}else{
				t.rows[0].cells[i].style.minWidth = w + "px";
				t.rows[1].cells[i].style.minWidth = w + "px";
			}
		}
		
		var thead = $(_t.query("thead"));
		var tbody = $(_t.query("tbody"));
		
		tbody.on("scroll", function(){
			if((this.scrollWidth - (this.scrollLeft + this.clientWidth )) < (this.offsetWidth - this.clientWidth)){
				thead.get().style.marginRight = (this.offsetWidth - this.clientWidth) - (this.scrollWidth - (this.scrollLeft + this.clientWidth )) + "px";
			}else{
				thead.get().style.marginRight = "0px";
			}
			thead.get().scrollLeft = this.scrollLeft;	
		});
	};
	
	return {
		setHeader: function(table){
			setHeader(table);
		}
	};
	
})(_sgQuery);

//_sgGrid.setHeader("#sgQuery8");