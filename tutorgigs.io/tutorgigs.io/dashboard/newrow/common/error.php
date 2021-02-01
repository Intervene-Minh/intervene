{"status":"error","data":{"message":"Consumer key not sent","errors":[],"code":440}}


============================

@#

/****
# Refrence>>
https://github.com/IMSGlobal/LTI-Tool-Provider-Library-PHP/blob/master/src/OAuth/OAuthConsumer.php

https://github.com/IMSGlobal/LTI-Tool-Provider-Library-PHP/blob/master/src/OAuth/OAuthConsumer.php

IMP>> API’s are separated by controllers: users, rooms, files, etc.


https://www.imsglobal.org/sites/default/files/lti/tp-library-php/docs/OAuthRequest_8php_source.html

**/


GET rooms/url/<room_id>

https://smart.newrow.com/backend/api/rooms/url/<room_id>

GET rooms/participants/<room_id>
PUT rooms/participants/<room_id>


============================
GET rooms/url/<room_id>
DESCRIPTION:
Get secured authenticated room URL for a user.
Api returns a signed URL which enables a specific user to redirect to a specific room.
The returned URL will expire after 30 minutes.
========================

 $client = new Zend_Http_Client();