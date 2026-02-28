#!/bin/bash

barename=$(echo "$1" | sed -E 's/^(.*)\.[^\.]+$/\1/g')

latexmk -pdf -lualatex -shell-escape -interaction=nonstopmode -f -outdir=log "src/$barename".tex

mv log/$barename.pdf ./
