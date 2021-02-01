Descripción:
------------

Genius Referrals en un intento de mejorar el proceso de integración con sus servicios ha creado esta biblioteca (cliente). 
La cual permite a sus clientes, a través de PHP, consumir los recursos de su RESTful API localizada en http://api.geniusreferrals.com/doc/. 

Instalación:
------------

El proceso de instalación de este cliente en muy sencillo y puede hacerse de varias formas.

### Usando Composer

Recomendamos composer para intallar este cliente.

#### 1- Installar Composer

```cd``` en el directorio de su projecto (ej: my_project) y ejecute:

```
curl -sS https://getcomposer.org/installer | php
```

#### 2- Adicionar el packete GRAPIPHPClient como una dependencia ejecutando: 

```
php composer.phar require geniusreferrals/gr-api-php-client:dev-master
```

#### 3- Requerir el cargador automático de Composer

```
require_once '../vendor/autoload.php';
```

### Usando Git

#### 1- Clonar el repositorio 

Si usted no quiere usar composer, puede instalar el paquete clonando el repositorio. 
```cd``` en la carpeta donde quiere clonar el paquete y ejecute: 

```
git clone git@github.com:GeniusReferrals/GRAPIPHPClient.git
```

#### 2- Requerir el clase del cliente en la clase en la que desea usar el cliente. 

``` 
require_once 'src/GeniusReferrals/GRPHPAPIClient.php';
```` 

### Descargando el cliente GRAPIPHPClient

#### 1- Descargar el paquete manualmente

Descargue el zip del cliente usando este vínculo [GRAPIPHPClient](https://github.com/GeniusReferrals/GRAPIPHPClient/archive/master.zip), 
unzip el paquete y guardelo dentro del directorio de su projecto.

#### 2- Requerir el la clase del cliente en la clase donde desea usar el cliente. 

``` 
require_once 'src/GeniusReferrals/GRPHPAPIClient.php';
```` 

Usando el Cliente
----------------

```
<?php

require_once '../vendor/autoload.php';

use GeniusReferrals\GRPHPAPIClient;

// Create a new GRPHPAPIClient object
$objGeniusReferralsAPIClient = new GRPHPAPIClient('YOUR_USERNAME', 'YOUR_API_TOKEN');

//Test authentication
$jsonResponse = $objGeniusReferralsAPIClient->testAuthentication();

// Get the list of Genius Referrals client accounts
$jsonResponse = $objGeniusReferralsAPIClient->getAccounts();

// Get the response from the previous request
$aryResponse = json_decode($jsonResponse);

// Get the response code from the previous request
$intResponseCode = $objGeniusReferralsAPIClient->getResponseCode();

// Create new advocate
$aryAdvocate = array('advocate' => array("name" => "Jonh", "lastname" => "Smith", "email" => "jonh@email.com", "payout_threshold" => 10));
$objGeniusReferralsAPIClient->postAdvocate('example-com', $aryAdvocate);

// Get the response code from the previous request
$intResponseCode = $objGeniusReferralsAPIClient->getResponseCode();
 
```

### Más ejemplos

Hemos implementado varios ejemplos donde se muestra cómo utilizar la biblioteca. Por favor revise [Ejemplos de integración](examples.en.md).

Para probar los ejemplos debe sustituir los parameters YOUR_USERNAME y YOUR_API_TOKEN por su usuario y api token asignados en la plataforma Genius Referrals.

Pruebas de unidad
-----------------

El cliente usa PHPUnit para implementar y probar pruebas de unidad. Para ejecutar las pruebas de unidad, primero tiene que instalar las dependencias del proyecto usando Composer. Ejecute ```php composer.phar install --dev```. 

Luego puede ejecutar las pruebas usando el siguiente comando en el directorio root de su projecto:
```
phpunit -c vendor/geniusreferrals/gr-api-php-client/
```
Si esta ejecutando las pruebas con xdebug habilitado, puede que tenga el siguiente problema: ```Fatal error: Maximum function nesting level of '100' reached, aborting!```. Esto puede ser resuelto adicionando ```xdebug.max_nesting_level = 200``` a su archivo php.ini.

Reportando un problema o nueva funcionalidad:
---------------------------------------------

Para reportar un problema utilice [Github issue tracker.](https://github.com/GeniusReferrals/GRAPIPHPClient/issues)
