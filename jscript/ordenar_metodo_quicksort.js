function quickSort(objMatriz,modo) {

    procesoQS(objMatriz,modo,0,objMatriz.length-1) 

}

function procesoQS(objMatriz,modo,ini,fin) {

    var i = ini
    var j = fin
    var tmp

    var c = objMatriz[Math.floor( ( i + j ) / 2 )]

    do {

        if ( modo == "A" ) {

            while ( ( i < fin ) && ( c > objMatriz[i] ) ) i++
            while ( ( j > ini ) && ( c < objMatriz[j] ) ) j-- 

        } else {

            while ( ( i < fin ) && ( c < objMatriz[i] ) ) i++
            while ( ( j > ini ) && ( c > objMatriz[j] ) ) j-- 

        }

        if ( i < j ) {

            tmp = objMatriz[i]
            objMatriz[i] = objMatriz[j]
            objMatriz[j] = tmp 

        }

        if ( i <= j ) {

            i++
            j-- 

        } 

    } while ( i <= j )

    if ( ini < j ) procesoQS(objMatriz,modo,ini,j)
    if ( i < fin ) procesoQS(objMatriz,modo,i,fin) 

}

var prueba = new Array (5,3,12,7,9,4,10,23,8,2)

quickSort(prueba,'A')

//alert(prueba)

quickSort(prueba,'D')

//alert(prueba) 