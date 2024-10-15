from bs4 import BeautifulSoup
from urllib.request import Request,urlopen
# from urllib.parse import urlparse, parse_qs
import sys
import json

# This script is designed to calculate gap from pole position for a quali session on Simgrid. It returns a json_file with driver and gap in % from the pole position.

def convertTimeToGapInPercent(bestlap, lap):
    return round(lap * 100 / bestlap, 2)

# url = "https://www.thesimgrid.com/championships/9918/results?filter_class_id=22706&overall=false&race_id=59881&session_type=qualifying"

def parseData():
    url = sys.argv[1]
    req = Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    page = urlopen(req)
    html = page.read().decode("utf-8")
    soup = BeautifulSoup(html, "html.parser")

    result_list = soup.find('table', class_='table-results')
    tbody = result_list.find('tbody')

    # Retrieve the rows of tbody
    rows = tbody.find_all('tr')

    # Get the 5th cell of the row which is the quali laptime
    laptimes = []
    for row in rows:
        if 'overall=true' in url:
            laptimes.append(row.select_one(':nth-child(6)'))
        else: laptimes.append(row.select_one(':nth-child(5)'))
    # The first cell is always the pole position time
    pole_position_laptime = int(laptimes[0]['data-milliseconds'])

    quali_gaps = []
    for laptime in laptimes:
        # Needed to invalidate driver with no quali time
        if (len(laptime.getText()) > 3 ):
            quali_gaps.append(convertTimeToGapInPercent(pole_position_laptime, int(laptime['data-milliseconds'])))

    # Get last name of the drivers
    # lastnames = result_list.find_all('a', attrs={'title': True})
    # As driver / team can have no links (might be blocked), find the second cell from the tbody and get the text content of the span
    lastnames = []
    if 'overall=true' in url:
        cells = tbody.find_all('td')[1::10]
    else: cells = tbody.find_all('td')[1::9]
    for cell in cells:
        lastnames.append(cell.select_one(':nth-child(1)').getText().strip())

    # Get race-id
    # parsed_url = urlparse(url)
    #raceId = parse_qs(parsed_url.query)['race_id'][0]

    # Get event title
    # event = soup.find('h1').get_text().replace(' ', '')

    result = {}
    i = 0
    while i < len(quali_gaps):
        result[i+1] = {'driver':lastnames[i], 'gap': quali_gaps[i]}
        i += 1
    json_data = json.dumps(result)

    return json_data

    # with open(event.strip() + '-' + raceId + ".json", "w") as outfile:
    #    outfile.write(json_data)

print(parseData())