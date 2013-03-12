/*
Guestbank database must be empty.
*/
ALTER TABLE CLIENT
ADD 
(branchcode NUMBER(3, 0) NOT NULL);
	

ALTER TABLE ADMINS ADD(branchcode NUMBER(3, 0) NOT NULL);