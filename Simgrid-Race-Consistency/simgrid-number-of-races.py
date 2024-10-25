from bs4 import BeautifulSoup, SoupStrainer
from urllib.request import Request,urlopen
import sys
import re
import json

def parseData():
    url = sys.argv[1]
    strainer = SoupStrainer('div', attrs={'id': 'resultFilter'})
    req = Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    page = urlopen(req)
    html = page.read().decode("utf-8")
    soup = BeautifulSoup(html, "lxml", parse_only=strainer)

    # Find the links to session_type
    result_div = soup.find('div', class_ ='results_option')
    session_type_div = result_div.find_all('div', class_='dropdown-menu')
    session_type_links = session_type_div[1].find_all('a', class_='dropdown-item')

    # Exclude the qualifying result
    number_of_races = len(session_type_links) - 1

    return json.dumps(number_of_races)

print(parseData())