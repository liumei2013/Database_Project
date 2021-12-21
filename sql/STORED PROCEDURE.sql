CREATE PROCEDURE tb_employee_charge()
BEGIN

# Step 1 - Charge according to employee category
# Basic: Employee can only view jobs but cannot apply. No charge
# Prime: Employee can view jobs as well as apply for up to five jobs. A monthly charge of $10 will be applied.
# Gold: Employee can view and apply to as many jobs as he/she wants. A monthly charge of $20 will be applied.

UPDATE TB_EMPLOYEE e
SET balance = balance - (
	SELECT month_fee 
	FROM TB_EMPLOYEE_CATEGORY c
	WHERE e.category = c.category_id
);

# Step 2 - Auto pay according to auto pay setting and default payment method
# Here we assume the payment account has enough money to pay balance.
# Only the negative balance will be paid.

UPDATE TB_EMPLOYEE e,TB_AUTO_PAY a
SET e.balance = 0
WHERE e.default_pm_id IS NOT NULL AND e.auto_pay = a.auto_pay AND a.value = 'Enable' AND e.balance < 0;

# Step 3 - Check status according to balance and pay time
# An account that is negative will be frozen until it is settled and a payment is made
# A suffering account for a year will be deactivated automatically by the system.

UPDATE TB_EMPLOYEE
SET status = (CASE
	WHEN balance < 0 AND TIMESTAMPDIFF(MONTH,last_pay_time,now()) >= 12 THEN 2 # deactivated
	WHEN balance < 0 AND TIMESTAMPDIFF(MONTH,last_pay_time,now()) >= 1 THEN 1 # frozen
ELSE 0 END);

END

DELIMITER

CREATE PROCEDURE tb_employer_charge()
BEGIN
# Step 1 - Charge according to employee category
# Prime: Employer can post up to five jobs. A monthly charge of $50 will be applied.
# Gold: Employer can post as many jobs as he/she wants. A monthly charge of $100 will be applied.

UPDATE TB_EMPLOYER e
SET balance = balance - (
	SELECT month_fee 
	FROM TB_EMPLOYER_CATEGORY c
	WHERE e.category = c.category_id
);

# Step 2 - Auto pay according to auto pay setting and default payment method
# Here we assume the payment account has enough money to pay balance.
# Only the negative balance will be paid.

UPDATE TB_EMPLOYER e,TB_AUTO_PAY a
SET e.balance = 0
WHERE e.default_pm_id IS NOT NULL AND e.auto_pay = a.auto_pay AND a.value = 'Enable' AND e.balance < 0;

# Step 3 - Check status according to balance and pay time
# An account that is negative will be frozen until it is settled and a payment is made
# A suffering account for a year will be deactivated automatically by the system.

UPDATE TB_EMPLOYER
SET status = (CASE
	WHEN balance < 0 AND TIMESTAMPDIFF(MONTH,last_pay_time,now()) >= 12 THEN 2 # deactivated
	WHEN balance < 0 AND TIMESTAMPDIFF(MONTH,last_pay_time,now()) >= 1 THEN 1 # frozen
ELSE 0 END);

END

DELIMITER

