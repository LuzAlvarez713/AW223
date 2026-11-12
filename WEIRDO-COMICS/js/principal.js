
document.addEventListener("DOMContentLoaded", function () {

    console.log("Weirdo Comics: JavaScript cargado correctamente");


   
    // 1. BUSCADOR DE CLIENTES
    

    const buscarCliente = document.getElementById("buscarCliente");
    const btnBuscarCliente = document.getElementById("btnBuscarCliente");

    if (buscarCliente && btnBuscarCliente) {

        function filtrarClientes() {

            const texto = buscarCliente.value.toLowerCase().trim();
            const filas = document.querySelectorAll(".tabla-clientes tbody tr");

            filas.forEach(function (fila) {
                const informacion = fila.textContent.toLowerCase();
                fila.style.display = informacion.includes(texto) ? "" : "none";
            });

        }

        btnBuscarCliente.addEventListener("click", filtrarClientes);

        buscarCliente.addEventListener("keydown", function (evento) {
            if (evento.key === "Enter") filtrarClientes();
        });

        buscarCliente.addEventListener("input", function () {
            if (buscarCliente.value.trim() === "") filtrarClientes();
        });

    }


    
    // 2. BUSCADOR Y FILTROS DE PRODUCTOS
   

    const buscarProducto = document.getElementById("buscarProducto");
    const btnBuscarProducto = document.getElementById("btnBuscarProducto");
    const filtroCategoria = document.getElementById("filtroCategoria");
    const filtroDisponibilidad = document.getElementById("filtroDisponibilidad");

    if (buscarProducto && btnBuscarProducto && filtroCategoria && filtroDisponibilidad) {

        function filtrarProductos() {

            const texto = buscarProducto.value.toLowerCase().trim();
            const categoria = filtroCategoria.value;
            const disponibilidad = filtroDisponibilidad.value;

            const filas = document.querySelectorAll(".tabla-productos tbody tr");

            filas.forEach(function (fila) {

                const informacion = fila.textContent.toLowerCase();
                const categoriaProducto = fila.dataset.categoria;
                const estadoProducto = fila.dataset.estado;

                const coincideTexto = informacion.includes(texto);
                const coincideCategoria = categoria === "" || categoriaProducto === categoria;
                const coincideDisponibilidad = disponibilidad === "" || estadoProducto === disponibilidad;

                fila.style.display =
                    coincideTexto && coincideCategoria && coincideDisponibilidad ? "" : "none";

            });

        }

        btnBuscarProducto.addEventListener("click", filtrarProductos);

        buscarProducto.addEventListener("keydown", function (evento) {
            if (evento.key === "Enter") filtrarProductos();
        });

        filtroCategoria.addEventListener("change", filtrarProductos);
        filtroDisponibilidad.addEventListener("change", filtrarProductos);
        buscarProducto.addEventListener("input", filtrarProductos);

    }


   
    // 3. PUNTO DE VENTA
  

    const catalogoVenta = document.getElementById("catalogoVenta");

    if (catalogoVenta) {

        const buscarVenta = document.getElementById("buscarVenta");
        const btnBuscarVenta = document.getElementById("btnBuscarVenta");
        const botonesCategorias = document.querySelectorAll("[data-categoria]");
        const listaCarrito = document.getElementById("listaCarrito");

        const cliente = document.getElementById("cliente");
        const pago = document.getElementById("pago");

        const subtotalVenta = document.getElementById("subtotalVenta");
        const ivaVenta = document.getElementById("ivaVenta");
        const totalVenta = document.getElementById("totalVenta");

        const indicadorVenta = document.getElementById("indicadorVenta");
        const indicadorProductos = document.getElementById("indicadorProductos");
        const indicadorCliente = document.getElementById("indicadorCliente");

        const btnLimpiarVenta = document.getElementById("btnLimpiarVenta");
        const btnCobrarVenta = document.getElementById("btnCobrarVenta");

        const mensajeCatalogo = document.getElementById("mensajeCatalogo");
        const mensajeVenta = document.getElementById("mensajeVenta");

        let categoriaSeleccionada = "todos";

        // Productos iniciales de ejemplo
        let carrito = [
            {
                nombre: "Spider-Man #1",
                codigo: "COM-001",
                precio: 120,
                cantidad: 1,
                stock: 35
            },
            {
                nombre: "Batman: Año Uno",
                codigo: "COM-002",
                precio: 180,
                cantidad: 1,
                stock: 18
            },
            {
                nombre: "Naruto Vol. 1",
                codigo: "MAN-001",
                precio: 150,
                cantidad: 1,
                stock: 5
            },
            {
                nombre: "Bolsa de compra",
                codigo: "BOL-001",
                precio: 40,
                cantidad: 1,
                stock: 100
            }
        ];

        // Formato de moneda
        function formatoMoneda(cantidad) {
            return cantidad.toLocaleString("es-MX", {
                style: "currency",
                currency: "MXN"
            });
        }


       
        // 4. MOSTRAR PRODUCTOS
      

        function filtrarCatalogo() {

            const texto = buscarVenta.value.toLowerCase().trim();
            const productos = catalogoVenta.querySelectorAll(".producto-venta");

            let encontrados = 0;

            productos.forEach(function (producto) {

                const nombre = producto.dataset.nombre.toLowerCase();
                const codigo = producto.dataset.codigo.toLowerCase();
                const categoria = producto.dataset.categoria;

                const coincideTexto =
                    nombre.includes(texto) || codigo.includes(texto);

                const coincideCategoria =
                    categoriaSeleccionada === "todos" ||
                    categoria === categoriaSeleccionada;

                if (coincideTexto && coincideCategoria) {
                    producto.style.display = "";
                    encontrados++;
                } else {
                    producto.style.display = "none";
                }

            });

            mensajeCatalogo.textContent =
                encontrados === 0 ? "No se encontraron productos." : "";

        }

        btnBuscarVenta.addEventListener("click", filtrarCatalogo);
        buscarVenta.addEventListener("input", filtrarCatalogo);

        buscarVenta.addEventListener("keydown", function (evento) {
            if (evento.key === "Enter") filtrarCatalogo();
        });


     
        // 5. FILTRAR POR CATEGORÍA
       
        botonesCategorias.forEach(function (boton) {

            boton.addEventListener("click", function () {

                categoriaSeleccionada = boton.dataset.categoria;

                botonesCategorias.forEach(function (otroBoton) {
                    otroBoton.classList.remove("categoria-activa");
                });

                boton.classList.add("categoria-activa");

                filtrarCatalogo();

            });

        });


       
        // 6. AGREGAR PRODUCTOS AL CARRITO
       

        const botonesAgregar = catalogoVenta.querySelectorAll(".btn-agregar");

        botonesAgregar.forEach(function (boton) {

            boton.addEventListener("click", function () {

                const tarjeta = boton.closest(".producto-venta");

                const nombre = tarjeta.dataset.nombre;
                const codigo = tarjeta.dataset.codigo;
                const precio = Number(tarjeta.dataset.precio);
                const stock = Number(tarjeta.dataset.stock);

                const productoExistente = carrito.find(function (producto) {
                    return producto.codigo === codigo;
                });

                if (productoExistente) {

                    if (productoExistente.cantidad >= stock) {
                        alert("Ya alcanzaste el límite de existencias de este producto.");
                     return;
                    }

                    productoExistente.cantidad++;

                } else {

                    if (stock <= 0) {
                        alert("Este producto está agotado.");
                        return;
                    }

                    carrito.push({
                        nombre: nombre,
                        codigo: codigo,
                        precio: precio,
                        cantidad: 1,
                        stock: stock
                    });

                }

                actualizarCarrito();

                mensajeVenta.textContent = nombre + " agregado al carrito.";

            });

        });


       
        // 7. ACTUALIZAR CARRITO Y TOTALES
      

        function actualizarCarrito() {

            listaCarrito.innerHTML = "";

            if (carrito.length === 0) {

                listaCarrito.innerHTML =
                    "<p class='aviso-venta'>No hay productos en el carrito.</p>";

            }

            let total = 0;
            let cantidadProductos = 0;

            carrito.forEach(function (producto, indice) {

                const importe = producto.precio * producto.cantidad;

                total += importe;
                cantidadProductos += producto.cantidad;

                const articulo = document.createElement("div");
                articulo.className = "articulo-carrito";

                const informacion = document.createElement("div");

                const nombre = document.createElement("strong");
                nombre.textContent = producto.nombre;

                const detalle = document.createElement("small");
                detalle.textContent =
                    producto.cantidad + " × " + formatoMoneda(producto.precio);

                informacion.appendChild(nombre);
                informacion.appendChild(detalle);

                const controles = document.createElement("div");

                const botonMenos = document.createElement("button");
                botonMenos.type = "button";
                botonMenos.textContent = "−";
                botonMenos.setAttribute("aria-label", "Restar cantidad");

                botonMenos.addEventListener("click", function () {
                    producto.cantidad--;

                    if (producto.cantidad <= 0) {
                        carrito.splice(indice, 1);
                    }

                    actualizarCarrito();
                });

                const cantidad = document.createElement("span");
                cantidad.textContent = " " + producto.cantidad + " ";

                const botonMas = document.createElement("button");
                botonMas.type = "button";
                botonMas.textContent = "+";
                botonMas.setAttribute("aria-label", "Aumentar cantidad");

                botonMas.addEventListener("click", function () {

                    if (producto.cantidad >= producto.stock) {
                        alert("No hay más existencias disponibles.");
                        return;
                    }

                    producto.cantidad++;
                    actualizarCarrito();

                });

                controles.appendChild(botonMenos);
                controles.appendChild(cantidad);
                controles.appendChild(botonMas);

                const importeTexto = document.createElement("span");
                importeTexto.textContent = formatoMoneda(importe);

                articulo.appendChild(informacion);
                articulo.appendChild(controles);
                articulo.appendChild(importeTexto);

                listaCarrito.appendChild(articulo);

            });

            // Los precios del catálogo incluyen IVA
            const subtotal = total / 1.16;
            const iva = total - subtotal;

            subtotalVenta.textContent = formatoMoneda(subtotal);
            ivaVenta.textContent = formatoMoneda(iva);
            totalVenta.textContent = formatoMoneda(total);

            indicadorVenta.textContent = formatoMoneda(total);
            indicadorProductos.textContent = cantidadProductos;

            indicadorCliente.textContent = cliente.value === "" ? "0" : "1";

            btnCobrarVenta.textContent = "Cobrar " + formatoMoneda(total);

            // Desactivar botones cuando se alcanza el inventario
            botonesAgregar.forEach(function (boton) {

                const tarjeta = boton.closest(".producto-venta");
                const codigo = tarjeta.dataset.codigo;
                const stock = Number(tarjeta.dataset.stock);

                const producto = carrito.find(function (item) {
                    return item.codigo === codigo;
                });

                boton.disabled = stock <= 0 ||
                    (producto && producto.cantidad >= stock);

            });

        }


       
        // 8. ACTUALIZAR CLIENTE
        

        cliente.addEventListener("change", function () {
            indicadorCliente.textContent = cliente.value === "" ? "0" : "1";
        });


     
        // 9. LIMPIAR VENTA
       

        btnLimpiarVenta.addEventListener("click", function () {

            if (carrito.length === 0) {
                mensajeVenta.textContent = "El carrito ya está vacío.";
                return;
            }

            const confirmar = confirm("¿Deseas eliminar todos los productos de la venta?");

            if (confirmar) {

                carrito = [];

                cliente.value = "";
                pago.value = "";

                mensajeVenta.textContent = "La venta se limpió correctamente.";

                actualizarCarrito();

            }

        });


      
        // 10. COBRAR VENTA DE EJEMPLO
       

        btnCobrarVenta.addEventListener("click", function () {

            if (carrito.length === 0) {
                alert("Agrega al menos un producto antes de cobrar.");
                return;
            }

            if (cliente.value === "") {
                alert("Selecciona un cliente.");
                cliente.focus();
                return;
            }

            if (pago.value === "") {
                alert("Selecciona un método de pago.");
                pago.focus();
                return;
            }

            const total = carrito.reduce(function (acumulado, producto) {
                return acumulado + producto.precio * producto.cantidad;
            }, 0);

            alert(
                "¡Venta de ejemplo preparada!\n\n" +
                "Cliente: " + cliente.value + "\n" +
                "Método de pago: " + pago.value + "\n" +
                "Total: " + formatoMoneda(total) 
            );

            mensajeVenta.textContent =
                "Demostración de cobro completada. No se guardó información.";

        });


        // Mostrar el carrito inicial
        actualizarCarrito();

    }


    
    // 11. FORMULARIO DEL PERFIL
  

    const formPerfil = document.getElementById("formPerfil");

    if (formPerfil) {

        const mensajePerfil = document.getElementById("mensajePerfil");

        formPerfil.addEventListener("submit", function (evento) {

            evento.preventDefault();

            mensajePerfil.textContent =
                "Datos revisados correctamente. Esta demostración no guarda cambios permanentemente.";

            alert("Formulario de perfil completado.");

        });

        formPerfil.addEventListener("reset", function () {
            mensajePerfil.textContent = "Se restablecieron los datos del formulario.";
        });

    }


});
