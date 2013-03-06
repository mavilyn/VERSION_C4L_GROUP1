/*online bank*/

CREATE TABLE USERS(
	username VARCHAR2(20) NOT NULL ENABLE,
	password VARCHAR2(100) NOT NULL ENABLE,
	usertype VARCHAR2(20) NOT NULL ENABLE,	/*bank manager - has power to add admin, teller, client*/
	CONSTRAINT user_username_pk PRIMARY KEY(username) ENABLE
);

CREATE TABLE CLIENT(
		username VARCHAR2(20) NOT NULL ENABLE,
		password VARCHAR2(100) NOT NULL ENABLE,
		accountnum NUMBER(10,0) NOT NULL ENABLE,
		fname VARCHAR2(30) NOT NULL ENABLE,
		mname VARCHAR2(30) NOT NULL ENABLE,
		lname VARCHAR2(30) NOT NULL ENABLE,
		gender VARCHAR2(10) NOT NULL ENABLE,
		homeaddress VARCHAR2(80) NOT NULL ENABLE,
		civilstat VARCHAR2(50) NOT NULL ENABLE,
		bday DATE NOT NULL ENABLE,
		email VARCHAR2(50),
		contact VARCHAR2(50),
		spouse VARCHAR2(100),
		mother VARCHAR2(100) NOT NULL ENABqLE,
		secret VARCHAR2(50) NOT NULL ENABLE,
		answer VARCHAR2(50) NOT NULL ENABLE,
		activation NUMBER(1,0) NOT NULL ENABLE, /*serve as flag*/
	CONSTRAINT client_accountnum_pk PRIMARY KEY(accountnum) ENABLE,
	CONSTRAINT client_username_fk FOREIGN KEY(username)
                REFERENCES USERS(username)
	);
   
CREATE TABLE ADMINS(
		username VARCHAR2(20) NOT NULL ENABLE,
		password VARCHAR2(100) NOT NULL ENABLE,
		empid NUMBER(10,0) NOT NULL ENABLE,
		mgrflag NUMBER(1,0) NOT NULL ENABLE,
	CONSTRAINT mgradmin_empid_pk PRIMARY KEY(empid) ENABLE,
	CONSTRAINT mgradmin_username_fk FOREIGN KEY(username)
                REFERENCES USERS(username)
);

CREATE TABLE BILLERLIST(
	accountnum NUMBER(10,0) NOT NULL ENABLE,	/*check yung type sa main bank*/
	billername VARCHAR2(30) NOT NULL ENABLE,
	CONSTRAINT billerlist_accountnum_pk PRIMARY KEY(accountnum)
	/*CONSTRAINT billerlist_billername_ck UNIQUE(billername) ENABLEs*/
);

CREATE TABLE BILLER(
	accountnum NUMBER(10,0) NOT NULL ENABLE,
	billeraccountnum NUMBER(10, 0) NOT NULL ENABLE,
	billername VARCHAR2(30) NOT NULL,
	refnum NUMBER(11,0) NOT NULL,
	CONSTRAINT biller_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES CLIENT(accountnum) ENABLE
	/*CONSTRAINT biller_refnum_pk PRIMARY KEY(refnum) ENABLE*/
);

CREATE TABLE ADDBILLER_REQUEST(		
	accountnum NUMBER(10,0) NOT NULL ENABLE,
	billeraccountnum NUMBER(10, 0) NOT NULL ENABLE,
	billername VARCHAR2(30) NOT NULL,
	refnum NUMBER(11,0) NOT NULL,
	requestDate DATE NOT NULL,
	appDisDate DATE,
	appDisFlag NUMBER(1,0),
	CONSTRAINT addbiller_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES CLIENT(accountnum) ENABLE
	/*CONSTRAINT addbiller_refnum_pk PRIMARY KEY(refnum) ENABLE*/
);

CREATE TABLE BILLER_REQ_STAT(		
	accountnum NUMBER(10,0) NOT NULL ENABLE,
	billeraccountnum NUMBER(10, 0) NOT NULL ENABLE,
	billername VARCHAR2(30) NOT NULL,
	refnum NUMBER(11,0) NOT NULL,
	requestDate DATE NOT NULL,
	appDisDate DATE,
	appDisFlag NUMBER(1,0),
	CONSTRAINT addbiller_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES CLIENT(accountnum) ENABLE
	/*CONSTRAINT addbiller_refnum_pk PRIMARY KEY(refnum) ENABLE*/
);

CREATE TABLE TRANSACT_PAYBILLS(
		accountnum NUMBER(10,0) NOT NULL ENABLE,
		billeraccountnum NUMBER(10,0) NOT NULL ENABLE,
		refnum NUMBER(11,0) NOT NULL ENABLE,
		transactdate DATE NOT NULL ENABLE,
		transactcost NUMBER(10,5) NOT NULL,
		/*transactbranch VARCHAR(50) NOT NULL,	*/	
		/*transactnum NUMBER(30,0) NOT NULL ENABLE, /*increments*/
		/*CONSTRAINT pay_transactnum_pk PRIMARY KEY(transactnum),*/
		CONSTRAINT paybills_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES CLIENT(accountnum)/*,
		CONSTRAINT FOREIGN KEY(refnum)
                REFERENCES BILLER(refnum)*/
	);
	
CREATE TABLE ACCOUNTCONNECTED(
	accountnum NUMBER(10,0) NOT NULL,
	otheraccountnum NUMBER(10,0) NOT NULL, /*check kung nasa bank database at hindi siya yung sariling account*/
	preferredname VARCHAR2(30),	
	CONSTRAINT accountconnected_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES CLIENT(accountnum)
	/*,CONSTRAINT accountconnected_otheraccountnum_pk PRIMARY KEY(otheraccountnum)*/
);

CREATE TABLE ADDACCNTCONNECT_REQUEST(
	accountnum NUMBER(10,0) NOT NULL,
	otheraccountnum NUMBER(10,0) NOT NULL, /*check kung nasa bank database at hindi siya yung sariling account*/
	preferredname VARCHAR2(30),
	CONSTRAINT accountconnected_account_fk FOREIGN KEY(accountnum)
                REFERENCES CLIENT(accountnum)
	/*,CONSTRAINT accountconnected_otheraccountnum_pk PRIMARY KEY(otheraccountnum)*/
);
/*
CREATE TABLE ADDBILLER_REQUEST(		
	accountnum NUMBER(10,0) NOT NULL ENABLE,		
	billername VARCHAR2(30) NOT NULL,
	refnum NUMBER(11,0) NOT NULL,
	CONSTRAINT addbiller_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES BILLERLIST(accountnum) ENABLE,
	CONSTRAINT addbiller_refnum_pk PRIMARY KEY(refnum) ENABLE
);*/
/*updated*/



CREATE TABLE TRANSACT_TRANSFER(
		accountnum NUMBER(10,0) NOT NULL ENABLE,
		otheraccountnum NUMBER(10,0) NOT NULL ENABLE,
		transactiontype VARCHAR2(30) NOT NULL ENABLE,	
		transactdate DATE NOT NULL ENABLE,
		transactcost NUMBER(10,5) NOT NULL,
		/*transactbranch VARCHAR(50) NOT NULL,	*/	
		transactionop VARCHAR2(7) NOT NULL ENABLE, 
		transactnum NUMBER(30,0) NOT NULL ENABLE, /*increments*/
		CONSTRAINT transfer_transactnum_pk PRIMARY KEY(transactnum),
		CONSTRAINT transfer_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES CLIENT(accountnum)
		/*,CONSTRAINT FOREIGN KEY(otheraccountnum)
                REFERENCES ACCOUNTCONNECTED(otheraccountnum)
		*/
	);