/*main bank*/
CREATE TABLE EMPLOYEE(
		empid NUMBER(10, 0) NOT NULL,
		fname VARCHAR(30) NOT NULL,
		mname VARCHAR(30) NOT NULL,
		lname VARCHAR(30) NOT NULL,
		gender VARCHAR(10) NOT NULL,
	/*	branchname VARCHAR(30) NOT NULL,*/
		manager NUMBER(1,0) NOT NULL, /*serve as flag 0 if not manager, else 1*/
	CONSTRAINT employee_empid_pk PRIMARY KEY(empid)
	);
	
CREATE TABLE ACCOUNT(
		accountnum NUMBER(10,0) NOT NULL,
		accountype VARCHAR2(30) NOT NULL,
		balance NUMBER(10,0) NOT NULL,
		atmpin NUMBER(4,0),
		compName VARCHAR2(30),
		/*branchname VARCHAR(30) NOT NULL,*/
	CONSTRAINT account_accountnum_pk PRIMARY KEY(accountnum)
	/*CONSTRAINT CHECK (balance>=0)*/
	); 

CREATE TABLE TRANS(
		accountnum NUMBER(10,0) NOT NULL,
		transactnum NUMBER(30,0) NOT NULL,	/*increment*/
		transactiontype VARCHAR2(30) NOT NULL,	
		transactdate DATE NOT NULL,
		transactcost NUMBER(10,5) NOT NULL,
		/*transactbranch VARCHAR2(50) NOT NULL,	*/	
		transactionop VARCHAR2(7) NOT NULL,	/*debit/credit*/ 
		CONSTRAINT transactions_transactnum_pk PRIMARY KEY(transactnum),
		CONSTRAINT transactions_accountnum_fk FOREIGN KEY(accountnum)
                REFERENCES ACCOUNT(accountnum)
);