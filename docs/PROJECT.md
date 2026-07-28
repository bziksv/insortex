# insortex.ru — документация проекта

## О проекте

Сайт на **1С-Битрикс** (корпоративный лендинг / каталог на базе **RANX: Creator**).

| Параметр | Значение |
|----------|----------|
| CMS | 1С-Битрикс |
| Шаблон | `bitrix/templates/ranx-landing/` |
| Модуль шаблона | `bitrix/modules/ranx.landing/` |
| Локальный URL | **http://localhost:8087/** |
| Локальная БД | `insortex_ru` @ `127.0.0.1` |
| Git | [github.com/bziksv/insortex](https://github.com/bziksv/insortex) |
| Prod IP | `155.212.171.103` |

---

## Сервер и окружения

### Правило работы

> **Разработка только локально** (`http://localhost:8087/`).  
> **Prod** (`insortex.ru`) — только по явной отдельной просьбе.

| Окружение | Домен | Путь | IP |
|-----------|-------|------|-----|
| **Local** | localhost:8087 | `/Users/stanislav/Documents/projects/insortex/insortex.ru` | — |
| **Prod** | insortex.ru | `/var/www/insortex_ru_usr/data/www/insortex.ru` | 155.212.171.103 |

### SSH

```bash
ssh root@155.212.171.103
```

Рекомендуемая запись в `~/.ssh/config`:

```
Host insortex
    HostName 155.212.171.103
    User root
    IdentityFile ~/.ssh/id_ed25519
    IdentitiesOnly yes
```

### Деплой на prod (только по явной просьбе)

Корень git на сервере = document root сайта.

```bash
SITE=/var/www/insortex_ru_usr/data/www/insortex.ru
cd "$SITE"
git fetch origin && git checkout main && git reset --hard origin/main
chown -R insortex_ru_usr:insortex_ru_usr "$SITE"
# очистка кеша при необходимости:
# rm -rf bitrix/cache/* bitrix/managed_cache/* bitrix/stack_cache/*
```

Секреты (`.settings.php`, `dbconn.php`, `license_key.php`) **не в git** — лежат только на сервере / в `.local/backup` локально.

---

## Локальный запуск (щадящий режим)

Порты: **HTTP 8087**, **php-fpm 9087** (не пересекаются с metplus `:8086`).

Ресурсы урезаны: 1 nginx worker, php-fpm `max_children=3`, `memory_limit=256M`.

```bash
cd /Users/stanislav/Documents/projects/insortex/insortex.ru

# 1) MySQL (Homebrew)
brew services start mysql

# 2) БД из дампа (лежит рядом с репо: ../insortex_ru.sql.gz)
./scripts/setup-local-db.sh          # или --force для переимпорта
# большой дамп: ./scripts/setup-local-db.sh --background

# 3) Старт nginx + php-fpm 8.3
./scripts/start-dev.sh

# Стоп
./scripts/stop-dev.sh
```

| Файл | Назначение |
|------|------------|
| `.local/db.env` | Локальные креды БД (не в git) |
| `.local/nginx/`, `.local/php/` | Конфиги щадящего стека |
| `scripts/start-dev.sh` / `stop-dev.sh` | Старт / стоп |
| `scripts/setup-local-db.sh` | Создать БД + импорт |
| `scripts/apply-local-db-config.sh` | Прописать localhost в `.settings.php` / `dbconn.local.php` |

Архивы вне git (рядом с репо):

- `../insortex.ru.tar.gz` — полный снимок сайта с prod
- `../insortex_ru.sql` / `../insortex_ru.sql.gz` — дамп БД

`upload/` в git не хранится — для медиа локально нужен sync с prod или распаковка из tar.

---

## Архитектура (коротко)

```
bitrix/templates/ranx-landing/   — шаблон сайта
bitrix/modules/ranx.landing/     — кастомный модуль RANX
catalog/, about/, services/…     — разделы
bitrix/php_interface/            — init / dbconn
upload/                          — медиа (не в git)
```

### Инфоблоки (по sitemap / структуре)

Идентификаторы уточнять в админке / `b_iblock`. На prod встречаются sitemap-iblock `1–9`, `11`, `13–14`, `16–21`, `25`, `27`.

---

## Git

```bash
cd /Users/stanislav/Documents/projects/insortex/insortex.ru
git add -A
git commit -m "…"
git push origin main
```

Не коммитить: секреты Битрикс, `.local/`, `upload/`, `*.sql*`, `*.tar.gz`.
