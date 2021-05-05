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

# Crear y editar el archivo .env
cp .env.example .env

# Inicializar la base de datos
composer init-db

# Ejecutar servidor PHP interno
php -S localhost:8000 -t public

```

## Uso

Luego de instalar y poner en funcionamiento la API puede accederse usando un cliente REST (Ej: Postman).

### Endpoint: /mutant

Analiza si el ADN es mutante o no. En caso que sea mutante retorna **200 OK** y si no lo es **403 Forbidden**. Ej: 

```
POST localhost:8000/mutant
{
    "dna":["ATGCGA","CAGTGC","TTATGT","AGAAGG","CCCCTA","TCACTG"]
}
```

```
Response: 200 OK
```

### Endpoint: /stats

Retorna estadísticas de los ADN analizados. Cantidad de mutantes, cantidad de humanos y ratio mutantes/humanos. Ej: 

```
GET localhost:8000/stats
```

```
Response: 200 OK
{
    "count_mutant_dna": 40,
    "count_human_dna": 100,
    "ratio": 0.4
}
```

## Pruebas

Con la API instalada y en funcionamiento pueden ejecutarse los siguientes comandos

``` bash
# Limpiar base de datos
composer init-db

# Ejecutar pruebas
composer test

# Limpiar base de datos
composer init-db

# Comprobar el coverage
composer coverage
```

### Reporte Coverage
``` bash               
 Summary:                  
  Classes: 83.33% (5/6)    
  Methods: 96.15% (25/26)  
  Lines:   99.39% (163/164)
```


## License
[MIT License](https://github.com/leanec/mutant-detector/blob/main/LICENSE)