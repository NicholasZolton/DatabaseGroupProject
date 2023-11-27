CREATE TABLE `USER`(
    User_ID     INT             NOT NULL  AUTO_INCREMENT,
    UserName    VARCHAR(20)     NOT NULL,
    Email       VARCHAR(30)     NOT NULL,
    PassW       VARCHAR(20)     NOT NULL,
    DateOfBirth VARCHAR(10)     NOT NULL,
   CONSTRAINT UserPK
    PRIMARY KEY(User_ID),
   CONSTRAINT UniqUserName
    UNIQUE(UserName),
   CONSTRAINT UniqEmail
    UNIQUE(Email));

CREATE TABLE BUYER(
    User_ID         INT             NOT NULL,
    Card_Number     VARCHAR(16),
    Card_Expr       VARCHAR(5),
    Security_Code   VARCHAR(3),

   CONSTRAINT BuyerPK
    PRIMARY KEY(User_ID),
   CONSTRAINT BuyerFK
    FOREIGN KEY(User_ID) REFERENCES `USER`(User_ID)
                  ON DELETE CASCADE         ON UPDATE CASCADE);

CREATE TABLE SELLER(
    User_ID         INT             NOT NULL,
    Rating          INT,

   CONSTRAINT SellerPK
    PRIMARY KEY(User_ID),
   CONSTRAINT SellerFK
    FOREIGN KEY(User_ID) REFERENCES `USER`(User_ID)
                  ON DELETE CASCADE         ON UPDATE CASCADE);

CREATE TABLE `ORDER`(
    Order_ID         INT NOT NULL  AUTO_INCREMENT,
    Seller_ID        INT,
    Buyer_ID         INT,

   CONSTRAINT OrderPK
    PRIMARY KEY(Order_ID),
   CONSTRAINT OrderSellerFK
    FOREIGN KEY(Seller_ID) REFERENCES SELLER(User_ID)
                  ON DELETE SET NULL         ON UPDATE CASCADE,
   CONSTRAINT OrderBuyerFK
    FOREIGN KEY(Buyer_ID) REFERENCES BUYER(User_ID)
                  ON DELETE SET NULL         ON UPDATE CASCADE);

CREATE TABLE VENUE(
    Venue_ID         INT             NOT NULL  AUTO_INCREMENT,
    VenueName        VARCHAR(30)     NOT NULL,
    Capacity         INT             NOT NULL    DEFAULT 0,
    StreetAddress    VARCHAR(30)     NOT NULL,
    VenueCity        VARCHAR(30)     NOT NULL,
    VenueState       VARCHAR(30)     NOT NULL,
    Zip_Code         INT     		 NOT NULL,

   CONSTRAINT VenuePK
    PRIMARY KEY(Venue_ID));

CREATE TABLE `EVENT`(
    Event_ID        INT             NOT NULL  AUTO_INCREMENT,
    EventName       VARCHAR(30)     NOT NULL,
    EventType       VARCHAR(30)     NOT NULL,
    EventTime       VARCHAR(30)     NOT NULL,
    EventDate       VARCHAR(30)     NOT NULL,
    Age_Limit       INT             NOT NULL,
    Venue_ID        INT,

   CONSTRAINT EventPK
    PRIMARY KEY(Event_ID),
   CONSTRAINT EventFK
    FOREIGN KEY(Venue_ID) REFERENCES VENUE(Venue_ID)
                  ON DELETE SET NULL    ON UPDATE CASCADE);

CREATE TABLE TICKET(
    Ticket_ID        INT             NOT NULL AUTO_INCREMENT,
    Event_ID         INT,
    Venue_ID         INT,
	Owner_ID		 INT			 NOT NULL,
    
   CONSTRAINT TicketPK
    PRIMARY KEY(Ticket_ID),
   CONSTRAINT TicketEventFK
    FOREIGN KEY(Event_ID) REFERENCES `EVENT`(Event_ID)
                  ON DELETE SET NULL         ON UPDATE CASCADE,
   CONSTRAINT TicketVenueFK
    FOREIGN KEY(Venue_ID) REFERENCES VENUE(Venue_ID)
                  ON DELETE SET NULL         ON UPDATE CASCADE,
   CONSTRAINT TicketOwnerFK
    FOREIGN KEY(Owner_ID) REFERENCES `USER`(User_ID)
                  ON DELETE CASCADE          ON UPDATE CASCADE);

CREATE TABLE TICKETS_IN_ORDER (
    Ticket_ID        INT,
    Order_ID         INT             NOT NULL,
    Price            REAL            NOT NULL,

   CONSTRAINT Tickets_In_OrderPK
    PRIMARY KEY(Ticket_ID,Order_ID),
   CONSTRAINT Tickets_In_OrderTicketFK
    FOREIGN KEY(Ticket_ID) REFERENCES TICKET(Ticket_ID)
                  ON DELETE CASCADE       ON UPDATE CASCADE,
   CONSTRAINT Tickets_In_OrderOrderFK
    FOREIGN KEY(Order_ID) REFERENCES `ORDER`(Order_ID)
                  ON DELETE CASCADE         ON UPDATE CASCADE);