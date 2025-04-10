# Simgrid Analysis

## Introduction

This project is a Symfony application that performs web scrapping from thesimgrid.com website using the Symfony Process.

## What can be done with this application ?

- You can view the qualification gaps in percents.
- You can view the race consistency in percents.
- Datas can be downloaded as JSON or CSV files.
- Only working for Asseto Corsa Competizione sessions.

## Tools and learnings from this project

- Symfony Process: after form verification, the python script will perform the scrapping and return a Json response. Symfony controller will then handle the data.
- Webscrapping with Python and BeautifulSoup 4 library.

## Future development

- UI improvements
- Same features with Le Mans Ultimate game.