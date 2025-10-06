
# 🏡 Caso de Uso: Sugerencias Automáticas para el Agente

## 1. Información general

**Nombre del caso de uso:**  
Sugerencias automáticas para el agente inmobiliario.

**Versión:**  
1.0

**Autor:**  
Claudia Maritza Velásquez R.

**Fecha:**  
Octubre 2025

**Tipo de mejora:**  
Propuesta funcional — nueva funcionalidad basada en inteligencia contextual.

---

## 2. Descripción general

El objetivo de este caso de uso es optimizar la gestión comercial del agente inmobiliario, proporcionándole recomendaciones automáticas de clientes potenciales interesados en un inmueble determinado.  
El sistema calcula un índice de afinidad entre las características del inmueble y las preferencias registradas de los clientes en el CRM.

Esto permite que el agente identifique rápidamente a qué clientes podría ofrecer un inmueble concreto, reduciendo el tiempo de búsqueda manual y aumentando la efectividad de cierre.

---

## 3. Actores involucrados

| Actor | Rol |
|-------|------|
| **Agente inmobiliario** | Usuario principal. Consulta la ficha de un inmueble y recibe sugerencias de clientes potenciales. |
| **Sistema CRM Inmoweb** | Procesa los datos de inmuebles y clientes, calcula el índice de afinidad y muestra las sugerencias. |
| **Administrador** *(opcional)* | Define los parámetros del algoritmo de afinidad (peso de ubicación, presupuesto, número de habitaciones, etc.). |

---

## 4. Flujo principal del caso de uso

### Escenario: Desde la ficha de inmueble

1. El **agente** accede a la ficha de un inmueble dentro del CRM.  
2. El **sistema** identifica automáticamente el inmueble activo.  
3. El **módulo de afinidad** busca en la base de datos los clientes registrados.  
4. Se calcula un **índice de afinidad (0–100%)** para cada cliente según:
   - Coincidencia de zona o barrio.
   - Ajuste del rango de precio.
   - Coincidencia de número de habitaciones.
   - Preferencias adicionales (terraza, garaje, ascensor, etc.).  
5. El sistema ordena los resultados de mayor a menor afinidad.  
6. El **agente visualiza** una lista con los clientes sugeridos, mostrando:
   - Nombre del cliente.  
   - Porcentaje de afinidad.  
   - Motivos de coincidencia (“Busca en Chamberí”, “Presupuesto dentro del rango”, etc.).  
7. El agente puede:
   - Abrir la ficha del cliente.  
   - Enviar un correo directo desde el CRM.  
   - Marcarlo como “contactado”.

---

## 5. Flujos alternativos

| Alternativa | Descripción |
|--------------|--------------|
| **A1:** No existen coincidencias | El sistema muestra el mensaje “No hay clientes compatibles actualmente para este inmueble”. |
| **A2:** Filtros personalizados | El agente puede ajustar manualmente los parámetros de búsqueda (por ejemplo, ampliar el rango de precios o añadir ubicación cercana). |

---

## 6. Reglas de negocio

1. Solo se mostrarán clientes con estado “Activo” en el CRM.  
2. El cálculo de afinidad debe realizarse en menos de 1 segundo por inmueble.  
3. Los resultados se actualizan automáticamente si cambian las características del inmueble o las preferencias del cliente.  
4. El sistema debe registrar las interacciones del agente (qué sugerencias revisó o descartó).

---

## 7. Consideraciones técnicas

- **Patrón de diseño empleado:**  
  - **MVC (Model-View-Controller):** para separar la lógica de negocio, la presentación y el control de flujo.  
  - **Strategy:** para encapsular el algoritmo de cálculo de afinidad, permitiendo cambiar la lógica sin alterar el resto del sistema.

- **Algoritmo de afinidad (simplificado):**
  ```php
  $score = 0;
  if ($client->matchesZone($property->zone)) $score += 40;
  if ($client->priceInRange($property->price)) $score += 30;
  if ($client->matchesRooms($property->rooms)) $score += 20;
  if ($client->matchesExtras($property)) $score += 10;
  ```
  Resultado → **Afinidad total = $score / 100 × 100%**

- **Tecnologías:**  
  - PHP 8.2 (NTS)  
  - HTML5 + CSS3  
  - XAMPP / Apache  
  - MySQL (o simulación en memoria)

---

## 8. Beneficios esperados

| Beneficio | Descripción |
|------------|--------------|
| 🎯 **Eficiencia comercial** | Reduce el tiempo del agente en la búsqueda manual de clientes. |
| 🤝 **Mayor tasa de conversión** | Las coincidencias de afinidad aumentan la probabilidad de cierre. |
| 💡 **Automatización inteligente** | Usa datos existentes sin requerir intervención manual. |
| 📊 **Visibilidad estratégica** | Facilita reportes sobre qué tipo de inmuebles tienen más demanda. |

---

## 9. Posibles mejoras futuras

1. Integrar un motor de aprendizaje automático (Machine Learning) que aprenda de los cierres anteriores.  
2. Permitir que el agente ajuste el peso de los factores de afinidad.  
3. Mostrar sugerencias inversas: “clientes con afinidad alta que aún no tienen inmuebles disponibles”.  
4. Integración con notificaciones automáticas (correo o WhatsApp).
