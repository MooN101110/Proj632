import icalendar
from urllib.request import urlretrieve
import csv 
import json

url = "https://ade-usmb-ro.grenet.fr/jsp/custom/modules/plannings/direct_cal.jsp?data=b5cfb898a9c27be94975c12c6eb30e9233bdfae22c1b52e2cd88eb944acf5364c69e3e5921f4a6ebe36e93ea9658a08f,1&resources=9497,7954,4941,4886&projectId=1&calType=ical&lastDate=2042-08-14"
fichier = "file.ical"
urlretrieve(url, fichier)

with open(fichier) as f:
    calendar = icalendar.Calendar.from_ical(f.read())
print(calendar)

evenement=[]
for event in calendar.walk('VEVENT'):
    evenement.append([str(event.get('SUMMARY')),str(event.get('LOCATION')),str(event.get('DTSTART')),str(event.get('DTEND'))])
print(evenement)

#sauvergarde CSV
with open("evenement.csv","wt+",newline="") as f:
    writer=csv.writer(f)
    for row in evenement:
        writer.writerow(row)
 

data={}
n=0
#sauvegarde json
for elt in evenement:
    data[f'evenement-{n}']={
        'nom':elt[0],
        'salle':elt[1],
        'debut':elt[2][10:-17],
        'fin':elt[3][10:-17],
    }
    n+=1

with open("evenement_cal.json","w") as f:
    json.dump(data,f,indent=4)