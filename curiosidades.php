<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit;
}
$sesionIniciada = true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repazo | Curiosidades</title>
    <link href="CSS/codigo.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img alt="logo de Repazo" decoding="async" loading="lazy" src="IMAGENES/LOGOREPAZO.jpg">
            <h2>Repazo</h2>
        </div>
        <nav>
            <ul>
                <?php if (!$sesionIniciada): ?>
                    <!-- Menú público (Sin sesión) -->
                    <li><a href="codigo.php">Inicio</a></li>
                    <li><a href="quienes-somos.php">Quiénes somos</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a class="registro" href="registro.php">Registrarse</a></li>
                <?php else: ?>
                    <!-- Menú privado (Con sesión activa) -->
                    <li><a href="panel.php">Módulos</a></li>
                    <li><a href="quizes.php">Quizzes</a></li>
                    <li><a class="active" href="curiosidades.php">Datos Curiosos</a></li>
                    <li><a href="perfil.php">Perfil</a></li>
                    <li><a href="php/cerrar_sesion.php" style="color: #ffcccc;">Cerrar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="heroMQC">
        <div class="heroMQC-texto">
            <h1>Curiosidades Educativas</h1>
            <p>Descubre datos sorprendentes sobre Matemáticas, Física, Química, Español e Inglés.</p>
        </div>
    </section>

    <main class="maincuriosidades">
        <h2 class="tituloC">Curiosidades por Materia</h2>

        <!-- MATEMÁTICAS -->
        <section class="materiaC">
            <h2><i class="fa-solid fa-square-root-variable"></i> Matemáticas</h2>
            <div class="cardsC">
                <div class="cardC">
                    <h3>El número π</h3>
                    <p>El número π tiene infinitos decimales y nunca se repite con un patrón.</p>
                </div>
                <div class="cardC">
                    <h3>El número 0</h3>
                    <p>El cero fue inventado hace más de 1.500 años y revolucionó las matemáticas.</p>
                </div>
                <div class="cardC">
                    <h3>Sucesión de Fibonacci</h3>
                    <p>Aparece en flores, caracoles, piñas y hasta en algunas galaxias.</p>
                </div>
                <div class="cardC">
                    <h3>Números primos</h3>
                    <p>Solo pueden dividirse entre 1 y ellos mismos.</p>
                </div>
                <div class="cardC">
                    <h3>El infinito</h3>
                    <p>El símbolo ∞ fue introducido por John Wallis en el siglo XVII.</p>
                </div>
                <div class="cardC">
                    <h3>Infinitos</h3>
                    <p>Hay infinitos mas grandes que otros.</p>
                </div>
                <div class="cardC">
                    <h3>La paradoja del cumpleaños</h3>
                    <p>Con 23 personas hay un 50% de probabilidad de que dos cumplan el mismo día.</p>
                </div>
                <div class="cardC">
                    <h3>El 0.999...</h3>
                    <p>El número 0.999... infinito es exactamente igual a 1, no casi 1.</p>
                </div>
            </div>
        </section>

        <!-- FÍSICA -->
        <section class="materiaC">
            <h2><i class="fa-solid fa-rocket"></i> Física</h2>
            <div class="cardsC">
                <div class="cardC">
                    <h3>Velocidad de la luz</h3>
                    <p>La luz viaja aproximadamente a 299.792.458 metros por segundo.</p>
                </div>
                <div class="cardC">
                    <h3>Gravedad</h3>
                    <p>En la Luna pesamos aproximadamente seis veces menos que en la Tierra.</p>
                </div>
                <div class="cardC">
                    <h3>Agujeros negros</h3>
                    <p>Su gravedad es tan fuerte que ni la luz puede escapar de ellos.</p>
                </div>
                <div class="cardC">
                    <h3>El sonido</h3>
                    <p>En el espacio no hay sonido porque no existe un medio para propagarse.</p>
                </div>
                <div class="cardC">
                    <h3>Rayos</h3>
                    <p>Un rayo puede alcanzar temperaturas cinco veces mayores que la superficie del Sol.</p>
                </div>
                <div class="cardC">
                    <h3>El tiempo es relativo</h3>
                    <p>El tiempo pasa más lento si vas muy rápido. Un astronauta que viaja rápido, envejece menos.</p>
                </div>
                <div class="cardC">
                    <h3>El rayo es mas caliente</h3>
                    <p>Un rayo puede ser 5 veces más caliente que la superficie del sol.</p>
                </div>
                <div class="cardC">
                    <h3>La luz pesa</h3>
                    <p>La luz no tiene masa pero la gravedad la puede curvar. Lo demostró Einstein.</p>
                </div>
            </div>
        </section>

        <!-- QUÍMICA -->
        <section class="materiaC">
            <h2><i class="fa-solid fa-flask"></i> Química</h2>
            <div class="cardsC">
                <div class="cardC">
                    <h3>El agua</h3>
                    <p>El agua puede existir de forma natural como sólido, líquido y gas.</p>
                </div>
                <div class="cardC">
                    <h3>El oro</h3>
                    <p>Es uno de los pocos metales que no se oxida fácilmente.</p>
                </div>
                <div class="cardC">
                    <h3>Diamante</h3>
                    <p>Está formado únicamente por átomos de carbono.</p>
                </div>
                <div class="cardC">
                    <h3>Helio</h3>
                    <p>Es tan ligero que hace flotar los globos.</p>
                </div>
                <div class="cardC">
                    <h3>Tabla periódica</h3>
                    <p>Actualmente contiene más de 118 elementos químicos.</p>
                </div>
                <div class="cardC">
                    <h3>El galio</h3>
                    <p>Este metal se derrite a 29.7 °C, por lo que puede fundirse en la palma de tu mano.</p>
                </div>
                <div class="cardC">
                    <h3>El oxígeno líquido</h3>
                    <p>El oxígeno es incoloro como gas, pero en estado líquido y sólido es de color azul intenso y magnético.</p>
                </div>
                <div class="cardC">
                    <h3>El olor a lluvia</h3>
                    <p>El aroma característico a tierra mojada lo produce una bacteria y se llama petricor.</p>
                </div>
            </div>
        </section>

        <!-- ESPAÑOL -->
        <section class="materiaC">
            <h2><i class="fa-solid fa-book-open"></i> Español</h2>
            <div class="cardsC">
                <div class="cardC">
                    <h3>El idioma español</h3>
                    <p>Más de 500 millones de personas hablan español como lengua materna.</p>
                </div>
                <div class="cardC">
                    <h3>Palíndromos</h3>
                    <p>Son palabras o frases que se leen igual de izquierda a derecha y de derecha a izquierda, como "reconocer".</p>
                </div>
                <div class="cardC">
                    <h3>La palabra más larga</h3>
                    <p>"Electroencefalografista" es una de las palabras más largas del español.</p>
                </div>
                <div class="cardC">
                    <h3>¿De dónde viene el alfabeto?</h3>
                    <p>El alfabeto español proviene del alfabeto latino.</p>
                </div>
                <div class="cardC">
                    <h3>La letra Ñ</h3>
                    <p>La letra Ñ es una característica única del idioma español y no existe en la mayoría de los alfabetos.</p>
                </div>
                <div class="cardC">
                    <h3>Palabras con las cinco vocales</h3>
                    <p>Palabras como "murciélago" y "euforia" contienen todas las vocales sin repetir ninguna.</p>
                </div>
                <div class="cardC">
                    <h3>Los signos de apertura</h3>
                    <p>El español es el único idioma mayoritario que utiliza los signos ¿ y ¡ al inicio de las oraciones.</p>
                </div>
                <div class="cardC">
                    <h3>Voces americanas</h3>
                    <p>Palabras como "tomate", "chocolate" y "aguacate" pasaron al mundo entero desde el náhuatl a través del español.</p>
                </div>
            </div>
        </section>

        <!-- INGLÉS -->
        <section class="materiaC">
            <h2><i class="fa-solid fa-language"></i> Inglés</h2>
            <div class="cardsC">
                <div class="cardC">
                    <h3>Idioma internacional</h3>
                    <p>El inglés es el idioma más utilizado para la comunicación internacional.</p>
                </div>
                <div class="cardC">
                    <h3>Palabra más usada</h3>
                    <p>"The" es una de las palabras más utilizadas en inglés.</p>
                </div>
                <div class="cardC">
                    <h3>Idioms</h3>
                    <p>Expresiones como "Break a leg" no deben traducirse literalmente.</p>
                </div>
                <div class="cardC">
                    <h3>Británico y Americano</h3>
                    <p>Algunas palabras cambian según el país, por ejemplo: "Lift" (UK) y "Elevator" (USA).</p>
                </div>
                <div class="cardC">
                    <h3>False Friends</h3>
                    <p>"Actually" significa "En realidad", no "Actualmente".</p>
                </div>
                <div class="cardC">
                    <h3>Sin rima</h3>
                    <p>Palabras como orange, silver, purple y month no tienen ninguna otra palabra con la que rimen perfectamente.</p>
                </div>
                <div class="cardC">
                    <h3>El símbolo &</h3>
                    <p>El ampersand (&) solía ser la 27.ª letra del alfabeto inglés hasta mediados del siglo XIX.</p>
                </div>
                <div class="cardC">
                    <h3>La palabra "Set"</h3>
                    <p>Es la palabra con más definiciones distintas en el diccionario de inglés, superando las 400 acepciones.</p>
                </div>
            </div>
        </section>

        <section class="botonesC">
            <a class="btnC" href="panel.php"><i class="fa-solid fa-arrow-left"></i> Volver a Módulos</a>
            <a class="btnC" href="quizes.php"><i class="fa-solid fa-circle-question"></i> Quizzes</a>
        </section>
    </main>

    <footer>
        <section class="footer1">
            <h2>Repazo</h2>
            <p>Aprender nunca fue tan divertido.</p>
        </section>
        <section class="footer3">
            <h3>Síguenos</h3>
            <div class="redes-sociales">
                <a href="https://www.facebook.com/profile.php?id=61594301955666" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.instagram.com/_repazo?stkn=MWRlamV2MnNlNm1vcw==" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.tiktok.com/@repazo80?_r=1&_t=ZS-99uzZN8ve1I" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
            </div>
        </section>
    </footer>
</body>
</html>