# ☕ Alianza Project - Gestión de Cafetería
Aplicación modular para la administración de productos, ventas, facturación y reportes de una cafetería.

## 🚀 Tecnologías utilizadas
- **PHP** (Back-end)
- **MySQL** (Base de datos)
- **Bootstrap** (Interfaz gráfica)
- **JavaScript (jQuery)** (Interactividad)
- **Postman** (Pruebas de API)
- **Dompdf** (Generación de reportes PDF)
- **PhpSpreadsheet** (Exportación de informes Excel)

---

## 📁 Estructura del Proyecto

cafeteria/ │── api/                 # API REST para usuarios y productos │   ├── usuario_api/     # CRUD de usuarios │   ├── producto_api/    # CRUD de productos │   ├── auth/            # Autenticación con tokens │── consultas/           # Reportes y estadísticas │   ├── reportes.php     # Panel de reportes │   ├── exportar_pdf.php # Generación de informe en PDF │   ├── exportar_excel.php # Exportación a Excel │── includes/            # Conexión a la base de datos y funciones globales │── admin/               # Panel de administración │── assets/              # Archivos CSS y JS │── index.php            # Página de inicio


---

## 🔑 **Módulos de la Aplicación**
### 1️⃣ **Administración de Usuarios**
✔ **CRUD completo de usuarios**  
✔ **Roles diferenciados (`admin`, `vendedor`)**  
✔ **Autenticación con tokens para acceso seguro**  

### 2️⃣ **Gestión de Productos**
✔ **CRUD de productos**  
✔ **Stock actualizado automáticamente**  
✔ **Búsqueda de producto** para vendedores  

### 3️⃣ **Procesos de Venta**
✔ **Venta de productos** por vendedores  
✔ **Generación automática de facturas**  
✔ **Historial de compras y ventas**  

### 4️⃣ **Reportes y Estadísticas Administradores**
✔ **Reporte de ventas diarias**  
✔ **Producto más vendido**  
✔ **Ingresos por producto**  
✔ **Stock disponible**  
✔ **Exportación de informes en PDF y Excel**  

---

## 📊 **Exportación de Datos**
### 📄 **Generar Reporte en PDF**
### 📊 **Exportar Datos a Excel**


---

## 🏁 **Cómo ejecutar en local**
1. **Configurar la base de datos en `includes/db.php`**.  
2. **Iniciar el servidor local (`XAMPP`, `WAMP`)**.  
3. **Acceder a la aplicación desde `index.php`**.  
4. **Usar el panel de administración para gestionar la cafetería**.  

---


## 🏁 **Cómo ejecutar en InfinityFree**
1. **Configurar la base de datos en `includes/db.php`**, usando las credenciales de InfinityFree.  
2. **Subir los archivos vía FTP o el administrador de archivos de InfinityFree**.  
3. **Acceder a la aplicación desde la URL proporcionada por InfinityFree**.  
4. **Usar el panel de administración para gestionar la cafetería**.  