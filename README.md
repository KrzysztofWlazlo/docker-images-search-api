**README dla projektu Docker Images Search API**
Opis

Projekt Docker Images Search API to aplikacja Symfony, która umożliwia wyszukiwanie i zarządzanie tagami obrazów Docker za pomocą Docker Hub API. Aplikacja oferuje zestaw funkcji dostępnych przez interfejs linii poleceń (CLI) oraz API REST, umożliwiając użytkownikom pobieranie danych o tagach obrazów Docker, aktualizowanie lokalnej bazy danych z najnowszymi tagami oraz zarządzanie historią wyszukiwań.
Główne Funkcje

    Pobieranie tagów obrazu Docker: Możliwość pobierania i wyświetlania tagów dla konkretnego obrazu Docker.
    Szczegóły tagu obrazu Docker: Uzyskaj szczegółowe informacje o konkretnym tagu obrazu Docker.
    Zarządzanie historią wyszukiwań: Przechowywanie historii wyszukiwanych tagów i obrazów w lokalnej bazie danych.
    Automatyczna aktualizacja tagów: Komenda Crontab umożliwia regularne aktualizowanie lokalnej bazy danych z najnowszymi tagami obrazów Docker.

**Konfiguracja Crontab**

Aby zapewnić aktualność danych w lokalnej bazie danych, projekt wykorzystuje zadanie Crontab, które regularnie odpytuje API Docker Hub i aktualizuje bazy danych z nowymi tagami. Zadanie cron jest skonfigurowane do uruchamiania codziennie o 02:00:

00 02 * * * /usr/bin/php /var/www/html/docker-images-search-api/bin/console app:update-docker-tags

Upewnij się, że ścieżka do interpretera PHP i ścieżka projektu są prawidłowe dla Twojego środowiska.

**Endpointy API REST**

Aplikacja oferuje następujące endpointy API REST do zarządzania tagami obrazów Docker i historią wyszukiwań:

Pobieranie tagów dla obrazu Docker:

_/api/docker/tags/namespace/{namespace}/repository/{repository}_

Pobieranie szczegółów konkretnego tagu obrazu Docker:

_/api/docker/tags/namespace/{namespace}/repository/{repository}/tag/{tagName_}

Pobieranie historii wyszukiwań dla obrazu Docker:

_/api/search/history/namespace/{namespace}/repository/{repository}

Pobieranie historii wyszukiwań dla konkretnego tagu:

_/api/search/history/tag/{tagName}

Pobieranie historii wyszukiwań dla konkretnego tagu i obrazu Docker:

_/api/search/history/namespace/{namespace}/repository/{repository}/tag/{tagName}_

**Kolekcja Postman**

Projekt zawiera kolekcję Postman, która ułatwia testowanie dostępnych endpointów API REST. Kolekcję można znaleźć w katalogu projektu. Importuj ją do aplikacji Postman, aby szybko testować różne funkcje API.

**Wymagania**

    PHP 8.0 lub nowszy
    Symfony 5.3 lub nowszy
    Doctrine ORM
    Klient HTTP Symfony

**Instalacja**

    Sklonuj repozytorium projektu.
    Zainstaluj zależności za pomocą Composer.
    Skonfiguruj połączenie z bazą danych w pliku .env.
    Uruchom migracje Doctrine, aby utworzyć schemat bazy danych.
