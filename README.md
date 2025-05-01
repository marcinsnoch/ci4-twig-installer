# CodeIgniter 4 Twig Installer

Automatyczny instalator integracji **Twig** z frameworkiem **CodeIgniter 4**, zawierający różne warianty startowe z gotowymi widokami.

---

## 🔧 Wymagania

- PHP >= 7.4
- CodeIgniter 4
- Composer
- NodeJS + NPM

---

## 📦 Co zawiera?

- Integracja z Twigiem (`twig/twig` i `twig/intl-extra`)
- Gotowa biblioteka i config Twig
- Przykładowe widoki (`Views/`)
- `BaseController` z konfiguracją Twiga
- `Home.php` jako przykładowy kontroler
- Warianty stylu: `simple`, `bootstrap5`, `adminlte`

---

## 🧪 Instalacja

1. Pobierz lub sklonuj repozytorium:
   
   ```bash
   git clone https://github.com/marcinsnoch/ci4-twig-installer.git
   ```

2. Przejdź do katalogu skryptu:
   
   ```bash
   cd ci4-twig-installer
   ```

3. Uruchom instalator wraz ze ścieżką do projektu Codeigniter 4
   
   ```bash
   ./install.sh /sciezka/do/projektu
   ```
