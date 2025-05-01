#!/bin/bash

CI4_PATH=$1

if [ -z "$CI4_PATH" ]; then
    echo "Użycie: ./install.sh /ścieżka/do/projektu_ci4"
    exit 1
fi

echo "=== Instalator Twiga dla CodeIgniter 4 ==="

# Wybór wariantu
echo "Wybierz wariant widoków:"
select VARIANT in "adminlte" "bootstrap5" "simple"; do
    if [[ "$VARIANT" =~ ^(adminlte|bootstrap5|simple)$ ]]; then
        echo "Wybrano wariant: $VARIANT"
        break
    else
        echo "Nieprawidłowy wybór. Spróbuj ponownie."
    fi
done

# Instalacja paczki Twig
composer require "twig/twig:^3.0" twig/intl-extra --working-dir="$CI4_PATH"

echo "📁 Kopiuję wspólne pliki (Twig lib + config)..."
cp -r common/Config/* "$CI4_PATH/app/Config/"
cp -r common/Controllers/* "$CI4_PATH/app/Controllers/"
cp -r common/Database/* "$CI4_PATH/app/Database/"
mkdir -p "$CI4_PATH/app/Language/pl/"
cp -r common/Language/pl/* "$CI4_PATH/app/Language/pl/"
cp -r common/Libraries/* "$CI4_PATH/app/Libraries/"
cp -r common/Models/* "$CI4_PATH/app/Models/"


echo "📁 Kopiuję assets dla wariantu '$VARIANT'..."
cp -r variants/$VARIANT/gulpfile.js "$CI4_PATH/"
cp -r variants/$VARIANT/package.json "$CI4_PATH/"
mkdir -p "$CI4_PATH/resources/" && cp -r variants/$VARIANT/resources/* "$CI4_PATH/resources/"
cp -r variants/$VARIANT/Views/* "$CI4_PATH/app/Views/"

rm $CI4_PATH/app/Views/welcome_message.php

# Run npm install
cd $CI4_PATH
npm install

# 3. Add to GIT
cd $CI4_PATH
git add . && git commit -m "Twig and Front-end installed."

echo "✅ Instalacja zakończona."
