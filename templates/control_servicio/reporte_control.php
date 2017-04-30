<?php 
    session_start();
    if (!$_SESSION || $_SESSION['tipo'] == 2) {
        header("Location:../form_login.php");
    }
    else {
        include("../conexion.php");
        $buscar_controles = "SELECT * FROM control_servicio ORDER BY fecha_control DESC";
        $resultado_control = mysqli_query($conexion, $buscar_controles);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte Consultas</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header>
        <div class="header--menu">
            <input type="button" value="Menú" onclick="Mostrar_Slider()" class="header--menu__button">
            <div class="header--menu__slider" id="slider" style="left: -300px;">
                <?php if ($_SESSION['tipo'] != 2) { ?>
                    <ul>
                        <li class="categoria">
                            <input type="button" class="header--menu__link" onclick="Mostrar_Clientes()" value="Clientes">
                            <ul id="funciones_clientes" style="height: 0px">
                                <li><a href="../cliente/form_alta_cliente.php" class="header--menu__link">Registrar cliente</a></li>
                                <li><a href="../cliente/reporte_clientes.php" class="header--menu__link">Reporte clientes</a></li>
                            </ul>
                        </li>
                        <li class="categoria">
                            <input type="button" class="header--menu__link" onclick="Mostrar_Consultas()" value="Consultas">
                            <ul id="funciones_consultas" style="height: 0px">
                                <li><a href="form_alta_control.php" class="header--menu__link">Registrar consulta</a></li>
                                <li><a href="reporte_control.php" class="header--menu__link">Reporte consultas</a></li>
                            </ul>
                        </li>
                        <li class="categoria">
                            <input type="button" class="header--menu__link" onclick="Mostrar_Facturas()" value="Facturas">
                            <ul id="funciones_facturas" style="height: 0px">
                                <li><a href="../factura/form_alta_factura.php" class="header--menu__link">Registrar factura</a></li>
                                <li><a href="../factura/reporte_facturas.php" class="header--menu__link">Reporte facturas</a></li>
                            </ul>
                        </li>
                        <li class="categoria">
                            <input type="button" class="header--menu__link" onclick="Mostrar_Mascotas()" value="Mascotas">
                            <ul id="funciones_mascotas" style="height: 0px">
                                <li><a href="../mascota/form_alta_mascota.php" class="header--menu__link">Registrar mascota</a></li>
                            </ul>
                        </li>
                        <li class="categoria">
                            <input type="button" class="header--menu__link" onclick="Mostrar_Medicos()" value="Médicos">
                            <ul id="funciones_medicos" style="height: 0px">
                                <li><a href="../medico/form_alta_medico.php" class="header--menu__link">Registrar médico</a></li>
                                <li><a href="../medico/reporte_medicos.php" class="header--menu__link">Reporte medicos</a></li>
                            </ul>
                        </li>
                        <li class="categoria">
                            <input type="button" class="header--menu__link" onclick="Mostrar_Servicios()" value="Servicios">
                            <ul id="funciones_servicios" style="height: 0px">
                                <li><a href="../servicio/form_alta_servicio.php" class="header--menu__link">Registrar servicio</a></li>
                                <li><a href="../servicio/reporte_servicios.php" class="header--menu__link">Reporte servicios</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php } else {?>
                <ul>
                    <li class="categoria">
                        <a href="../mascota/reporte_mascotas.php?cliente=<?php echo $_SESSION['nombre']; ?>" class="header--menu__link">Consultar mascotas</a>
                    </li>
                    <li class="categoria">
                        <a href="../cita/form_alta_cita.php" class="header--menu__link">Agendar cita</a>
                        <a href="../cita/reporte_citas.php" class="header--menu__link">Consultar citas</a>
                    </li>
                </ul>
                <?php } ?>
            </div>
        </div>
        <div class="header--title">
            <a href="../bienvenida.php" class="header--title__name">Veterinaria</a>
        </div>
        <div class="header--nav">
           <a href="../logout.php" class="header--nav__link">Cerrar Sesión</a>
        </div>
    </header>
    <section class="wrap" id="wrap">
        <?php 
            while($control = mysqli_fetch_assoc($resultado_control)) {
        ?>
            <div class="card">
                <div class="card--title">
                    <h1 class="card--title__name"><?php echo $control['fecha_control'] ?></h1>
                    <?php if ($_SESSION['tipo'] != 2) { ?>
                        <nav class="card--title__menu">
                            <a href="form_modificar_control.php?control=<?php echo $control['clave_control_servicio'] ?>" class="card--title__item">Modificar</a>
                            <a href="eliminar_control.php?control=<?php echo $control['clave_control_servicio'] ?>" class="card--title__item">Eliminar</a>
                            <a href="../factura/alta_factura.php?control=<?php echo $control['clave_control_servicio'] ?>" class="card--title__item">Facturar</a>
                        </nav>
                    <?php } ?>
                </div>

                <?php 
                    $buscar_mascota = mysqli_query($conexion, "SELECT mascota.nombre_mascota AS nombre_mascota FROM control_servicio JOIN mascota WHERE control_servicio.id_mascota = mascota.id_mascota"); 
                    $mascota = mysqli_fetch_assoc($buscar_mascota);
                ?>
                <p class="card__data"><?php echo $mascota['nombre_mascota'] ?></p>

                <?php 
                    $buscar_medico = mysqli_query($conexion, "SELECT medico.nombre_medico AS nombre_medico FROM control_servicio JOIN medico WHERE control_servicio.rfc_medico = medico.rfc_medico"); 
                    $medico = mysqli_fetch_assoc($buscar_medico);
                ?>
                <p class="card__data"><?php echo $medico['nombre_medico'] ?></p>

                <?php 
                    $buscar_servicios = "SELECT servicio.descripcion_servicio AS descripcion_servicio FROM control_servicio_servicio JOIN servicio WHERE control_servicio_servicio.clave_servicio = servicio.clave_servicio AND control_servicio_servicio.clave_control_servicio = '$control[clave_control_servicio]'";
                    $resultado_servicio = mysqli_query($conexion, $buscar_servicios);
                    while($servicio = mysqli_fetch_assoc($resultado_servicio)) { 
                ?>
                    <p class="card__data"><?php echo $servicio['descripcion_servicio'] ?></p>
                <?php } ?>
            </div>
        <?php
            }
            mysqli_close($conexion);
        ?>
    </section>
    <footer>
        <div class="copy">
            <a href="../innovasoft.html">&copy Innovasoft 2017</a>
        </div>
    </footer>

    <script src="../../js/funciones.js"></script>
</body>
</html>