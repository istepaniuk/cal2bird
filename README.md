# cal2bird

Reads an iCalendar (.ics) file from stdin and creates the corresponding entries on Moneybird's timesheet throught their API.

Without downloading anything, you can run this with docker running:
```
$ docker run -it istepaniuk/cal2bird:latest MONEYBIRD_TOKEN "The Project" "2022-03-31" < yourcalendar.ics
```
This will create, for any calendar event named exactly "The Project"  in the .ics file, 
an equivalent entry in Moneybird for a project named "The Project" (that must exist in your administration).
Moreover, only dates AFTER "2022-03-31" will be read from the calendar.

MONEYBIRD_TOKEN is of course a Moneybird API token. You need to create that yourself in [moneybird.com/user/applications](https://moneybird.com/user/applications)

You could also pipe a Google calendar (iCal public url) into Moneybird directly, by using curl:
```
$ curl https://calendar.google.com/xyz/basic.ics | docker run -it ... (same as before)
``
