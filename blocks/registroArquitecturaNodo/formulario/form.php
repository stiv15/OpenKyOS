<?php 
namespace registroArquitecturaNodo\formulario;

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


class Formulario {

	
    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miSql;

    function __construct($lenguaje, $formulario, $sql) {

        $this->miConfigurador = \Configurador::singleton ();

        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );

        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;
        
        $this->miSql = $sql;

    }

    function formulario() {

        /**
         * IMPORTANTE: Este formulario está utilizando jquery.
         * Por tanto en el archivo script/ready.php y script/ready.js se declaran 
         * algunas funciones js que lo complementan.
         */

    	$conexion = "estructura";
    	$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
    	
        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

        // ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
        /**
        * Atributos que deben ser aplicados a todos los controles de este formulario.
        * Se utiliza un arreglo independiente debido a que los atributos individuales se reinician cada vez que se 
        * declara un campo.
        *
        * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
        * $atributos= array_merge($atributos,$atributosGlobales);
        */
        
        $atributosGlobales ['campoSeguro'] = 'true';
                
        if(!isset($_REQUEST['tiempo'])){
        	$_REQUEST['tiempo']=time();
        }
        
        // -------------------------------------------------------------------------------------------------

        // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque ['nombre'];
        $atributos ['id'] = $esteCampo;
        $atributos ['nombre'] = $esteCampo;

        // Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
        $atributos ['tipoFormulario'] = '';

        // Si no se coloca, entonces toma el valor predeterminado 'POST'
        $atributos ['metodo'] = 'POST';

        // Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
        $atributos ['action'] = 'index.php';
        $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );

        // Si no se coloca, entonces toma el valor predeterminado.
        $atributos ['estilo'] = '';
        $atributos ['marco'] = true;
        $tab = 1;
        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------

        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos ['tipoEtiqueta'] = 'inicio';
        echo $this->miFormulario->formularioBootstrap ( $atributos );
        unset($atributos);

        // ---------------- SECCION: Controles del Formulario -----------------------------------------------

        if(isset($_REQUEST['mensaje'])){
        	$esteCampo = 'mensajemodal';
        	$atributos ["id"] = $esteCampo; // No cambiar este nombre
        	$atributos ["tipo"] = "hidden";
        	$atributos ['estilo'] = '';
        	$atributos ["obligatorio"] = false;
        	$atributos ['marco'] = true;
        	$atributos ["etiqueta"] = "";
        	$atributos ['valor'] = $_REQUEST['mensaje'];
        	$atributos = array_merge ( $atributos, $atributosGlobales );
        	echo $this->miFormulario->campoCuadroTexto ( $atributos );
        	unset ( $atributos );
        }
        
        
        $esteCampo = 'ficheros';
        $atributos ['id'] = $esteCampo;
        $atributos ['leyenda'] = "Arquitectura de Nodo (EOC)";
        echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
        unset ( $atributos );
        
        
        // ----------------INICIO CONTROL: Lista Proyecto--------------------------------------------------------
        
        $esteCampo = 'proyecto';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['evento'] = '';
        $atributos ['seleccion'] = - 1;
        $atributos ['deshabilitado'] = false;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
//         $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "valoraconsultar" );
        $matrizItems = array (
        		array (
        				0,
        				' '
        		)
        );
//         $matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
        $atributos ['matrizItems'] = $matrizItems;
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['seleccion'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['seleccion'] = - 1;
        }
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroListaBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Lista Proyecto--------------------------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Nombre del Nodo--------------------------------------------------------
        
        $esteCampo = 'nombre_nodo';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Nombre Nodo--------------------------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Distancia al Nodo--------------------------------------------------------
        
        $esteCampo = 'distancia_nodo';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        // Aplica atributos globales al control
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Distancia al Nodo--------------------------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Total Nodo Hasta Punto de Ingreso--------------------------------------------------------
        
        $esteCampo = 'total_nodo_ptingreso';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = true;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        // Aplica atributos globales al control
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Total Nodo Hasta Punto de Ingreso--------------------------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Distancia Punto de Ingreso Hasta Nodo--------------------------------------------------------
        
        $esteCampo = 'dist_ptingreso_nodo';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        // Aplica atributos globales al control
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Distancia Punto de Ingreso Hasta Nodo--------------------------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Distancia Punto de Ingreso Sobre Posteria Aérea--------------------------------------------------------
        
        $esteCampo = 'dist_ptingreso_posteria';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = true;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        // Aplica atributos globales al control
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Distancia Punto de Ingreso Sobre Posteria Aérea--------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Total Punto de Ingreso Hasta Nodo--------------------------------------------------------
        
        $esteCampo = 'total_ptingreso_nodo';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = true;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        // Aplica atributos globales al control
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Total Punto de Ingreso Hasta Nodo--------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Total Enlace--------------------------------------------------------
        
        $esteCampo = 'total_enlace';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = true;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        // Aplica atributos globales al control
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Total Enlace--------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Total Postes--------------------------------------------------------
        
        $esteCampo = 'total_postes';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        // Aplica atributos globales al control
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Total Postes--------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Master 1--------------------------------------------------------
        
        $esteCampo = 'master1';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        // Aplica atributos globales al control
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Master 1--------------------------------------
        
        // ----------------INICIO CONTROL: Campo Texto Master 2--------------------------------------------------------
        
        $esteCampo = 'master2';
        $atributos ['nombre'] = $esteCampo;
        $atributos ['tipo'] = "text";
        $atributos ['id'] = $esteCampo;
        $atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ["etiquetaObligatorio"] = true;
        $atributos ['tab'] = $tab ++;
        $atributos ['anchoEtiqueta'] = 2;
        $atributos ['estilo'] = "bootstrap";
        $atributos ['evento'] = '';
        $atributos ['deshabilitado'] = false;
        $atributos ['readonly'] = true;
        $atributos ['columnas'] = 1;
        $atributos ['tamanno'] = 1;
        $atributos ['placeholder'] = "";
        $atributos ['valor'] = "";
        $atributos ['ajax_function'] = "";
        $atributos ['ajax_control'] = $esteCampo;
        $atributos ['limitar'] = false;
        $atributos ['anchoCaja'] = 10;
        $atributos ['miEvento'] = '';
        $atributos ['validar'] = 'required';
        // Aplica atributos globales al control
        
        if (isset ( $cargueDatos [$esteCampo] )) {
        	$atributos ['valor'] = $cargueDatos [$esteCampo];
        } else {
        	$atributos ['valor'] = '';
        }
        
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoCuadroTextoBootstrap ( $atributos );
        unset ( $atributos );
         
        // ----------------FIN CONTROL: Campo Texto Master 2--------------------------------------
        
        // ------------------Division para los botones-------------------------
        $atributos ["id"] = "botones";
        $atributos ["estilo"] = "marcoBotones";
        echo $this->miFormulario->division ( "inicio", $atributos );
        unset($atributos);

        // -----------------CONTROL: Botón ----------------------------------------------------------------
        $esteCampo = 'botonContinuar';
        $atributos ["id"] = $esteCampo;
        $atributos ["tabIndex"] = $tab;
        $atributos ["tipo"] = 'boton';
        // submit: no se coloca si se desea un tipo button genérico
        $atributos ['submit'] = true;
        $atributos ["simple"] = true;
        $atributos ["estiloMarco"] = '';
        $atributos ["estiloBoton"] = 'primary';
        $atributos ["block"] = false;
        // verificar: true para verificar el formulario antes de pasarlo al servidor.
        $atributos ["verificar"] = '';
        $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
        $atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
        $tab ++;

        // Aplica atributos globales al control
        $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoBotonBootstrapHtml ( $atributos );
        unset($atributos);
        // -----------------FIN CONTROL: Botón -----------------------------------------------------------

        // ------------------Fin Division para los botones-------------------------
        echo $this->miFormulario->division ( "fin" );

        // ------------------- SECCION: Paso de variables ------------------------------------------------

        
        echo $this->miFormulario->agrupacion ( 'fin' );
        unset ( $atributos );
        
        /**
         * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
         * SARA permite realizar esto a través de tres
         * mecanismos:
         * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
         * la base de datos.
         * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
         * formsara, cuyo valor será una cadena codificada que contiene las variables.
         * (c) a través de campos ocultos en los formularios. (deprecated)
        */

        // En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:

        // Paso 1: crear el listado de variables

        $valorCodificado = "action=" . $esteBloque ["nombre"];
        $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
        $valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
        $valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
        $valorCodificado .= "&opcion=registrarConsumo";
        /**
         * SARA permite que los nombres de los campos sean dinámicos.
         * Para ello utiliza la hora en que es creado el formulario para
         * codificar el nombre de cada campo. 
         */
        $valorCodificado .= "&campoSeguro=" . $_REQUEST['tiempo'];
        // Paso 2: codificar la cadena resultante
        $valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );

        $atributos ["id"] = "formSaraData"; // No cambiar este nombre
        $atributos ["tipo"] = "hidden";
        $atributos ['estilo'] = '';
        $atributos ["obligatorio"] = false;
        $atributos ['marco'] = true;
        $atributos ["etiqueta"] = "";
        $atributos ["valor"] = $valorCodificado;
        echo $this->miFormulario->campoCuadroTexto ( $atributos );
        unset ( $atributos );

        // ----------------FIN SECCION: Paso de variables -------------------------------------------------

        // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------

        // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
        // Se debe declarar el mismo atributo de marco con que se inició el formulario.
        
        $atributos ['marco'] = true;
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario ( $atributos );

        // -----------------INICIO CONTROL: Ventana Modal Mensaje -----------------------------------------------------------
        
        $atributos ['tipoEtiqueta'] = 'inicio';
        $atributos ['titulo'] = 'Mensaje';
        $atributos ['id'] = 'myModalMensaje';
        echo $this->miFormulario->modal ( $atributos );
        unset($atributos);
        
        echo "<h5><p>". $this->lenguaje->getCadena ( $_REQUEST['mensaje'] ) . "</p></h5>";
        
        // -----------------CONTROL: Botón ----------------------------------------------------------------
        $esteCampo = 'regresarConsultar';
        $atributos ["id"] = $esteCampo;
        $atributos ["tabIndex"] = $tab;
        $atributos ["tipo"] = 'boton';
        $atributos ["basico"] = false;
        // submit: no se coloca si se desea un tipo button genérico
        $atributos ['submit'] = true;
        $atributos ["estiloMarco"] = 'text-center';
        $atributos ["estiloBoton"] = 'default';
        $atributos ["block"] = false;
        $atributos ['deshabilitado'] = true;
        
        // verificar: true para verificar el formulario antes de pasarlo al servidor.
        $atributos ["verificar"] = '';
        $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
        $atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
        $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
        $tab ++;
         
        // Aplica atributos globales al control
        //         $atributos = array_merge ( $atributos, $atributosGlobales );
        echo $this->miFormulario->campoBotonBootstrapHtml ( $atributos );
        unset($atributos);
        // -----------------FIN CONTROL: Botón -----------------------------------------------------------
        
        $atributos ['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->modal ( $atributos );
        unset($atributos);
        
        // -----------------FIN CONTROL: Ventana Modal Mensaje -----------------------------------------------------------
        
        }
        
        
        function mensaje() {
        
        	// Si existe algun tipo de error en el login aparece el siguiente mensaje
        	$mensaje = $this->miConfigurador->getVariableConfiguracion ( 'mostrarMensaje' );
        	$this->miConfigurador->setVariableConfiguracion ( 'mostrarMensaje', null );
        
        	if ($mensaje) {
        
        		$tipoMensaje = $this->miConfigurador->getVariableConfiguracion ( 'tipoMensaje' );
        
        		if ($tipoMensaje == 'json') {
        
        			$atributos ['mensaje'] = $mensaje;
        			$atributos ['json'] = true;
        		} else {
        			$atributos ['mensaje'] = $this->lenguaje->getCadena ( $mensaje );
        		}
        		// -------------Control texto-----------------------
        		$esteCampo = 'divMensaje';
        		$atributos ['id'] = $esteCampo;
        		$atributos ["tamanno"] = '';
        		$atributos ["estilo"] = 'information';
        		$atributos ["etiqueta"] = '';
        		$atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
        		echo $this->miFormulario->campoMensaje ( $atributos );
        		unset ( $atributos );
        
        return true;

    }
    
    
    function elementosAdicionales(){
    	
    	

             
        }

        return true;

    }

}

$miFormulario = new Formulario ( $this->lenguaje, $this->miFormulario, $this->sql  );


$miFormulario->formulario ();
$miFormulario->mensaje ();

?>