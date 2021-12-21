CREATE TABLE TB_ADMIN_ACCOUNT (
  name varchar(45) NOT NULL,
  password varchar(45) NOT NULL,
  email varchar(45) NOT NULL,
  PRIMARY KEY (name)
) ;

CREATE TABLE TB_ADMIN_LOG (
  log_id int NOT NULL AUTO_INCREMENT,
  log_time datetime DEFAULT NULL,
  activity varchar(300) DEFAULT NULL,
  PRIMARY KEY (log_id)
);

CREATE TABLE TB_APPLICATION_STATUS (
  status_id int NOT NULL,
  status varchar(45) NOT NULL,
  PRIMARY KEY (status_id),
  UNIQUE KEY status_UNIQUE (status)
);

CREATE TABLE TB_EMPLOYEE_CATEGORY (
  category_id int NOT NULL,
  category varchar(45) NOT NULL,
  month_fee int NOT NULL,
  limit int NOT NULL,
  PRIMARY KEY (category_id),
  UNIQUE KEY category_UNIQUE (category)
);

CREATE TABLE TB_EMPLOYER_CATEGORY (
  category_id int NOT NULL,
  category varchar(45) NOT NULL,
  month_fee int NOT NULL,
  limit int NOT NULL,
  PRIMARY KEY (category_id),
  UNIQUE KEY category_UNIQUE (category)
);

CREATE TABLE TB_AUTO_PAY (
  auto_pay int NOT NULL,
  value varchar(45) NOT NULL,
  PRIMARY KEY (auto_pay),
  UNIQUE KEY value_UNIQUE (value)
);

CREATE TABLE TB_PAYMENT_METHOD (
  method_id int NOT NULL,
  method_name varchar(45) NOT NULL,
  PRIMARY KEY (method_id),
  UNIQUE KEY method_name_UNIQUE (method_name)
);

CREATE TABLE TB_EMPLOYEE (
  employee_id int NOT NULL AUTO_INCREMENT,
  name varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  password varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  first_name varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  last_name varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  email varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  category int DEFAULT NULL,
  status int DEFAULT NULL,
  balance int( DEFAULT NULL,
  resume varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  last_pay_time datetime DEFAULT NULL,
  auto_pay int DEFAULT NULL,
  default_pm_id int DEFAULT NULL,
  PRIMARY KEY (employee_id),
  UNIQUE KEY name_UNIQUE (name),
  KEY employee_category_idx (category),
  KEY employee_auto_pay_idx (auto_pay),
  CONSTRAINT employee_auto_pay FOREIGN KEY (auto_pay) REFERENCES TB_AUTO_PAY (auto_pay) ON DELETE RESTRICT,
  CONSTRAINT employee_category FOREIGN KEY (category) REFERENCES TB_EMPLOYEE_CATEGORY (category_id)
) AUTO_INCREMENT=10000;

CREATE TABLE TB_EMPLOYER (
  employer_id int NOT NULL AUTO_INCREMENT,
  name varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  password varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  company varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  email varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  category int DEFAULT NULL,
  status int DEFAULT NULL,
  balance int DEFAULT NULL,
  last_pay_time datetime DEFAULT NULL,
  auto_pay int DEFAULT NULL,
  default_pm_id int DEFAULT NULL,
  PRIMARY KEY (employer_id),
  KEY employer_catagory_idx (category),
  KEY employer_auto_pay_idx (auto_pay),
  CONSTRAINT employer_auto_pay FOREIGN KEY (auto_pay) REFERENCES TB_AUTO_PAY (auto_pay),
  CONSTRAINT employer_catagory FOREIGN KEY (category) REFERENCES TB_EMPLOYER_CATEGORY (category_id)
) AUTO_INCREMENT=100;


CREATE TABLE TB_JOB_CATEGORY (
  job_cid int NOT NULL AUTO_INCREMENT,
  employer_id int NOT NULL,
  category varchar(45) NOT NULL,
  PRIMARY KEY (job_cid),
  KEY job_category (employer_id,category)
);

CREATE TABLE TB_JOB (
  job_id int NOT NULL AUTO_INCREMENT,
  employer_id int NOT NULL,
  title varchar(45) NOT NULL,
  needed int NOT NULL,
  job_cid int NOT NULL,
  description varchar(50) DEFAULT NULL,
  status int DEFAULT NULL,
  post_time datetime DEFAULT NULL,
  PRIMARY KEY (job_id),
  KEY job_cid_idx (job_cid),
  KEY employer_id_idx (employer_id),
  CONSTRAINT job_cid FOREIGN KEY (job_cid) REFERENCES TB_JOB_CATEGORY (job_cid) ON DELETE RESTRICT,
  CONSTRAINT job_employer FOREIGN KEY (employer_id) REFERENCES TB_EMPLOYER (employer_id) ON DELETE RESTRICT
) AUTO_INCREMENT=100000;

CREATE TABLE TB_PAYMENT_EMPLOYEE (
  pm_id int NOT NULL AUTO_INCREMENT,
  employee_id int NOT NULL,
  pay_method int DEFAULT NULL,
  account_name varchar(45) DEFAULT NULL,
  account_number varchar(45) DEFAULT NULL,
  PRIMARY KEY (pm_id),
  KEY pm_employee_idx (employee_id),
  CONSTRAINT pm_employee FOREIGN KEY (employee_id) REFERENCES TB_EMPLOYEE (employee_id) ON DELETE CASCADE
) AUTO_INCREMENT=100000;

CREATE TABLE TB_PAYMENT_EMPLOYER (
  pm_id int NOT NULL AUTO_INCREMENT,
  employer_id int NOT NULL,
  pay_method int DEFAULT NULL,
  account_name varchar(45) DEFAULT NULL,
  account_number varchar(45) DEFAULT NULL,
  PRIMARY KEY (pm_id),
  KEY pm_employer_idx (employer_id),
  CONSTRAINT pm_employer FOREIGN KEY (employer_id) REFERENCES TB_EMPLOYER (employer_id)
) AUTO_INCREMENT=1000;

CREATE TABLE TB_RECRUITER (
  recruiter_id int NOT NULL AUTO_INCREMENT,
  employer_id int NOT NULL,
  name varchar(45) NOT NULL,
  password varchar(45) DEFAULT NULL,
  first_name varchar(45) DEFAULT NULL,
  last_name varchar(45) DEFAULT NULL,
  email varchar(45) DEFAULT NULL,
  PRIMARY KEY (recruiter_id),
  KEY recruiter_employer_idx (employer_id),
  CONSTRAINT recruiter_employer FOREIGN KEY (employer_id) REFERENCES TB_EMPLOYER (employer_id) ON DELETE RESTRICT
) AUTO_INCREMENT=1000;

CREATE TABLE TB_APPLICATION (
  app_id int NOT NULL AUTO_INCREMENT,
  job_id int NOT NULL,
  employee_id int NOT NULL,
  status_id int DEFAULT NULL,
  apply_time datetime DEFAULT NULL,
  PRIMARY KEY (app_id),
  KEY app_job_idx (job_id),
  KEY app_employee_idx (employee_id),
  KEY app_status_idx (status_id),
  CONSTRAINT app_employee FOREIGN KEY (employee_id) REFERENCES TB_EMPLOYEE (employee_id) ON DELETE CASCADE,
  CONSTRAINT app_job FOREIGN KEY (job_id) REFERENCES TB_JOB (job_id) ON DELETE RESTRICT,
  CONSTRAINT app_status FOREIGN KEY (status_id) REFERENCES TB_APPLICATION_STATUS (status_id) ON DELETE RESTRICT
) AUTO_INCREMENT=1000000;

ALTER TABLE TB_EMPLOYEE 
ADD INDEX employee_pm_id_idx (default_pm_id ASC) VISIBLE;

ALTER TABLE TB_EMPLOYEE 
ADD CONSTRAINT employee_pm_id
  FOREIGN KEY (default_pm_id)
  REFERENCES TB_PAYMENT_EMPLOYEE (pm_id)
  ON DELETE SET NULL
  ON UPDATE NO ACTION;

ALTER TABLE TB_EMPLOYER 
ADD INDEX employer_pm_id_idx (default_pm_id ASC) VISIBLE;

ALTER TABLE TB_EMPLOYER 
ADD CONSTRAINT employer_pm_id
  FOREIGN KEY (default_pm_id)
  REFERENCES TB_PAYMENT_EMPLOYER (pm_id)
  ON DELETE SET NULL
  ON UPDATE NO ACTION;
  


