#!/bin/bash

echo "🚀 Iniciando instalación del proyecto..."

# Configurar .env si no existe
echo "📝 Copiando archivo .env.example a .env..."
cp .env.example .env

# Instalar dependencias y configurar entorno
echo "⚙️ Instalando dependencias..."
composer require laravel/sail --dev
composer require darkaonline/l5-swagger --dev
php artisan sail:install --with=mysql,redis,mailpit

# Configurar variables de entorno en .env
echo "⚙️ Configurando variables de entorno..."
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/g' .env
sed -i 's/DB_PASSWORD=/DB_PASSWORD=password/g' .env

# Iniciar contenedores
echo "🐳 Iniciando contenedores Docker..."
./vendor/bin/sail up -d

# Esperar a que MySQL esté listo
echo "⏳ Esperando a que MySQL esté listo..."
sleep 15  # Primero esperamos

# Intentar crear la base de datos con reintentos
echo "🗄️ Creando base de datos si no existe..."
docker exec $(docker ps -qf "name=mysql") mysql -u root -ppassword -e "CREATE DATABASE IF NOT EXISTS laravel;"

# Generar clave de aplicación
echo "🔑 Generando clave de aplicación..."
./vendor/bin/sail artisan key:generate

# Ejecutar migraciones y seeders
echo "🔄 Ejecutando migraciones..."
./vendor/bin/sail artisan migrate:fresh
echo "🌱 Ejecutando seeders..."
./vendor/bin/sail artisan db:seed

echo "🚀 Iniciando instalación de Passport..."

# Mostrar animación de instalación
echo -n "⏳ Installing Passport"
for i in {1..6}; do
    echo -n "."
    sleep 0.5
    if [ $i -eq 6 ]; then
        echo " Presiona Enter para continuar..."
        read
    fi
done
echo ""

# Capturar la salida del comando passport:install
PASSPORT_OUTPUT=$(./vendor/bin/sail artisan passport:install)

# Extraer los IDs y Secrets usando grep y awk
PERSONAL_CLIENT_ID=$(echo "$PASSPORT_OUTPUT" | grep "Client ID" -m 1 | awk '{print $NF}')
PERSONAL_CLIENT_SECRET=$(echo "$PASSPORT_OUTPUT" | grep "Client secret" -m 1 | awk '{print $NF}')
PASSWORD_CLIENT_ID=$(echo "$PASSPORT_OUTPUT" | grep "Client ID" -m 2 | tail -n1 | awk '{print $NF}')
PASSWORD_CLIENT_SECRET=$(echo "$PASSPORT_OUTPUT" | grep "Client secret" -m 2 | tail -n1 | awk '{print $NF}')

# Actualizar el archivo .env
echo -n "⚙️ Actualizando configuración"
for i in {1..5}; do
    echo -n "."
    sleep 0.5
done
echo "Presiona Enter para continuar..."

sed -i '' "s/PASSPORT_PERSONAL_ACCESS_CLIENT_ID=.*/PASSPORT_PERSONAL_ACCESS_CLIENT_ID=$PERSONAL_CLIENT_ID/" .env
sed -i '' "s/PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=.*/PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=$PERSONAL_CLIENT_SECRET/" .env
sed -i '' "s/PASSPORT_PASSWORD_GRANT_CLIENT_ID=.*/PASSPORT_PASSWORD_GRANT_CLIENT_ID=$PASSWORD_CLIENT_ID/" .env
sed -i '' "s/PASSPORT_PASSWORD_GRANT_CLIENT_SECRET=.*/PASSPORT_PASSWORD_GRANT_CLIENT_SECRET=$PASSWORD_CLIENT_SECRET/" .env

# Clear and cache configuration
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan config:cache

# Publicar y generar documentación Swagger
echo "📚 Configurando Swagger..."
./vendor/bin/sail artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
./vendor/bin/sail artisan l5-swagger:generate

echo "✅ Claves de Passport actualizadas en .env"

echo "✅ Instalación completada!"
echo "📖 Puedes acceder a la documentación de la API en: http://localhost/api/documentation"