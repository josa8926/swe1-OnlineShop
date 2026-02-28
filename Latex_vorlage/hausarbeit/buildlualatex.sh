#!/bin/bash

#lualatex poster.tex
latexmk -pdf -lualatex -shell-escape -interaction=nonstopmode -f -outdir=log hauptdatei.tex 

mv log/hauptdatei.pdf ./ 
cp hauptdatei.pdf /var/www/html/$USER/
