<?php
$vulnerability = isset($_GET['vuln']) ? $_GET['vuln'] : 'A1';

function demoBrokenAccessControl() {
    echo '<div class="demo-container">';
    echo "<h2>A1: Broken Access Control</h2>";
    echo "<p class='subtitle'>Simulación: Acceso indebido a recursos restringidos.</p>";

    // Simulación de formulario para probar control de acceso
    echo '<div class="demo">
            <h3>Prueba de Control de Acceso</h3>
            <p>Intenta acceder a una página restringida simulando un cambio en el nivel de acceso.</p>
            <form action="" method="POST" class="demo-form">
                <label for="role">Rol del Usuario:</label>
                <select id="role" name="role" required>
                    <option value="user">Usuario Regular</option>
                    <option value="admin">Administrador</option>
                </select>
                <button type="submit">Intentar Acceso</button>
            </form>';

    // Procesamiento de acceso
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $role = $_POST['role'];
        echo "<p>Intento de acceso con rol: <strong>{$role}</strong></p>";
        if ($role === 'admin') {
            echo '<div class="result success">✅ Acceso concedido: Bienvenido al panel de administrador.</div>';
        } else {
            echo '<div class="result error">❌ Acceso denegado: Solo los administradores pueden acceder a este recurso.</div>';
        }
    }

    echo "</div>";

    // Información adicional sobre Broken Access Control
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>Broken Access Control ocurre cuando un atacante puede acceder o modificar recursos que deberían estar protegidos, debido a controles de acceso mal configurados.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Implementar controles de acceso basados en roles (RBAC) y verificarlos en el servidor.</li>
                <li>Asegurar que los controles de acceso no dependan del cliente (front-end).</li>
                <li>Restringir el acceso a recursos sensibles utilizando verificaciones en el backend.</li>
                <li>Realizar pruebas regulares para detectar configuraciones incorrectas.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>El control de acceso roto puede permitir a un atacante:</p>
            <ul>
                <li>Acceder a datos sensibles de otros usuarios.</li>
                <li>Modificar configuraciones o recursos administrativos.</li>
                <li>Ejecutar acciones no autorizadas en el sistema.</li>
            </ul>
        </div>";
    echo "</div>";
}
function demoCryptographicFailures() {
    echo '<div class="demo-container">';
    echo "<h2>A2: Cryptographic Failures</h2>";
    echo "<p class='subtitle'>Simulación: Uso de un cifrado débil que compromete datos sensibles.</p>";

    // Simulación del formulario para cifrar y descifrar datos
    echo '<div class="demo">
            <h3>Prueba de Cifrado Débil</h3>
            <p>Introduce un mensaje para cifrarlo y simular la vulnerabilidad del cifrado débil.</p>
            <form action="" method="POST" class="demo-form">
                <label for="message">Mensaje:</label>
                <input type="text" id="message" name="message" placeholder="Escribe tu mensaje aquí" required>
                <button type="submit">Cifrar</button>
            </form>';

    // Procesamiento de cifrado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $message = $_POST['message'];
        $key = '12345'; // Clave débil utilizada para el cifrado
        $encrypted = base64_encode(openssl_encrypt($message, 'AES-128-ECB', $key, OPENSSL_RAW_DATA));
        $decrypted = openssl_decrypt(base64_decode($encrypted), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

        echo "<div class='result'>
                <h4>Resultados:</h4>
                <p><strong>Mensaje Original:</strong> $message</p>
                <p><strong>Mensaje Cifrado:</strong> $encrypted</p>
                <p><strong>Mensaje Descifrado:</strong> $decrypted</p>
              </div>";
        
        // Análisis de vulnerabilidad
        echo '<div class="result error">❌ Vulnerabilidad detectada: El cifrado utiliza una clave débil (12345). Esto facilita que un atacante pueda descifrar el mensaje mediante fuerza bruta.</div>';
    }

    echo "</div>";

    // Información adicional sobre Cryptographic Failures
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>Cryptographic Failures ocurren cuando los datos sensibles están protegidos con algoritmos de cifrado inseguros, claves débiles, o configuraciones incorrectas.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Utilizar algoritmos de cifrado robustos, como AES-256.</li>
                <li>Generar claves de cifrado seguras y almacenarlas adecuadamente.</li>
                <li>Implementar un manejo adecuado de certificados y claves privadas.</li>
                <li>Evitar claves estáticas o fácilmente adivinables.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>Si un atacante puede explotar un fallo criptográfico, podría:</p>
            <ul>
                <li>Descifrar datos sensibles.</li>
                <li>Suplantar identidades mediante certificados comprometidos.</li>
                <li>Acceder a recursos protegidos.</li>
            </ul>
        </div>";
    echo "</div>";
}


function demoInjection() {
    echo '<div class="demo-container">';
    echo "<h2>A3: Injection</h2>";
    echo "<p class='subtitle'>Simulación: Inyección SQL para comprometer la base de datos.</p>";

    // Formulario para la prueba de inyección
    echo '<div class="demo">
            <h3>Prueba de Inyección</h3>
            <form action="" method="POST" class="demo-form">
                <label for="id">ID del Usuario:</label>
                <input type="text" id="id" name="id" placeholder="Ej: 1 OR 1=1" required>
                <button type="submit">Consultar</button>
            </form>';

    // Procesamiento del formulario
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        
        // Simulación de la consulta SQL
        echo "<p>Consulta ejecutada: <code>SELECT * FROM usuarios WHERE id = '$id';</code></p>";

        // Verificación de inyecciones comunes
        $vulnerable = false;
        $mensaje = '';
        if (strpos(strtoupper($id), 'OR') !== false || strpos(strtoupper($id), 'UNION') !== false) {
            $vulnerable = true;
            $mensaje = "❌ ¡Vulnerabilidad detectada! Se devolvieron múltiples registros o se accedió a datos no autorizados.";
        } elseif (strpos(strtoupper($id), 'DROP') !== false) {
            $vulnerable = true;
            $mensaje = "❌ ¡Peligro extremo! Potencial eliminación de tablas detectada.";
        } elseif (strpos(strtoupper($id), "'") !== false) {
            $vulnerable = true;
            $mensaje = "❌ ¡Riesgo moderado! Inyección básica detectada.";
        }

        // Resultados de la prueba
        if ($vulnerable) {
            echo '<div class="result error">' . $mensaje . '</div>';
        } else {
            echo '<div class="result success">✅ Consulta ejecutada correctamente. No se detectaron vulnerabilidades.</div>';
        }
    }

    echo "</div>";

    // Información adicional
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>La inyección SQL ocurre cuando un atacante inserta código malicioso en una consulta SQL para acceder, modificar o eliminar datos.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Usar <strong>consultas parametrizadas</strong> o <strong>prepared statements</strong>.</li>
                <li>Validar y sanitizar todas las entradas del usuario.</li>
                <li>Restringir los permisos de la base de datos.</li>
                <li>Configurar el servidor para manejar errores de forma genérica y evitar exposición de información.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>Los ataques de inyección SQL pueden permitir a un atacante:</p>
            <ul>
                <li>Acceder a datos sensibles.</li>
                <li>Modificar o eliminar registros.</li>
                <li>Ejecutar comandos administrativos en la base de datos.</li>
                <li>Comprometer todo el sistema.</li>
            </ul>
        </div>";
    echo "</div>";
}

function demoInsecureDesign() {
    echo '<div class="demo-container">';
    echo "<h2>A4: Insecure Design</h2>";
    echo "<p class='subtitle'>Simulación: Fallo en la validación de roles, permitiendo acceso no autorizado.</p>";

    // Formulario para simular inicio de sesión
    echo '<div class="demo">
            <h3>Prueba de Diseño Inseguro</h3>
            <p>Simule un inicio de sesión para verificar la validación de roles.</p>
            <form action="" method="POST" class="demo-form">
                <label for="role">Rol del Usuario:</label>
                <select id="role" name="role">
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
                <button type="submit">Iniciar Sesión</button>
            </form>';

    // Procesamiento de roles
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $role = $_POST['role'];

        echo "<div class='result'>";
        if ($role == 'admin') {
            echo '<p class="success">✅ Acceso concedido: ¡Bienvenido, administrador!</p>';
        } else {
            echo '<p class="error">❌ Acceso denegado: Solo los administradores pueden acceder a esta sección.</p>';
        }

        // Simulación de bypass
        echo '<h4>Simulación de Bypass</h4>';
        echo "<p>Supongamos que un atacante manipula la petición para enviar el rol <code>admin</code>, incluso si no tiene permisos legítimos. Esto demuestra un fallo en la validación del lado del servidor.</p>";
        echo '<p class="error">❌ Vulnerabilidad detectada: El sistema confía en los datos del cliente sin validarlos correctamente en el servidor.</p>';
        echo "</div>";
    }

    echo "</div>";

    // Información adicional sobre Insecure Design
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>Insecure Design ocurre cuando el sistema no implementa controles adecuados para prevenir accesos indebidos o abusos.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Validar roles y permisos del lado del servidor, no confiar en los datos enviados por el cliente.</li>
                <li>Implementar el principio de privilegios mínimos.</li>
                <li>Realizar pruebas de diseño para identificar y corregir posibles abusos antes del despliegue.</li>
                <li>Utilizar frameworks seguros que ofrezcan controles de acceso predeterminados.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>Un diseño inseguro puede permitir a atacantes:</p>
            <ul>
                <li>Acceder a datos sensibles.</li>
                <li>Modificar configuraciones críticas.</li>
                <li>Escalar privilegios no autorizados.</li>
            </ul>
        </div>";
    echo "</div>";
}

function demoSecurityMisconfiguration() {
    echo '<div class="demo-container">';
    echo "<h2>A5: Security Misconfiguration</h2>";
    echo "<p class='subtitle'>Simulación: Mala configuración de seguridad que expone información sensible del sistema.</p>";

    // Simulación de acceso a una página mal configurada
    echo '<div class="demo">
            <h3>Prueba de Mala Configuración</h3>
            <p>Simule un acceso no autorizado a un archivo sensible en el servidor.</p>
            <form action="" method="POST" class="demo-form">
                <label for="file">Archivo solicitado:</label>
                <input type="text" id="file" name="file" placeholder="Ej: config.php" required>
                <button type="submit">Acceder</button>
            </form>';

    // Procesamiento de solicitud de archivo
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $file = $_POST['file'];

        echo "<div class='result'>";
        if ($file == 'config.php') {
            echo '<p class="error">❌ Vulnerabilidad detectada: Se accedió a un archivo sensible.</p>';
            echo '<p><strong>Contenido del archivo:</strong></p>';
            echo '<pre>
    DB_HOST=localhost
    DB_USER=root
    DB_PASS=password123
            </pre>';
        } else {
            echo '<p class="success">✅ Acceso denegado: Archivo protegido.</p>';
        }

        echo '<h4>Simulación de Configuración Débil</h4>';
        echo "<p>Un atacante podría acceder a archivos como <code>config.php</code> debido a una mala configuración del servidor que permite listar directorios o falta de restricciones de acceso.</p>";
        echo "</div>";
    }

    echo "</div>";

    // Información adicional sobre Security Misconfiguration
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>Security Misconfiguration ocurre cuando las configuraciones del servidor o de la aplicación son débiles, exponiendo información sensible o permitiendo accesos no autorizados.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Deshabilitar la lista de directorios en el servidor.</li>
                <li>Restringir el acceso a archivos sensibles usando reglas de configuración (por ejemplo, .htaccess).</li>
                <li>Eliminar credenciales o claves sensibles de los archivos de configuración.</li>
                <li>Implementar revisiones regulares de las configuraciones del servidor y de la aplicación.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>Un atacante podría:</p>
            <ul>
                <li>Acceder a configuraciones sensibles como credenciales de bases de datos.</li>
                <li>Modificar configuraciones críticas del sistema.</li>
                <li>Obtener información valiosa para llevar a cabo ataques más avanzados.</li>
            </ul>
        </div>";
    echo "</div>";
}
function demoVulnerableComponents() {
    echo '<div class="demo-container">';
    echo "<h2>A6: Vulnerable and Outdated Components</h2>";
    echo "<p class='subtitle'>Simulación: Uso de componentes obsoletos que pueden ser vulnerables a ataques conocidos.</p>";

    // Simulación de actualización de un componente vulnerable
    echo '<div class="demo">
            <h3>Prueba de Componentes Vulnerables</h3>
            <p>Simulemos una aplicación que usa una biblioteca vulnerable conocida.</p>';
    
    // Se muestra el componente vulnerable en uso
    echo '<p>Componente en uso: <code>jquery-1.12.4.js</code></p>';
    echo '<p><strong>Vulnerabilidad conocida:</strong> La versión 1.12.4 de jQuery tiene vulnerabilidades conocidas (CVE-2016-10707) que pueden permitir la ejecución remota de código.</p>';

    echo '<p><strong>Simulación:</strong> Si un atacante explota esta vulnerabilidad, podría ejecutar código malicioso en la aplicación.</p>';
    echo '<div class="result error">❌ Vulnerabilidad detectada: Componente obsoleto detectado en el sistema.</div>';

    echo '<h4>Simulación de Ataque</h4>';
    echo "<p>Un atacante podría intentar explotar una vulnerabilidad conocida en <code>jquery-1.12.4.js</code> para inyectar código malicioso o comprometer la aplicación.</p>";

    // Mostrar actualización de componente
    echo '<p><strong>Solución:</strong> Actualizar el componente a la versión más reciente de jQuery para eliminar las vulnerabilidades.</p>';
    echo '</div>';

    // Información adicional sobre componentes vulnerables
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>El uso de componentes obsoletos y vulnerables puede permitir que los atacantes exploten vulnerabilidades conocidas para comprometer la aplicación.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Realizar auditorías regulares de los componentes y bibliotecas utilizados en la aplicación.</li>
                <li>Utilizar herramientas de gestión de dependencias para detectar componentes vulnerables.</li>
                <li>Actualizar siempre las bibliotecas y componentes a sus versiones más recientes que contengan parches de seguridad.</li>
                <li>Eliminar cualquier componente o biblioteca que no se utilice o que esté obsoleto.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>El uso de componentes vulnerables puede permitir:</p>
            <ul>
                <li>La ejecución de código malicioso en el servidor o cliente.</li>
                <li>El acceso no autorizado a los datos almacenados.</li>
                <li>El compromiso de la integridad del sistema y la aplicación.</li>
            </ul>
        </div>";
    echo "</div>";
}

function demoAuthenticationFailures() {
    echo '<div class="demo-container">';
    echo "<h2>A7: Identification and Authentication Failures</h2>";
    echo "<p class='subtitle'>Simulación: Fallas en el proceso de identificación y autenticación que permiten acceso no autorizado.</p>";

    // Simulación de un intento de login sin seguridad adecuada
    echo '<div class="demo">
            <h3>Prueba de Fallas de Autenticación</h3>
            <form action="" method="POST" class="demo-form">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" placeholder="Ej: admin" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Ej: 12345" required>
                <button type="submit">Iniciar sesión</button>
            </form>';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Simulación de un acceso incorrecto
        if ($username == "admin" && $password == "12345") {
            echo '<div class="result success">✅ Acceso concedido. Sesión iniciada correctamente.</div>';
        } else {
            echo '<div class="result error">❌ Fallo de autenticación. Usuario o contraseña incorrectos.</div>';
        }
    }

    echo '</div>';

    // Información adicional sobre la autenticación
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>Las fallas de identificación y autenticación ocurren cuando un atacante puede evadir el proceso de autenticación para obtener acceso no autorizado a una aplicación o sistema.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Implementar autenticación multifactor (MFA).</li>
                <li>Asegurarse de que las contraseñas sean fuertes y se almacenen de manera segura (por ejemplo, usando hashing).</li>
                <li>Utilizar mecanismos de bloqueo de cuenta después de varios intentos fallidos.</li>
                <li>Asegurarse de que los formularios de autenticación estén protegidos contra ataques de fuerza bruta.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>Las fallas de autenticación pueden permitir que los atacantes:</p>
            <ul>
                <li>Accedan a cuentas y datos sensibles.</li>
                <li>Realicen operaciones no autorizadas, como cambiar contraseñas o eliminar datos.</li>
                <li>Obtengan acceso privilegiado si el control de acceso no está correctamente implementado.</li>
            </ul>
        </div>";
    echo "</div>";
}

function demoInsecureDeserialization() {
    echo '<div class="demo-container">';
    echo "<h2>A8: Insecure Deserialization</h2>";
    echo "<p class='subtitle'>Simulación: Deserialización insegura en una aplicación vulnerable.</p>";

    echo '<div class="demo">
            <h3>Simulación de Deserialización Insegura</h3>
            <form action="" method="POST" class="demo-form">
                <label for="data">Datos serializados:</label>
                <input type="text" id="data" name="data" placeholder="Ej: a:1:{s:3:\"foo\";s:3:\"bar\";}" required>
                <button type="submit">Deserializar</button>
            </form>';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = $_POST['data'];

        echo "<p>Datos recibidos para deserializar: <code>$data</code></p>";

        // Simulación de deserialización
        $deserialized = @unserialize($data);

        if ($deserialized === false) {
            echo '<div class="result error">❌ Error en la deserialización: datos maliciosos detectados.</div>';
        } else {
            echo '<div class="result success">✅ Datos deserializados correctamente.</div>';
        }
    }

    echo '</div>';

    // Información adicional sobre deserialización insegura
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>La deserialización insegura ocurre cuando los datos provenientes de fuentes no confiables se deserializan sin medidas de seguridad adecuadas, lo que puede permitir la ejecución de código malicioso.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Evitar deserializar datos provenientes de fuentes no confiables.</li>
                <li>Usar técnicas como firmas digitales para verificar la integridad de los datos antes de deserializarlos.</li>
                <li>Validar los datos y realizar controles de acceso adecuados antes de procesarlos.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>Un atacante podría ejecutar código malicioso al enviar datos manipulados, lo que podría resultar en la ejecución remota de código o en la manipulación de la aplicación.</p>
        </div>";
    echo "</div>";
}

function demoKnownVulnerabilities() {
    echo '<div class="demo-container">';
    echo "<h2>A9: Using Components with Known Vulnerabilities</h2>";
    echo "<p class='subtitle'>Simulación: Demostración de cómo el uso de componentes vulnerables puede comprometer la seguridad de una aplicación.</p>";

    // Simulación de la vulnerabilidad
    echo '<div class="demo">
            <h3>Prueba de Componentes Vulnerables</h3>
            <form action="" method="POST" class="demo-form">
                <label for="component">Nombre del Componente:</label>
                <input type="text" id="component" name="component" placeholder="Ej: outdated-lib" required>
                <button type="submit">Verificar Vulnerabilidad</button>
            </form>';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $component = $_POST['component'];
        echo "<p>Componente recibido: <code>$component</code></p>";

        // Simulación de la verificación de la vulnerabilidad del componente
        $vulnerableComponents = ["outdated-lib", "old-framework", "vulnerable-plugin"];
        
        if (in_array($component, $vulnerableComponents)) {
            echo '<div class="result error">❌ Componente vulnerable detectado: ' . $component . '</div>';
        } else {
            echo '<div class="result success">✅ Componente seguro: ' . $component . '</div>';
        }
    }

    echo '</div>';

    // Información adicional sobre componentes vulnerables
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>El uso de componentes desactualizados o vulnerables pone en riesgo la seguridad de una aplicación, ya que pueden ser explotados por atacantes para obtener acceso no autorizado, ejecutar código malicioso o comprometer el sistema.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Utilizar herramientas para verificar las vulnerabilidades conocidas de los componentes (Ej: OWASP Dependency-Check, Snyk, etc.).</li>
                <li>Actualizar los componentes regularmente y aplicar parches de seguridad.</li>
                <li>Evitar el uso de librerías o componentes no mantenidos o con vulnerabilidades conocidas.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>El uso de componentes vulnerables puede resultar en:</p>
            <ul>
                <li>Explotación remota de vulnerabilidades.</li>
                <li>Acceso no autorizado a los sistemas o datos de los usuarios.</li>
                <li>Riesgo de ejecución de código malicioso, que podría comprometer la infraestructura completa.</li>
            </ul>
        </div>";
    echo "</div>";
}

function demoLoggingMonitoring() {
    echo '<div class="demo-container">';
    echo "<h2>A10: Insufficient Logging & Monitoring</h2>";
    echo "<p class='subtitle'>Simulación: Demostramos cómo la falta de monitoreo y registros puede permitir un ataque sin ser detectado.</p>";

    // Simulación de monitoreo y registro insuficiente
    echo '<div class="demo">
            <h3>Simulación de Monitoreo de Eventos</h3>
            <form action="" method="POST" class="demo-form">
                <label for="event">Descripción del Evento:</label>
                <input type="text" id="event" name="event" placeholder="Ej: Acceso no autorizado" required>
                <button type="submit">Registrar Evento</button>
            </form>';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $event = $_POST['event'];

        echo "<p>Evento recibido: <code>$event</code></p>";

        // Simulación de registro adecuado en un sistema con monitoreo adecuado
        if (strpos(strtolower($event), "acceso no autorizado") !== false) {
            // Simulamos un registro adecuado del evento de acceso no autorizado
            $logFile = "logs.txt";
            $logMessage = date("Y-m-d H:i:s") . " - [ALERTA] Intento de acceso no autorizado detectado: " . $event . PHP_EOL;

            // En un sistema adecuado, deberíamos alertar al administrador inmediatamente
            if (file_put_contents($logFile, $logMessage, FILE_APPEND)) {
                echo '<div class="result success">✅ Evento crítico registrado correctamente y alerta enviada.</div>';
                // Simulamos que se envía una alerta automática
                echo '<div class="alerta">⚠️ ¡ALERTA! Acceso no autorizado detectado. El administrador ha sido notificado.</div>';
            } else {
                echo '<div class="result error">❌ Error al registrar el evento crítico.</div>';
            }
        } else {
            // En un sistema con monitoreo insuficiente, no se realiza el registro
            echo '<div class="result info">🔔 Este evento no es crítico, por lo tanto no se requiere alerta ni registro.</div>';
        }
    }

    echo '</div>';

    // Información adicional sobre Insufficient Logging & Monitoring
    echo "<div class='info'>
            <h3>Descripción</h3>
            <p>Un sistema con monitoreo insuficiente no detecta rápidamente los intentos de ataque o actividades maliciosas. Los eventos de seguridad no se registran correctamente o no se monitorean adecuadamente.</p>
            <h3>Mitigación</h3>
            <ul>
                <li>Registrar todos los eventos de seguridad relevantes y configurar alertas automáticas para detectar accesos no autorizados.</li>
                <li>Monitorear de manera continua las aplicaciones, bases de datos y redes en busca de anomalías y patrones sospechosos.</li>
                <li>Asegurarse de que los registros sean inmutables y que no puedan ser modificados por atacantes.</li>
            </ul>
            <h3>Riesgo</h3>
            <p>Sin registros y monitoreo adecuado, los atacantes pueden actuar sin ser detectados, comprometiendo la seguridad del sistema durante un largo período de tiempo.</p>
        </div>";
    echo "</div>";
}



echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demostración OWASP Top 10 2021</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Demostraciones OWASP Top 10</h1>
        <p>Simula vulnerabilidades y aprende a mitigarlas.</p>
    </header>
    <main>';

switch ($vulnerability) {
    case 'A1':
        demoBrokenAccessControl();
        break;
    case 'A2':
        demoCryptographicFailures();
        break;
    case 'A3':
        demoInjection();
        break;
    case 'A4':
        demoInsecureDesign();
        break;
    case 'A5':
        demoSecurityMisconfiguration();
        break;
    case 'A6':
        demoVulnerableComponents();
        break;
    case 'A7':
        demoAuthenticationFailures();
        break;
    case 'A8':
        demoInsecureDeserialization();
        break;
    case 'A9':
        demoKnownVulnerabilities();
        break;
    case 'A10':
        demoLoggingMonitoring();
        break;
    default:
        echo "<p>Vulnerabilidad no encontrada.</p>";
        break;
}

echo '<a href="index.php" class="back-button">Volver al Menú</a>
    </main>
</body>
</html>';
?>
