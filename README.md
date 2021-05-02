# Mutant Detector

Detector de mutantes según su cadena de ADN.

## Introducción
El objetivo es detectar si un humano es mutante o no analizando su cadena de ADN, **más de una secuencia de cuatro letras iguales ya sea de forma oblicua, horizontal o vertical** indica que es mutante.
Como entrada se recibirá un **array de Strings** que representan cada fila de una tabla **(NxN)** con la secuencia del ADN. Las letras de los Strings sólo pueden ser **(A,T,C,G)**.

## Requerimientos

Composer
PHP 7.4+

## Instalación Local

``` bash
# Clonar el repositorio
git clone https://github.com/leanec/mutant-detector.git

# Acceder al directorio
cd mutant-detector

# Instalar dependencias
composer install

# Ejecutar servidor PHP interno
php -S localhost:8000

```

## License
[MIT License](https://github.com/leanec/mutant-detector/blob/main/LICENSE)