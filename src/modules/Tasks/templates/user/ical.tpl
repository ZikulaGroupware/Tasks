BEGIN:VCALENDAR
PRODID:Zikula
VERSION:2.0



{foreach from=$tasks item="task"}

{$task.cr_date}

BEGIN:VTODO
DTSTAMP:20110415T230442Z
ORGANIZER:MAILTO:info@stura.org
ATTENDEE;RSVP=FALSE;PARTSTAT=ACCEPTED;ROLE=REQ-PARTICIPANT:mailto:
 info@stura.org
CREATED:20110415T230308Z
UID:uid{$task.tid}@stura.wuertz.org
SEQUENCE:3
LAST-MODIFIED:20110415T230435Z
SUMMARY:{$task.title}
STATUS:NEEDS-ACTION
PRIORITY:{$task.priority}
DUE;VALUE=DATE:{$task.due}
PERCENT-COMPLETE:{$task.progress}
END:VTODO
{/foreach}


END:VCALENDAR
