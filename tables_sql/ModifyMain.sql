CREATE TABLE CURRENT_BILLER(
	accountnum NUMBER(10,0) NOT NULL ENABLE,
	billeraccountnum NUMBER(10, 0) NOT NULL ENABLE,
	billername VARCHAR2(30) NOT NULL,
	refnum NUMBER(11,0) NOT NULL,
	CONSTRAINT biller_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES ACCOUNT(accountnum) ENABLE
	/*CONSTRAINT biller_refnum_pk PRIMARY KEY(refnum) ENABLE*/
);

ALTER TABLE BILLS
	ADD CONSTRAINT servicenum_pk PRIMARY KEY(servicenum); 
;