# bulkemail
Send bulk email from database using common free mail provider

This is a PHP script to send the same email to many people using one of
the main free email providers. Up to two different email languages are
supported.

Email addresses are taken from a MySQL database (schema is given in
bulkemail.sql). Contacts are taken from the target table and moved to
target_sent table as soon as the email is sent.

Text of the email, STMP credentials and other options must be first
configured in file BulkEmail.php. Then run send.php on the command
line and redirect any protocol output to smtp.log for later inspection:

$ php send.php >>smtp.log 2>&1

Progress can be checked real-time in another terminal:

$ tail -f send.log
...
(ctrl-c to exit)

This script stops at the first error (connection error, throttle
limits reached, etc), but it can be re-run later on to resume from
the last email that failed.

Please note that if a contact is moved to target_sent that does NOT
imply the email was successfully sent.  The way most STMP servers work
is that they blindy accept and queue email.  Therefore you would not
know immediately whether the email was received or not. This script
does the sending only and cannot thus detect invalid email addresses.
You should look for such notification manually in the email account
INBOX.
