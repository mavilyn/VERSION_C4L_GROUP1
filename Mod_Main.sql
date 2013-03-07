CREATE TABLE bill(
	billeraccountnum NUMBER(10,0) NOT NULL,
	refnum NUMBER(11,0) NOT NULL,
	bill_cost NUMBER(10,0) NOT NULL,
	CONSTRAINT bill_account_fk FOREIGN KEY(billeraccountnum)
                REFERENCES ACCOUNT(accountnum),
	CONSTRAINT bill_refnum_pk PRIMARY KEY(refnum)
	/*,CONSTRAINT accountconnected_otheraccountnum_pk PRIMARY KEY(otheraccountnum)*/
);