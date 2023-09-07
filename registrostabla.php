<?php
    include("conexion.php");
    $con=conectar();
    if(!$con) {     
        echo"Error al conectar a la base de datos"; 
        exit();               
    }
    //Recibo el contador
    $contador = $_POST['contador'];
    $cantFilas=0;
    //1.- Obtengo la cantidad de Registros
    $sqlCantRows = "Select IsNull(Count(*),0) Cantidad From vsp_DefectosCalidad Where	Fecha	=	Convert(Date,GetDate()) And		TerminoProceso	=	'N'";
    $resultCantRows=sqlsrv_query($con,$sqlCantRows);
    while($mostrarCantRows=sqlsrv_fetch_array($resultCantRows)){
        $cantFilas=$mostrarCantRows['Cantidad'];
    }
    //2.- Obtengo Cantidad de Páginas
    $per_page = 12;
    $entero=0;
    $start=1;
    $desde  =   0;
    if($cantFilas>0){
        $numeroPaginas=floor($cantFilas/$per_page);
        if($cantFilas%$per_page>0){
            $numeroPaginas=$numeroPaginas+1;
        }
        
        if($contador>$numeroPaginas){
            $entero =   floor($contador/$numeroPaginas);
            $pagina =   $entero;
        }else{
            $pagina =   $contador;
        }
        if($pagina  >   1){
            $desde  =   (($pagina*($per_page-1))-$per_page)+1;

        }else{
            //$desde  =   $contador-1;
            $desde  =   0;
        }
        if($numeroPaginas==1){
            $pagina =   1;
            $desde  =   0;
        }
        /*
        echo "Desde ->".$desde;
        echo "          Cant Filas   ->".$per_page;
        */
        echo"<div class='titulo_tabla_dash'>";
            echo"<h2>Detalle Defectos Calidad </h2>";
            echo"<h2 class='titulo_tabla_page'>Pág ".$pagina." De ".$numeroPaginas."</h2>";
        echo"</div>";

        $sql="  
                Select	--Fecha			,
                        Maquina			,
                        NoIngreso		,
                        NoAguaje		,
                        Talla			,
                        NoOrden			,
                        Muestra			,
                        CabezaAnaranjada,
                        CabezaDesgonzada,
                        CabezaFloja		,
                        Flacido         ,
                        Mudado	        ,
                        ManchasNegras	,
                        Uniformidad
                From	vsp_DefectosCalidad 
                Where	Fecha	=	Convert(Date,GetDate())
                Order   By      fecha       ,
                                Maquina     , 
                                NoIngreso   ,
                                Muestra
                OFFSET $desde ROWS 
                FETCH NEXT $per_page ROWS ONLY";
        $result=sqlsrv_query($con,$sql);
        echo"<table>";
                echo"<thead>";
                    echo"<tr>";
                        echo"<th class='ancho_celdas_normales'> Máquina             </th>";
                        echo"<th class='ancho_celdas_normales'> # Ingreso           </th>";
                        echo"<th class='ancho_celdas_normales'> Talla               </th>";
                        echo"<th class='ancho_celdas_normales'> # Orden             </th>";
                        echo"<th class='ancho_celdas_normales'> Muestra             </th>";
                        echo"<th class='ancho_celdas_normales'> Cabeza Anaranjada   </th>";
                        echo"<th class='ancho_celdas_normales'> Cabeza Desgonzada   </th>";
                        echo"<th class='ancho_celdas_normales'> Cabeza Floja        </th>";
                        echo"<th class='ancho_celdas_normales'> Flácido             </th>";
                        echo"<th class='ancho_celdas_normales'> Mudado              </th>";
                        echo"<th class='ancho_celdas_normales'> Manchas Negras      </th>";
                        echo"<th class='ancho_celdas_normales'> Uniformidad         </th>";

                        /* echo"<th class='ancho_celdas_normales'>  </th>";
                        echo"<th class='ancho_celdas_normales'> T. Tratamiento </th>";
                        echo"<th class='ancho_celdas_normales'> T. Max Trat. Ini. </th>";
                        echo"<th class='ancho_celdas_barra'> </th>";
                        echo"<th class='ancho_celdas_normales'>  </th>";
                        echo"<th class='ancho_celdas_normales'> Prom. Resid. </th>";
                        echo"<th class='ancho_celdas_normales'> T. Pesca Planta</th>";
                        echo"<th class='ancho_celdas_normales'> T. Esp. Recepciòn </th>";
                        echo"<th class='ancho_celdas_barra'>    </th>";
                        echo"<th class='ancho_celdas_normales'> T. Tot. Espera </th>"; */
                        
                    echo"</tr>";
                echo"</thead>";
                echo"<tbody>";
                    while($mostrar=sqlsrv_fetch_array($result)){
                        echo"<tr>";
                        	echo"<td>".$mostrar['Maquina']."</td>";
                            echo"<td>".$mostrar['NoIngreso']."</td>";
                            echo"<td>".$mostrar['Talla']."</td>";
                            echo"<td>".$mostrar['NoOrden']."</td>";
                            echo"<td>".$mostrar['Muestra']."</td>";
                            echo"<td>".$mostrar['CabezaAnaranjada']."</td>";
                            echo"<td>".$mostrar['CabezaDesgonzada']."</td>";
                            echo"<td>".$mostrar['CabezaFloja']."</td>";
                            echo"<td>".$mostrar['Flacido']."</td>";
                            echo"<td>".$mostrar['Mudado']."</td>";
                            echo"<td>".$mostrar['ManchasNegras']."</td>";
                            echo"<td>".$mostrar['Uniformidad']."</td>";

                            
                        echo"</tr>";
                            
                    }
                echo"</tbody>";
        echo"</table>";
    }else{
        echo"<div class='titulo_tabla_dash'>";
            echo"<h2>Detalle Guìas de Pesca (CC x CC)</h2>";
            //echo"<h2 class='titulo_tabla_page'>Pág ".$pagina." De ".$numeroPaginas."</h2>";
        echo"</div>";
        echo"<table>";
                echo"<thead>";
                    echo"<tr>";
                        echo"<th class='ancho_celdas_normales'> Máquina             </th>";
                        echo"<th class='ancho_celdas_normales'> # Ingreso           </th>";
                        echo"<th class='ancho_celdas_normales'> Talla               </th>";
                        echo"<th class='ancho_celdas_normales'> # Orden             </th>";
                        echo"<th class='ancho_celdas_normales'> Muestra             </th>";
                        echo"<th class='ancho_celdas_normales'> Cabeza Anaranjada   </th>";
                        echo"<th class='ancho_celdas_normales'> Cabeza Desgonzada   </th>";
                        echo"<th class='ancho_celdas_normales'> Cabeza Floja        </th>";
                        echo"<th class='ancho_celdas_normales'> Flácido             </th>";
                        echo"<th class='ancho_celdas_normales'> Mudado              </th>";
                        echo"<th class='ancho_celdas_normales'> Manchas Negras      </th>";
                        echo"<th class='ancho_celdas_normales'> Uniformidad         </th>";    

                    echo"</tr>";
                echo"</thead>";
                echo"<tbody>";
                    
                echo"</tbody>";
        echo"</table>";
    }
    


    
    /*
    $query = "SELECT * FROM tabla LIMIT $start, $per_page";
    $result = mysqli_query($conn, $query);
    */
    // Mostrar los registros en una tabla HTML
    /* echo "<table>";
    while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['campo1'] . "</td>";
    echo "<td>" . $row['campo2'] . "</td>";
    echo "</tr>";
    }
    echo "</table>"; */


?>