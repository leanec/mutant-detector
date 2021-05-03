# Mutant Detector

Detector de mutantes según su cadena de ADN.

## Introducción
El objetivo es detectar si un humano es mutante o no analizando su cadena de ADN, **más de una secuencia de cuatro letras iguales ya sea de forma oblicua, horizontal o vertical** indica que es mutante.
Como entrada se recibirá un **array de Strings** que representan cada fila de una tabla **(NxN)** con la secuencia del ADN. Las letras de los Strings sólo pueden ser **(A,T,C,G)**.

## Requerimientos

- Composer
- PHP 7.4+
- MySQL 5.7+

## Instalación Local

``` bash
# Clonar el repositorio
git clone https://github.com/leanec/mutant-detector.git

# Acceder al directorio
cd mutant-detector

# Instalar dependencias
composer install

# Crear la base de datos
mysql -u root -p < database/database.sql

# Crear y editar el archivo .env
cp .env.example .env

# Ejecutar servidor PHP interno
php -S localhost:8000

```

## Uso

Luego de instalar y poner en funcionamiento la API puede accederse usando un cliente REST (Ej: Postman).

### Endpoint: /mutant

Analiza si el ADN es mutante o no. En caso que sea mutante retorna **200 OK** y si no lo es **403 Forbidden**. Ej: 

```
POST localhost:8080/mutant
{
    "dna":["ATGCGA","CAGTGC","TTATGT","AGAAGG","CCCCTA","TCACTG"]
}
```

```
Response: 200 OK
```

## License
[MIT License](https://github.com/leanec/mutant-detector/blob/main/LICENSE)