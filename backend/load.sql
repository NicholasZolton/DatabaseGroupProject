LOAD DATA INFILE "data/userdata.dat" INTO TABLE `USER`
FIELDS TERMINATED BY "," ENCLOSED BY '"'
LINES TERMINATED BY "\r\n";

LOAD DATA INFILE "data/buyerdata.dat" INTO TABLE BUYER
FIELDS TERMINATED BY "," ENCLOSED BY '"'
LINES TERMINATED BY "\r\n";

LOAD DATA INFILE "data/sellerdata.dat" INTO TABLE SELLER
FIELDS TERMINATED BY "," ENCLOSED BY '"'
LINES TERMINATED BY "\r\n";

LOAD DATA INFILE "data/venuedata.dat" INTO TABLE VENUE
FIELDS TERMINATED BY "," ENCLOSED BY '"'
LINES TERMINATED BY "\r\n";

LOAD DATA INFILE "data/eventdata.dat" INTO TABLE `EVENT`
FIELDS TERMINATED BY "," ENCLOSED BY '"'
LINES TERMINATED BY "\r\n";

LOAD DATA INFILE "data/ticketdata.dat" INTO TABLE TICKET
FIELDS TERMINATED BY "," ENCLOSED BY '"'
LINES TERMINATED BY "\r\n";

LOAD DATA INFILE "data/orderdata.dat" INTO TABLE `ORDER`
FIELDS TERMINATED BY "," ENCLOSED BY '"'
LINES TERMINATED BY "\r\n";

LOAD DATA INFILE "data/tickets_in_orderdata.dat" INTO TABLE TICKETS_IN_ORDER
FIELDS TERMINATED BY "," ENCLOSED BY '"'
LINES TERMINATED BY "\r\n";