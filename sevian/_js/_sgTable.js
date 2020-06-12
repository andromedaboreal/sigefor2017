// JavaScript Document


var _sgTable = false;


(function(){

	var table = function(opt){
		this.target = document.body;
		
		this._main = false;
		this.rows = [];
		this.cells = [];
		this.nRows = 0;
		this._lastRow = false;
		this._row = false;
		this._cell = false;
		
		var optMain = {};		
		
		for(var x in opt){
			
			if(this.hasOwnProperty(x)){
				this[x] = opt[x];
			}else{
				optMain[x] = opt[x];	
			}
		}

		if(!this._main){
			this.create(optMain);
		}
		
	};
	
	table.prototype = {
		
		
		create: function(opt){
			
			
			this._main = document.createElement("div");
			this._main.dataset.sgType = "sg_table";	
			for(var x in opt){
				this._main[x] = opt[x];	
			}
			
			this.target.appendChild(this._main);	
			
		},
		
		get: function(){
			return this._main;
			
		},
		
		insertRow: function(){
			
			var row = this._row = document.createElement("div");
			row.dataset.sgType = "sg_table_tr";
				
			this._main.appendChild(row);
			
			this.rows[this.nRows] = row;
			this.cells[this.nRows] = [];
			
			this._lastRow = this.nRows++;
			
			return row;			
		
		},
		
		insertCell: function(opt){
			
			var cell = this._cell = document.createElement("div");
			cell.dataset.sgType = "sg_table_td";
			
			for(var x in opt){
				cell[x] = opt[x];	
			}
			
			this._row.appendChild(cell);
			
			this.cells[this._lastRow].push(cell);
			return cell;

		},
		
		appendChild: function(e){
			this._cell.appendChild(e);
		},
		
		setRow: function(index){
			this._row = this.rows[this._lastRow];
			return this._row;	
		},

		getRow: function(){
			return this._row;	
		},


		setCell: function(index){
			this._cell = this.cells[this._lastRow][index];
			return this._cell;
		},

		
		getCell: function(){
			return this._cell;
		},
		
	};


	_sgTable = table;
	
}());

/*
var t = _sgTable.create({});

t.insertRow();
t.insertCell();
t.appendChild(document.createTextNode("Hola"));
t.insertCell();
t.appendChild(document.createTextNode("Adios"));

t.insertRow();
var c = t.insertCell();
t.appendChild(document.createTextNode("D 1"));

c.style.color = "blue";
c.display = "block";
//t.insertCell();
//t.appendChild(document.createTextNode("g 4"));


t.insertRow();
t.insertCell();
t.appendChild(document.createTextNode("Arbol"));
t.insertCell({title:"La Calle 13"});
t.appendChild(document.createTextNode("Calle"));


*/