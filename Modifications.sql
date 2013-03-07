Drop table addaccntconnect_request;
Drop table addbiller_request;

CREATE TABLE ADDACCNTCONNECT_REQUEST(
	accountnum NUMBER(10,0) NOT NULL,
	otheraccountnum NUMBER(10,0) NOT NULL, /*check kung nasa bank database at hindi siya yung sariling account*/
	preferredname VARCHAR2(30),
	requestDate DATE NOT NULL,
	appDisDate DATE,
	appDisFlag NUMBER(1,0),
	CONSTRAINT accountconnected_account_fk FOREIGN KEY(accountnum)
                REFERENCES CLIENT(accountnum)
	/*,CONSTRAINT accountconnected_otheraccountnum_pk PRIMARY KEY(otheraccountnum)*/
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

CREATE SEQUENCE trans_transfer_seq
    MINVALUE 1
    MAXVALUE 999999999999999999999999999
    START WITH 1
    INCREMENT BY 1
    NOCACHE
    NOCYCLE;
