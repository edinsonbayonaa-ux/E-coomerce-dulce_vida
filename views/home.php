<?php

session_start();

$registro_ok = isset($_GET['registro']) && $_GET['registro'] == 'ok';

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Producto.php";
require_once __DIR__ . "/../models/Carrito.php";

$id_usuario = $_SESSION['id_usuario'] ?? null;

$database = new Database();
$conn = $database->conectar();

$productoModel = new Producto($conn);
$carritoModel  = new Carrito($conn);

// ===========================
// Productos (menú)
// ===========================
$producto = $productoModel->listar();


// ===========================
// Carrito (modal)
// ===========================
if ($id_usuario) {

    $itemsCarrito = $carritoModel->obtenerItems($id_usuario);

    $totalCompra = 0;
    foreach ($itemsCarrito as $item) {
        $totalCompra += $item['subtotal'];
    }

    $totalCarrito = $carritoModel->contarItems($id_usuario);

} else {

    $itemsCarrito = [];
    $totalCompra = 0;
    $totalCarrito = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dulce Vida</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Yummy
  * Template URL: https://bootstrapmade.com/yummy-bootstrap-restaurant-website-template/
  * Updated: Mar 03 2026 with Bootstrap v5.3.8
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="../views/home.php" class="logo d-flex align-items-center me-auto me-xl-0">
         <img src="../assets/img/Dulce.webp" alt="">
        <div class="colorT">
          <h1 class="sitename" style="color: #8bc0f1; ">Dulce Vida</h1>
        </div>
        <span></span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul style="justify-content: center;">
          <li><a href="#hero" class="active">Inicio<br></a></li>
          <li><a href="#menu">Menu</a></li>
          <li><a href="#gallery">Galeria</a></li>
          <li><a href="#contact">Contacto</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="d-flex align-items-center ms-auto gap-2">

        <li class="ms-5" style="list-style: none;">

          <a href="#"
             onclick="document.getElementById('modalCarrito').style.display='flex'; return false"
             style="position:relative;">

            <i class="bi bi-cart-fill" style="font-size:25px;"></i>

            <?php if ($totalCarrito > 0): ?>
            <span class="badge bg-danger"
                  style="position:absolute; top:-10px; right:-12px;">
                <?php echo $totalCarrito; ?>
            </span>
            <?php endif; ?>

          </a>

          <!-- ============ MODAL CARRITO ============ -->
          <div id="modalCarrito"
               style="display:none;
                      position:fixed;
                      inset:0;
                      background:rgba(0,0,0,0.5);
                      justify-content:center;
                      align-items:center;
                      z-index:9999;">

            <div style="background:#ffffff;
                        padding:30px;
                        border-radius:10px;
                        width:600px;
                        max-height:85vh;
                        overflow:auto;">

              <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="m-0">Carrito de Compras</h2>
                <button type="button"
                        class="btn-close"
                        onclick="document.getElementById('modalCarrito').style.display='none'"></button>
              </div>

              <?php if (count($itemsCarrito) > 0): ?>

              <table class="table align-middle">
                <thead>
                  <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($itemsCarrito as $item): ?>
                  <tr>
                    <td>
                      <img src="<?php echo htmlspecialchars($item['imagen_url_producto']); ?>"
                           alt=""
                           style="width:60px; height:60px; object-fit:cover; border-radius:6px;">
                    </td>
                    <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                    <td><?php echo (int) $item['cantidad']; ?></td>
                    <td>$<?php echo number_format($item['precio_producto'], 2); ?></td>
                    <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                    <td>
                      <a href="../controllers/CarritoController.php?accion=eliminar&id=<?php echo $item['id_item']; ?>"
                         class="btn btn-sm btn-danger">
                        Eliminar
                      </a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

              <h4 class="text-end">TOTAL: $<?php echo number_format($totalCompra, 2); ?></h4>

              <div class="d-flex justify-content-between mt-3">
                <a href="../controllers/CarritoController.php?accion=vaciar" class="btn btn-outline-danger">
                  Vaciar carrito
                </a>
                <button type="button"
                        class="btn btn-success"
                        onclick="document.getElementById('modalCarrito').style.display='none'; document.getElementById('modalPago').style.display='flex';">
                  Pagar
                </button>
              </div>

              <?php else: ?>

              <p>Tu carrito está vacío.</p>

              <?php endif; ?>

              <div class="text-end mt-3">
                <button type="button"
                        class="btn btn-secondary"
                        onclick="document.getElementById('modalCarrito').style.display='none'">
                  Cerrar
                </button>
              </div>

            </div>
          </div>
          <!-- ============ FIN MODAL CARRITO ============ -->
          <!-- ============ MODAL LOGIN ============ -->
                <div id="modalLogin"
          style="display:none;
                  position:fixed;
                  inset:0;
                  background:rgba(0,0,0,.5);
                  justify-content:center;
                  align-items:center;
                  z-index:9999;">

          <div style="background:white;
                      width:450px;
                      padding:30px;
                      border-radius:10px;">

              <div class="d-flex justify-content-between mb-3">
                  <h3>Iniciar Sesión</h3>

                  <button type="button" class="btn-close"
                          onclick="document.getElementById('modalLogin').style.display='none'">
                  </button>
              </div>

              <form action="../controllers/UsuarioController.php" method="POST">

                  <input type="hidden" name="login" value="1">

                  <div class="mb-3">
                      <label>Correo</label>
                      <input type="email"
                            name="email"
                            class="form-control"
                            required>
                  </div>

                  <div class="mb-3">
                      <label>Contraseña</label>
                      <input type="password"
                            name="clave"
                            class="form-control"
                            required>
                  </div>

                  <button type="submit" class="btn btn-success">
                      Ingresar
                  </button>

                  <div class="mt-3 text-center">
                    <a href="#"
                      onclick="
                      document.getElementById('modalLogin').style.display='none';
                      document.getElementById('modalRegistro').style.display='flex';
                      return false;">
                      ¿No tienes cuenta? Regístrate
                    </a>
                </div>
              </form>

          </div>

      </div>
          <!-- ============ FIN MODAL LOGIN============ -->
          <!-- ============ MODAL REGISTRO ============ --> 
                <div id="modalRegistro"
                 style="display:none;
                 position:fixed;
                inset:0;
                background:rgba(0,0,0,.5);
                justify-content:center;
                align-items:center;
                z-index:9999;">

                <div style="background:white;padding:30px;border-radius:10px;width:500px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h3 class="m-0">Registro</h3>

                      <button type="button"
                              class="btn-close"
                              onclick="document.getElementById('modalRegistro').style.display='none'">
                      </button>
                  </div>

                 <form action="../controllers/UsuarioController.php" method="POST">

                      <input type="hidden" name="registrar" value="1">

                      <div class="mb-3">
                          <label>Nombre</label>
                          <input type="text"
                                name="nombre"
                                class="form-control"
                                required>
                      </div>

                      <div class="mb-3">
                          <label>Apellido</label>
                          <input type="text"
                                name="apellido"
                                class="form-control"
                                required>
                      </div>

                      <div class="mb-3">
                          <label>Correo</label>
                          <input type="email"
                                name="email"
                                class="form-control"
                                required>
                      </div>

                      <div class="mb-3">
                          <label>Teléfono</label>
                          <input type="text"
                                name="telefono"
                                class="form-control">
                      </div>

                      <div class="mb-3">
                          <label>Contraseña</label>
                          <input type="password"
                                name="clave"
                                class="form-control"
                                required>
                      </div>

                      <div class="mb-3">
                          <label>Confirmar Contraseña</label>
                          <input type="password"
                                name="confirmar_clave"
                                class="form-control"
                                required>
                      </div>

                      <button type="submit"
                              class="btn btn-primary">
                          Registrarme
                      </button>

                      <button type="button"
                            class="btn btn-secondary"
                            onclick="
                            document.getElementById('modalRegistro').style.display='none';
                            document.getElementById('modalLogin').style.display='flex';">
                        Ya tengo cuenta
                    </button>

                  </form>
                  </div>
                </div>
          <!-- ============ FIN REGISTRO ============ -->
           <!-- ============ INICIO REGISTRADO ============ -->

         <?php if(isset($_GET['registro']) && $_GET['registro'] == 'ok'): ?>
            <div id="mensajeRegistro"
                class="alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3"
                style="z-index:99999;">
                Usuario registrado correctamente. Ya puedes iniciar sesión.
            </div>

            <script>
              setTimeout(function() {
                let msg = document.getElementById('mensajeRegistro');
                if(msg){
                    msg.remove();
                }

                window.history.replaceState({}, document.title, window.location.pathname);
              }, 1000);
            </script>
          <?php endif; ?>
          <!-- ============ FIN REGISTRADO============ -->
          <!-- ============ INICIO VERIFICACIÓN============ -->
           <?php if(isset($_GET['login']) && $_GET['login'] == 'correo'): ?>
              <div id="msgCorreo"
                  class="alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3"
                  style="z-index:99999;">
                  El correo no existe.
              </div>

              <script>
              setTimeout(function(){
                  document.getElementById('msgCorreo')?.remove();
                  window.history.replaceState({}, document.title, window.location.pathname);
              },2000);
              </script>
            <?php endif; ?>


            <?php if(isset($_GET['login']) && $_GET['login'] == 'clave'): ?>
                <div id="msgClave"
                    class="alert alert-warning position-fixed top-0 start-50 translate-middle-x mt-3"
                    style="z-index:99999;">
                    Contraseña incorrecta.
                </div>

                <script>
                setTimeout(function(){
                    document.getElementById('msgClave')?.remove();
                    window.history.replaceState({}, document.title, window.location.pathname);
                },2000);
                </script>
              <?php endif; ?>

              <?php if(isset($_GET['registro']) && $_GET['registro'] == 'clave'): ?>
                  <div id="msgRegistroClave"
                      class="alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3"
                      style="z-index:99999;">
                      Las contraseñas no coinciden.
                  </div>

                  <script>
                  setTimeout(function(){
                      document.getElementById('msgRegistroClave')?.remove();
                      window.history.replaceState({}, document.title, window.location.pathname);
                  },1500);
                  </script>
                <?php endif; ?>

            <!-- ============ FIN VERIFICACIÓN============ -->
          <!-- ============ MODAL PAGO ============ -->
          <div id="modalPago"
               style="display:none;
                      position:fixed;
                      inset:0;
                      background:rgba(0,0,0,0.5);
                      justify-content:center;
                      align-items:center;
                      z-index:9999;">

            <div style="background:#ffffff;
                        padding:30px;
                        border-radius:10px;
                        width:500px;
                        max-height:85vh;
                        overflow:auto;">

              <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="m-0">Datos de Pago</h2>
                <button type="button"
                        class="btn-close"
                        onclick="document.getElementById('modalPago').style.display='none'"></button>
              </div>

              <form action="../controllers/PedidoController.php" method="POST">

                <div class="mb-2">
                  <label class="form-label">Dirección</label>
                  <input type="text" name="direccion" class="form-control" required>
                </div>

                <div class="mb-2">
                  <label class="form-label">Ciudad</label>
                  <input type="text" name="ciudad" class="form-control" required>
                </div>

                <div class="mb-2">
                  <label class="form-label">Departamento</label>
                  <input type="text" name="departamento" class="form-control" required>
                </div>

                <div class="mb-2">
                  <label class="form-label">Código postal</label>
                  <input type="text" name="codigo_postal" class="form-control">
                </div>

                <div class="mb-3">
                  <label class="form-label">Método de pago</label>
                  <select name="metodo_pago" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="Transferencia">Transferencia</option>
                    <option value="Nequi">Nequi</option>
                  </select>
                </div>

                <h5 class="text-end">TOTAL: $<?php echo number_format($totalCompra, 2); ?></h5>

                <div class="d-flex justify-content-between mt-3">
                  <button type="button"
                          class="btn btn-secondary"
                          onclick="document.getElementById('modalPago').style.display='none'">
                    Cancelar
                  </button>
                  <button type="submit" class="btn btn-success">
                    Confirmar Compra
                  </button>
                </div>

              </form>

            </div>
          </div>
          <!-- ============ FIN MODAL PAGO ============ -->

        </li>

        <!-- Usuario -->
        <?php if(isset($_SESSION['id_usuario'])): ?>
         <div class="d-flex align-items-center gap-2">
            <span style="font-weight:bold; color:#f18be0;">
            Bienvenido,
            <?php echo htmlspecialchars($_SESSION['nombre']); ?>
            <?php if(
                  isset($_SESSION['rol']) &&
                  $_SESSION['rol'] == 'Administrador'
              ): ?>

              <a href="admin.php"
                class="btn btn-warning">
                  Administrador
              </a>

            <?php endif; ?>
            </span>
            <a class="btn-getstarted" href="../public/logout.php">
            Cerrar Sesión
            </a>
          </div>
        <?php else: ?>
          <div class="d-flex align-items-center gap-2">
            <a class="btn-getstarted"
            href="#"
            onclick="document.getElementById('modalLogin').style.display='flex'; return false;">
            Iniciar Sesión
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

      <div class="container">
        <div class="row gy-4 justify-content-center justify-content-lg-between">
          <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h1 data-aos="fade-up" style="color: #f18be0;">Descubre Deliciosas Creaciones Artesanales<br>Dulces Deliciosos</h1>
            <p data-aos="fade-up" data-aos-delay="100" style="color: rgb(0, 0, 0);">Somos Dulce Vida, una tienda artesanal de dulces donde cada creación se elabora con amor, ingredientes frescos y una verdadera pasión por lo dulce.</p>
            <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
            </div>
          </div>
          <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
            <img src="../assets/img/num2.png" class="img-fluid animated" alt="">
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- Why Us Section -->
    <section id="why-us" class="why-us section light-background">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="why-box">
              <h3>¿Por qué elegir Dulce Vida?</h3>
              <p>
                En Dulce Vida, cada dulce está elaborado con ingredientes frescos y mucho amor. Desde chocolates artesanales hasta gomitas y caramelos, tenemos el sabor perfecto para cada ocasión. Endulza tu día con nuestras creaciones únicas, hechas especialmente para ti.
              </p>
              <div class="text-center">
                <a href="#" class="more-btn"><span>Más Información</span> <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </div><!-- End Why Box -->

          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">

              <div class="col-xl-4">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-clipboard-data"></i>
                  <h4>Fresco y Hecho a Mano</h4>
                  <p>Cada dulce es elaborado cuidadosamente a mano utilizando únicamente ingredientes naturales de la más alta calidad, garantizando un sabor único y delicioso en cada bocado.</p>
                </div>
              </div><!-- End Icon Box -->

              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-gem"></i>
                  <h4>Calidad Premium</h4>
                  <p>Seleccionamos los mejores chocolates, frutas y sabores para crear dulces verdaderamente especiales, porque mereces nada menos que lo mejor.</p>
                </div>
              </div><!-- End Icon Box -->

              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-inboxes"></i>
                  <h4>Hecho con Amor</h4>
                  <p>Cada pedido se prepara con dedicación y pasión. Ya sea un regalo especial o un gusto personal, Dulce Vida hace que cada momento sea más dulce.</p>
                </div>
              </div><!-- End Icon Box -->

            </div>
          </div>

        </div>

      </div>

    </section><!-- /Why Us Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section dark-background">

      <img src="../assets/img/num1.png" alt="" data-aos="fade-in">

    </section><!-- /Stats Section -->

    <!-- Menu Section -->
    <section id="menu" class="menu section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Menu</h2>
        <p><span>Mira</span> <span class="description-title">Menu De Dulce Vida</span></p>
      </div><!-- End Section Title -->

      <div class="container">

        <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">

        </ul>

        <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

          <div class="tab-pane fade active show" id="menu-starters">

            <div class="tab-header text-center">
              <h3>Nuestros Productos</h3>
            </div>

            <div class="row gy-5">

              <?php foreach ($producto as $producto): ?>

              <div class="col-lg-4 menu-item">
                <a href="../assets/img/gallery/num<?php echo $producto['id_producto']; ?>.PNG" class="glightbox">
                  <img src="../assets/img/gallery/num<?php echo $producto['id_producto']; ?>.PNG"
                  class="menu-img img-fluid"
                  alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
                </a>
                <h4><?php echo htmlspecialchars($producto['nombre_producto']); ?></h4>
                <p class="ingredients">
                  <?php echo htmlspecialchars($producto['descripcion_producto']); ?>
                </p>
                <p class="price">
                  $<?php echo number_format($producto['precio_producto'], 2); ?>
                </p>

                <?php if ($producto['stock_producto'] > 0): ?>
                <form action="../controllers/CarritoController.php" method="POST">
                  <input type="hidden" name="accion" value="agregar">
                  <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                  <button type="submit" class="btn btn-primary">
                    Agregar al carrito
                  </button>
                </form>
                <?php else: ?>
                <button type="button" class="btn btn-secondary" disabled>
                  Sin stock
                </button>
                <?php endif; ?>

              </div><!-- Menu Item -->

              <?php endforeach; ?>

            </div>
          </div><!-- End Starter Menu Content -->

        </div>

      </div>

    </section><!-- /Menu Section -->

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Galeria</h2>
        <p><span>Revisar</span> <span class="description-title">Nuestra Galería</span></p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "centeredSlides": true,
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 0
                },
                "768": {
                  "slidesPerView": 3,
                  "spaceBetween": 20
                },
                "1200": {
                  "slidesPerView": 5,
                  "spaceBetween": 20
                }
              }
            }
          </script>
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="../assets/img/gallery/num1.PNG"><img src="../assets/img/gallery/num1.PNG" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="../assets/img/gallery/num2.PNG"><img src="../assets/img/gallery/num2.PNG" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="../assets/img/gallery/num3.PNG"><img src="../assets/img/gallery/num3.PNG" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="../assets/img/gallery/num4.PNG"><img src="../assets/img/gallery/num4.PNG" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="../assets/img/gallery/num5.PNG"><img src="../assets/img/gallery/num5.PNG" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="../assets/img/gallery/num6.PNG"><img src="../assets/img/gallery/num6.PNG" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="../assets/img/gallery/num8.PNG"><img src="../assets/img/gallery/num8.PNG" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a class="glightbox" data-gallery="images-gallery" href="../assets/img/gallery/num9.PNG"><img src="../assets/img/gallery/num9.PNG" class="img-fluid" alt=""></a></div>
          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Gallery Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contacto</h2>
        <p><span>Necesitas Ayuda?</span> <span class="description-title">Contactanos</span></p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="mb-5">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947.9553587136775!2d-73.60534592623553!3d8.307235299871703!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e5d8583da1e8655%3A0x58884632ede3a0d8!2sC.%208%20%23%2033-45%2C%20Aguachica%2C%20Cesar!5e0!3m2!1ses!2sco!4v1780931499065!5m2!1ses!2sco" width="100%" height="50%" allowfullscreen="true" style="border:0;"></iframe>
        </div><!-- End Google Maps -->

        <div class="row gy-4">

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
              <i class="icon bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Address</h3>
                <p>CLL 8 # 33 - 45 Aguachica</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="300">
              <i class="icon bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Call Us</h3>
                <p>+57 3159035224</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="500">
              <i class="icon bi bi-clock flex-shrink-0"></i>
              <div>
                <h3>Opening Hours<br></h3>
                <p><strong>Mon-Sat:</strong> 11AM - 23PM; <strong>Sunday:</strong> Closed</p>
              </div>
            </div>
          </div><!-- End Info Item -->

        </div>

        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="600">
          <div class="row gy-4">

            <div class="col-md-6">
              <input type="text" name="name" class="form-control" placeholder="Your Name" required="" style="background-color: #a9cff3; color: black; border: #8bc0f1;">
            </div>

            <div class="col-md-6 ">
              <input type="email" class="form-control" name="email" placeholder="Your Email" required="" style="background-color: #a9cff3; color: black; border: #8bc0f1;">
            </div>

            <div class="col-md-12">
              <input type="text" class="form-control" name="subject" placeholder="Subject" required="" style="background-color: #a9cff3; color: black; border: #8bc0f1;">
            </div>

            <div class="col-md-12">
              <textarea class="form-control" name="message" rows="6" placeholder="Message" required="" style="background-color: #a9cff3; color: black; border: #8bc0f1;"></textarea>
            </div>

            <div class="col-md-12 text-center">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your message has been sent. Thank you!</div>

              <button type="submit">Send Message</button>
            </div>

          </div>
        </form><!-- End Contact Form -->

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container">
      <div class="row gy-3">
        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-geo-alt icon"></i>
          <div class="address">
            <h4>Address</h4>
            <p>CLL 8 # 33 - 45</p>
            <p>Aguachica</p>
            <p></p>
          </div>

        </div>

        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-telephone icon"></i>
          <div>
            <h4>Contact</h4>
            <p>
              <strong>Phone:</strong> <span>+57 3159035224</span><br>
            </p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-clock icon"></i>
          <div>
            <h4>Opening Hours</h4>
            <p>
              <strong>Mon-Sat:</strong> <span>11AM - 23PM</span><br>
              <strong>Sunday</strong>: <span>Closed</span>
            </p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <h4>Follow Us</h4>
          <div class="social-links d-flex">
            <a href="https://www.instagram.com/ladulcevidartesanal/" class="instagram"><i class="bi bi-instagram"></i></a>
          </div>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Yummy</strong> <span>All Rights Reserved</span></p>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>

</html>
