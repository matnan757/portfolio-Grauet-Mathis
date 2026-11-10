# Image pour heberger le portfolio et les sites PHP (stage, chat, hotel) en ligne.
# Un seul serveur PHP sert tout le depot ; les sites sont dans leurs sous-dossiers.
FROM php:8.3-cli

# S'assure que SQLite (utilise par les sites) est disponible.
RUN if ! php -m | grep -qi pdo_sqlite; then \
        apt-get update && apt-get install -y libsqlite3-dev \
        && docker-php-ext-install pdo_sqlite \
        && rm -rf /var/lib/apt/lists/*; \
    fi

WORKDIR /app
COPY . /app

# Render (et les autres hebergeurs) fournissent le port dans la variable PORT.
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t /app"]
