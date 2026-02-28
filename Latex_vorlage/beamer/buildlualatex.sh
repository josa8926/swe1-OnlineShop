#!/bin/bash

#lualatex poster.tex
latexmk -pdf -lualatex -shell-escape -interaction=nonstopmode -f -outdir=log beamer.tex

mv log/beamer.pdf ./
cp beamer.pdf /var/www/html/$USER/
