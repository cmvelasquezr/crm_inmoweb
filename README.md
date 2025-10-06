# 🧭 CRM Inmobiliario – Caso de Uso Simulado para Presentar a Inmoweb MVC + Strategy (PHP)

Este proyecto es una **simulación simplificada de un caso de Uso para CRM inmobiliario**, centrada en la **gestión de afinidad entre inmuebles y clientes**.  
Permite calcular y visualizar el grado de coincidencia entre un inmueble y distintos clientes potenciales, mostrando un desglose detallado por criterio (zona, precio, habitaciones, garaje, terraza, etc.).

---

## 🎯 **Objetivos del proyecto**

- Implementar una arquitectura limpia con **patrón MVC (Modelo–Vista–Controlador)**.
- Aplicar el **patrón Strategy** para permitir múltiples estrategias de cálculo de afinidad.
- Crear una **interfaz clara y moderna** en HTML/CSS puro, sin frameworks.
- Facilitar la extensibilidad (por ejemplo, añadir ponderaciones, criterios nuevos, etc.).

---

## 🧩 **Arquitectura**

## 🧠 **Patrones de diseño utilizados**

### 1️⃣ **MVC (Modelo–Vista–Controlador)**

- **Modelo (`/src/Models/`)**  
  Contiene la lógica de negocio y las entidades principales (`Property`, `Client`, `AffinityCalculator`).
  
- **Vista (`/views/`)**  
  Responsable de la presentación HTML de los datos calculados.
  
- **Controlador (`/src/Controllers/PropertyController.php`)**  
  Recoge la solicitud, coordina modelos y vista, y devuelve la respuesta al usuario.

> 🔹 Este patrón favorece la separación de responsabilidades, facilitando el mantenimiento y la escalabilidad del proyecto.

---

### 2️⃣ **Strategy**

El cálculo de afinidad se encapsula en una interfaz común:

```php
interface AffinityStrategy {
    public function compute_affinity(Property $p, Client $c): array;
}

Esto permite:

Cambiar la lógica de cálculo sin modificar el resto del sistema.

Implementar múltiples estrategias (por ejemplo, BasicAffinityStrategy, WeightedAffinityStrategy, AdvancedAIPropertyMatch, etc.).

Favorecer la extensibilidad y la prueba unitaria.

⚙️ Requisitos

PHP ≥ 8.1 (recomendado: PHP 8.2 Non Thread Safe)

Base de datos MySQL (recomendado: MySQL Ver 8.0.43-0ubuntu0.22.04.2 for Linux on x86_64 ((Ubuntu)))

Navegador web moderno

🚀 Ejecución servidor Ubuntu

URL: http://135.236.152.41/index.php
    - Inmueble ejemplo 1: http://135.236.152.41/index.php?id=1
    - Inmueble ejemplo 2: http://135.236.152.41/index.php?id=2

🚀 Ejecución local

Clonar el repositorio:

git clone https://github.com/tu-usuario/crm-inmobiliario.git
cd crm-inmobiliario


Iniciar el servidor embebido de PHP:

php -S localhost:8000


Flujo

Abrir en el servidor público:

| Página              | Función                         | URL                                                                                |
| ------------------- | ------------------------------- | ---------------------------------------------------------------------------------- |
| `index.php`         | Muestra afinidad del inmueble 1 | [http://135.236.152.41/index.php]
| `property.php?id=1` | Muestra afinidad del inmueble 1 | [http://135.236.152.41/index.php?id=1]
| `property.php?id=2` | Muestra afinidad del inmueble 2 | [http://135.236.152.41/index.php?id=2]


Abrir en el navegador local:

| Página              | Función                         | URL                                                                                |
| ------------------- | ------------------------------- | ---------------------------------------------------------------------------------- |
| `index.php`         | Muestra afinidad del inmueble 1 | [http://localhost:8000](http://localhost:8000)                                     |
| `property.php?id=1` | Muestra afinidad del inmueble 1 | [http://localhost:8000?id=1](http://localhost:8000/?id=1) |
| `property.php?id=2` | Muestra afinidad del inmueble 2 | [http://localhost:8000/property.php?id=2](http://localhost:8000/?id=2) |


🎨 Características visuales

Diseño responsive y minimalista.

Barras de afinidad dinámicas.

Desglose por criterio con colores (verde ✅ / rojo ❌).

Detalles desplegables mediante JavaScript puro.

Ejemplo de visualización:

Piso luminoso con terraza en Chamberí
Clientes potenciales (ordenados por afinidad)

Ana Pérez — 100 %
✔ Zona preferida
✔ Dentro del presupuesto
✔ Cumple requisito de garaje
...

🧩 Extensiones futuras