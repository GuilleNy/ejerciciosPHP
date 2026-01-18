<?php 


function depurar($cadena){
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=htmlspecialchars($cadena);
    return $cadena; 
}
 
function low($cadena){
    return strtolower($cadena);
}

function recogerDatos(){
    $nomUsuario=depurar($_POST['usuario']);
    $contrUsuario=depurar($_POST['clave']);
    return [$nomUsuario, $contrUsuario];
}


function registrarCompra(){
    $conn = conexion_BBDD();

    try{
        $conn->beginTransaction();

        $numCli =  devolverNumCli(); //func_sesiones.php
        $date = date("Y-m-d");
        $codigoOrder = obtenerUltimoCodigo($conn); //otras_funciones.php,   Obtengo el ultimo numero de orden 
        $cestaProductos = devolverCesta();
        $numeroPago = depurar($_POST['pago']);
        $importeTotal=precioTotalCesta();
        $orderLineNumber = 1;

        crearOrders($conn, $codigoOrder , $numCli,  $date, $date);
        
        foreach ($cestaProductos as $productos => $detalles) {
            $idProducto = $detalles[0];
            $priceEach = $detalles[2];
            $cantidadProd = $detalles[3]; //3
            
            crearOrderDetails($conn, $codigoOrder , $idProducto, $cantidadProd, $priceEach, $orderLineNumber);
            actualizarCantidadProd($conn, $idProducto, $cantidadProd);
            $orderLineNumber++;
        }  
        crearPayments($conn, $numCli, $numeroPago, $date, $importeTotal);
    $conn->commit();
    echo "Compra realizada con éxito.";
    }catch(PDOException $e){
        if ($conn->inTransaction()) {
            $conn->rollBack(); 
        }
        echo "Error: " . $e->getMessage();
    }
}


function verifica_campo_login(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['usuario'])) {
        $mensaje .= "El campo Usuario esta vacio.<br>";
        $enviar = False;  
    }

    if (empty($_POST['clave'])) {
        $mensaje .= "El campo Clave esta vacio.<br>";
        $enviar = False;  
    }
    echo $mensaje;
    return $enviar;
}

function verifica_campo_altaPedido(){
    $mensaje = ""; 
    $enviar = True;  

    if(!isset($_POST['producto'])){
        $mensaje .= "No se ha seleccionado un Producto.<br>";
        $enviar = False; 
    }
    
    if (empty($_POST['cantidad'])){
        $mensaje .= "La cantidad esta vacia.<br>";
        $enviar = False; 
    }else{ 
        if(intval(depurar($_POST['cantidad'])) < 0 ){
            echo "La cantidad debe ser igual o mayor de 1.<br>";
        }
    }
    echo $mensaje;
    return $enviar;
}

function verifica_campo_consultaCli(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['numCli'])) {
        $mensaje .= "El campo Numero Cliente esta vacio.<br>";
        $enviar = False;  
    }

    echo $mensaje;
    return $enviar;
}

function verificarNumCli($conn){
    $existe = False;
    $numCli = depurar($_POST['numCli']);
    $cliente = consultarClientes($conn, $numCli);
    if($cliente != False){
        $existe = True;
    }
    return $existe;
}

function verificarCampoProducto(){
    $mensaje = ""; 
    $enviar = True;  

    if (empty($_POST['producto'])) {
        $mensaje .= "El campo producto esta vacio.<br>";
        $enviar = False;  
    }

    echo $mensaje;
    return $enviar;
}

function obtenerUltimoCodigo($conn){

    $ultimoID=consultaUltimaOrder($conn);
    $nuevoID = "";

    if($ultimoID != False){
        $cod = intval($ultimoID['ultima_order']);
        $nuevoID = $cod + 1;
    }else{
        $nuevoID = 10100;
    }
    return $nuevoID ;
}


function verificarPago(){
    $enviar = True;
    $mensaje = " ";

    $numeroPago = depurar($_POST['pago']);

    if(strlen($numeroPago) != 7){
        $enviar = False;
        $mensaje .= "La informacion de pago debe contener 7 caracteres.</br>";
    }else{
        $indice = 0;
        while($enviar && $indice < 2){
            if(!ctype_upper($numeroPago[$indice])){
                $enviar = False;
                $mensaje .= "Los primeros dos caracteres deben ser letras mayusculas.</br>";
            }
            $indice++;
        }

        while($enviar && $indice < strlen($numeroPago)){
            if(!ctype_digit($numeroPago[$indice])){
                $enviar = False;
                $mensaje .= "Los cincos digitos restantes deben ser numeros.</br>";
            }
            $indice++;
        }
    }
    
    if(!$enviar){
        echo $mensaje;
    }
    return $enviar;
}

function mostrarConsultas($conn){
    $consulta = consultaOrdernCli($conn);
    
    if($consulta != False)
    {
        echo "<div id='cesta'>";
            print '<table class="table table-bordered table-hover table-sm text-nowrap">
                    <tr>
                        <th>Order Number</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>';
            
            foreach ($consulta as $row => $valor) {
                $orderNumber = $valor['orderNumber'];
                $orderDetails = consultaOrdernDetails($conn, $orderNumber); //consultas_db.php   
                
                echo "<tr>
                        <td>" . $valor['orderNumber'] . "</td>
                        <td>" . $valor['orderDate'] . "</td>
                        <td>" . $valor['status'] . "</td>
                    </tr>";

                echo "<tr><td colspan='3'>
                        <table class='table table-bordered table-hover table-sm text-nowrap'>
                            <tr>
                                <th>OrderLine Number</th>
                                <th>Order Number</th>
                                <th>Product Name</th>
                                <th>Quantity Ordered</th>
                                <th>Price Each</th>
                            </tr>";
                    foreach ($orderDetails as $detalles => $info) {
                        echo "<tr>
                                <td>" . $info['orderLineNumber'] . "</td>
                                <td>" . $info['orderNumber'] . "</td>
                                <td>" . $info['productName'] . "</td>
                                <td>" . $info['quantityOrdered'] . "</td>
                                <td>" . $info['priceEach'] . "</td>
                            </tr>";
                    }
                echo " </table></td></tr>";
            }
            print "</table>";
        echo "</div>";
    }
}
        
function mostrarStock($conn, $productCode){
    $productos = consultarStockProducto($conn , $productCode);

    //var_dump($productos);
    if($productos != false)
    {
        echo "<div id='cesta'>";
        print '<table class="table table-bordered table-hover table-sm text-nowrap"><tr><th>Nombre Producto</th><th>Cantidad</th></tr>';
        
        print "<tr><td>" . $productos['productName'] . "</td><td>" . $productos['quantityInStock'] . "</td></tr>";
        
        print "</tr>";
        echo "</div>";
    }

}
?>