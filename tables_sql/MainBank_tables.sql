/*main bank*/
CREATE TABLE BANK_BRANCH(
		branchcode NUMBER(3, 0) NOT NULL,
		address VARCHAR(80) NOT NULL,
	CONSTRAINT bank_code_pk PRIMARY KEY(branchcode)
	);

	
CREATE TABLE EMPLOYEE(
		empid NUMBER(10, 0) NOT NULL,
		fname VARCHAR(30) NOT NULL,
		mname VARCHAR(30) NOT NULL,
		lname VARCHAR(30) NOT NULL,
		gender VARCHAR(10) NOT NULL,
		branchcode NUMBER(3, 0) NOT NULL,
		/*branchname VARCHAR(30) NOT NULL,*/
		manager NUMBER(1,0) NOT NULL, /*serve as flag 0 if not manager, else 1*/
	CONSTRAINT employee_empid_pk PRIMARY KEY(empid),
	CONSTRAINT emp_branchcode_fk FOREIGN KEY(branchcode)
                REFERENCES BANK_BRANCH(branchcode)
	);
	
CREATE TABLE ACCOUNT(
		accountnum NUMBER(10,0) NOT NULL,
		accountype VARCHAR2(30) NOT NULL,
		balance NUMBER(12,2) NOT NULL,
		atmpin VARCHAR2(100),
		compName VARCHAR2(30),
		branchcode NUMBER(3, 0) NOT NULL,
		/*branchname VARCHAR(30) NOT NULL,*/
	CONSTRAINT account_accountnum_pk PRIMARY KEY(accountnum),
	CONSTRAINT accnt_branchcode_fk FOREIGN KEY(branchcode)
                REFERENCES BANK_BRANCH(branchcode)
	/*CONSTRAINT CHECK (balance>=0)*/
	); 

CREATE TABLE TRANS(
		transactnum NUMBER(30,0) NOT NULL,	/*increment*/
		accountnum NUMBER(10,0) NOT NULL,
		otheraccountnum NUMBER(10,0) NOT NULL,
		transactiontype VARCHAR2(30) NOT NULL,	/*debit/credit*/
		transactmedium VARCHAR2(30) NOT NULL,   /*online/manual*/
		transactionop VARCHAR2(30) NOT NULL,	/*transfer_fund, pay_bill, withdraw, deposit*/ 
		transactdate DATE NOT NULL,
		transactcost NUMBER(12,2) NOT NULL,
		branchcode NUMBER(3, 0) NOT NULL,
		/*transactbranch VARCHAR2(50) NOT NULL,	*/	
		CONSTRAINT transactions_transactnum_pk PRIMARY KEY(transactnum),
		CONSTRAINT transactions_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES ACCOUNT(accountnum),
		CONSTRAINT trans_branchcode_fk FOREIGN KEY(branchcode)
                REFERENCES BANK_BRANCH(branchcode)
);

CREATE TABLE BILLERLIST(
	blist_accountnum NUMBER(10,0) NOT NULL ENABLE,	/*check yung type sa main bank*/
	billername VARCHAR2(30) NOT NULL ENABLE,
	CONSTRAINT billerlist_accountnum_pk PRIMARY KEY(blist_accountnum),
	CONSTRAINT billerlist_accountnum_fk FOREIGN KEY(blist_accountnum)
		REFERENCES ACCOUNT(accountnum)
	/*CONSTRAINT billerlist_billername_ck UNIQUE(billername) ENABLEs*/
);

CREATE TABLE BILLER_CUST(
	refnum NUMBER(11,0) NOT NULL ENABLE,	/*check yung type sa main bank*/
	biller_accountnum NUMBER(10,0) NOT NULL ENABLE,	/*check yung type sa main bank*/
	cust_name VARCHAR2(80) NOT NULL ENABLE,
	CONSTRAINT cust_refnum_pk PRIMARY KEY(refnum),
	CONSTRAINT cust_accountnum_fk FOREIGN KEY(biller_accountnum)
		REFERENCES BILLERLIST(blist_accountnum)
	/*CONSTRAINT billerlist_billername_ck UNIQUE(billername) ENABLEs*/
);

CREATE TABLE BILLS(
	servicenum NUMBER(20, 0) NOT NULL,	/*check yung type sa main bank*/
	biller_accountnum NUMBER(10,0) NOT NULL ENABLE,	/*check yung type sa main bank*/
	refnum NUMBER(11,0) NOT NULL ENABLE,	/*check yung type sa main bank*/
	amountdue NUMBER(12,2) NOT NULL ENABLE,	/*check yung type sa main bank*/
	dateissued DATE NOT NULL ENABLE,	/*check yung type sa main bank*/
	CONSTRAINT bills_accountnum_fk FOREIGN KEY(biller_accountnum)
		REFERENCES BILLERLIST(blist_accountnum),
	CONSTRAINT bills_refnum_fk FOREIGN KEY(refnum)
		REFERENCES BILLER_CUST(refnum)
	/*CONSTRAINT billerlist_billername_ck UNIQUE(billername) ENABLEs*/
);

CREATE SEQUENCE branch_code_seq
    MINVALUE 1
    MAXVALUE 9999
    START WITH 1
    INCREMENT BY 1
    NOCACHE
    NOCYCLE;

CREATE SEQUENCE emp_id_seq				/*empid*/
    MINVALUE 1
    MAXVALUE 999999999999999999999999999
    START WITH 1000
    INCREMENT BY 1
    NOCACHE
    NOCYCLE;
	
CREATE SEQUENCE accountnum_seq
    MINVALUE 1
    MAXVALUE 9999999999
    START WITH 1234567890
    INCREMENT BY 1
    NOCACHE
    NOCYCLE;

CREATE SEQUENCE trans__seq
    MINVALUE 1
    MAXVALUE 99999999999999999999999999999999
    START WITH 1
    INCREMENT BY 1
    NOCACHE
    NOCYCLE;

CREATE SEQUENCE servicenum__seq
    MINVALUE 1
    MAXVALUE 99999999999999999999999999999999
    START WITH 1
    INCREMENT BY 1
    NOCACHE
    NOCYCLE;
	
create or replace function md5(
  in_string in varchar2)
return varchar2
as
  cln_md5raw raw(2000);
  out_raw raw(16);
begin
  cln_md5raw := utl_raw.cast_to_raw(in_string);
  dbms_obfuscation_toolkit.md5(input=>cln_md5raw,checksum=>out_raw);
  -- return hex version (32 length)
  return rawtohex(out_raw);
end;
/