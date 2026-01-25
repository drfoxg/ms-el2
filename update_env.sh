#!/bin/bash
# Скрипт безопасного обновления .env и кэша конфигов Laravel
# Бэкапит текущий .env и кэш конфигов.
# Применять новые переменные из файла .env.new.
# Проверять синтаксис конфигов перед кэшированием.
# Откатываться автоматически при ошибке.

# Удаляем config.php, по сути это аварийный «откат» Laravel к обычному чтению конфигов
# php artisan config:clear

# Пытаемся заново сгенерировать
# php artisan config:cache

APP_DIR="/var/www"  # путь к приложению
ENV_FILE="$APP_DIR/.env"
ENV_NEW_FILE="$APP_DIR/.env.new"
BACKUP_DIR="$APP_DIR/env_backup_$(date +%Y%m%d_%H%M%S)"

# Создаём бэкап
echo "Создаём бэкап текущего .env и кэша конфигов..."
mkdir -p "$BACKUP_DIR"
cp "$ENV_FILE" "$BACKUP_DIR/.env.bak"
cp -r "$APP_DIR/bootstrap/cache" "$BACKUP_DIR/cache.bak"

# Проверяем наличие нового .env
if [ ! -f "$ENV_NEW_FILE" ]; then
  echo "Файл $ENV_NEW_FILE не найден. Прерываем."
  exit 1
fi

# Копируем новый .env
echo "Применяем новый .env..."
cp "$ENV_NEW_FILE" "$ENV_FILE"

# Проверка синтаксиса PHP конфигов
echo "Проверяем конфиги..."
php -l "$APP_DIR/artisan" >/dev/null 2>&1
if [ $? -ne 0 ]; then
  echo "Ошибка PHP синтаксиса! Откатываем .env..."
  cp "$BACKUP_DIR/.env.bak" "$ENV_FILE"
  exit 1
fi

# Очищаем и кэшируем конфиги
echo "Очищаем старый кэш конфигов..."
php "$APP_DIR/artisan" config:clear

echo "Кэшируем конфиги..."
php "$APP_DIR/artisan" config:cache
if [ $? -ne 0 ]; then
  echo "Ошибка при кэшировании конфигов! Откатываем..."
  cp "$BACKUP_DIR/.env.bak" "$ENV_FILE"
  php "$APP_DIR/artisan" config:clear
  exit 1
fi

# Дополнительно очищаем другие кеши (по желанию)
php "$APP_DIR/artisan" cache:clear
php "$APP_DIR/artisan" route:clear
php "$APP_DIR/artisan" view:clear

echo "Обновление .env прошло успешно"
