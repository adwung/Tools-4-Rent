
-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_fa17_team072`; 
SET default_storage_engine=InnoDB;
-- SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_fa17_team072 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_fa17_team072;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_fa17_team072`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

-- Tables 


CREATE TABLE `User` (
  username varchar(250) NOT NULL,
  email varchar(250) NOT NULL,
  password varchar(60) NOT NULL,
  first_name varchar(100) NOT NULL,
  middle_name varchar(100) DEFAULT NULL,
  last_name varchar(100) NOT NULL,
  type varchar(20) NOT NULL,
  PRIMARY KEY (username),
  UNIQUE KEY (email)
);

CREATE TABLE RentalCustomer (
  rcID int(16) NOT NULL AUTO_INCREMENT,
  username varchar(250) NOT NULL,
  street varchar(250) NOT NULL,
  city varchar(250) NOT NULL,
  state varchar(250) NOT NULL,
  zip varchar(250) NOT NULL,
  PRIMARY KEY (rcID), 
  UNIQUE KEY (username)
);

CREATE TABLE Clerk (
  empNO int(16) NOT NULL AUTO_INCREMENT,
  username varchar(250) NOT NULL,
  hire_date date NOT NULL,
  PRIMARY KEY (empNO),
  UNIQUE KEY (username)
);

CREATE TABLE CustomerPhoneNumber (
  username varchar(250) NOT NULL,
  primary_phone varchar(250) NOT NULL,
  home_phone_number int(15) NOT NULL,
  work_phone_number int(15) NOT NULL,
  cell_phone_number int(15) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE Reservation (
  rNO int(16) NOT NULL AUTO_INCREMENT,
  rcID int(16) NOT NULL,
  start_date date NOT NULL,
  end_date date NOT NULL,
  deposit_price decimal(6,2) NOT NULL,
  rental_price decimal(6,2) NOT NULL,
  pickupClerkENO int(16),
  dropoffClerkENO int(16),
  PRIMARY KEY (rNO)
);

CREATE TABLE Holds (
  rNO int(16) NOT NULL,
  toolID int(16) NOT NULL,
  PRIMARY KEY (rNO,toolID)
);

CREATE TABLE Tool (
  toolID int(16) NOT NULL AUTO_INCREMENT,
  empNO int(16) NOT NULL,
  category varchar(250) NOT NULL,
  sub_type varchar(250) NOT NULL,
  sub_option varchar(250) NOT NULL,
  width decimal(6,2) NOT NULL,
  width_unit varchar(10) NOT NULL,
  length decimal(6,2) NOT NULL,
  length_unit varchar(10) NOT NULL,
  manufacturer varchar(250) NOT NULL,
  weight decimal (6,2) NOT NULL,
  power_source varchar(250),
  material varchar(250) NOT NULL,
  deposit_price decimal(6,2) NOT NULL,
  rental_price decimal(6,2) NOT NULL,
  rental_count decimal(6,2) NOT NULL,
  purchase_price decimal(6,2) NOT NULL,
  short_description varchar(1000) NOT NULL,
  full_description varchar(10000) NOT NULL,
  status varchar(250) NOT NULL,
  statusdate date NOT NULL,
  PRIMARY KEY (toolID)
);

CREATE TABLE ServiceOrder(
  serviceID int(16) NOT NULL AUTO_INCREMENT,
  toolID int(16) NOT NULL,
  empNO int(16) NOT NULL,
  status varchar(250) NOT NULL,
  repair_cost decimal(6,2) NOT NULL,
  PRIMARY KEY (serviceID),
  UNIQUE KEY (toolID),
  UNIQUE KEY (empNO)
);

CREATE TABLE SalesOrder(
  saleID int(16) NOT NULL AUTO_INCREMENT,
  rcID int(16) NOT NULL,
  toolID int(16) NOT NULL,
  empNO int(16) NOT NULL,
  sale_date date NOT NULL,
  status varchar(250) NOT NULL,
  sale_price decimal(6,2) NOT NULL,
  PRIMARY KEY (saleID),
  UNIQUE KEY (rcID),
  UNIQUE KEY (toolID),
  UNIQUE KEY (empNO)
);

CREATE TABLE CreditCard(
  username varchar(250) NOT NULL,
  name varchar(250) NOT NULL,
  cvc int(3) NOT NULL,
  expiration_month int(2) NOT NULL,
  expiration_year int(2) NOT NULL,
  cc_number int(20) NOT NULL,
  PRIMARY KEY (username),
  UNIQUE KEY (cc_number)
);

CREATE TABLE HandTool (
  toolID int(16) NOT NULL AUTO_INCREMENT,
  screw_size decimal(6,2) DEFAULT NULL,
  sae_size decimal(6,2) DEFAULT NULL,
  deep_socket decimal(6,2) DEFAULT NULL,
  drive_size decimal(6,2) DEFAULT NULL,
  adjustable tinyint(1) DEFAULT NULL,
  gauge_rating decimal(6,2) DEFAULT NULL,
  capacity decimal(6,2) DEFAULT NULL,
  anti_vibration tinyint(1) DEFAULT NULL,
  PRIMARY KEY (toolID)
);

CREATE TABLE GardenTool (
  toolID int(16) NOT NULL AUTO_INCREMENT,
  blade_length decimal(6,2) DEFAULT NULL,
  blade_width decimal(6,2) DEFAULT NULL,
  blade_material varchar(250) DEFAULT NULL,
  tine_count int(16) DEFAULT NULL,
  bin_material varchar(250) DEFAULT NULL,
  bin_volume decimal(6,2) DEFAULT NULL,
  wheel_count int(2) DEFAULT NULL,
  head_weight decimal(6,2) DEFAULT NULL,
  handle_material varchar(250) DEFAULT NULL,
  PRIMARY KEY (toolID)
);

CREATE TABLE LadderTool (
  toolID int(16) NOT NULL AUTO_INCREMENT,
  step_count int(2) DEFAULT NULL,
  weight_capacity decimal(6,2) DEFAULT NULL,
  rubber_feet tinyint(1) DEFAULT NULL,
  pail_shelf tinyint(1) DEFAULT NULL,
  PRIMARY KEY (toolID)
);

CREATE TABLE PowerTool (
  toolID int(16) NOT NULL AUTO_INCREMENT,
  volt_rating int(6) DEFAULT NULL,
  amp_rating int(6) DEFAULT NULL,
  adjustable_clutch tinyint(1) DEFAULT NULL,
  min_torque_rating int(6) DEFAULT NULL,
  max_torque_rating int(6) DEFAULT NULL,
  blade_size decimal(6,2) DEFAULT NULL,
  dust_bag decimal(6,2) DEFAULT NULL,
  tank_size decimal(6,2) DEFAULT NULL,
  pressure_rating decimal(6,2) DEFAULT NULL,
  motor_rating decimal(6,2) DEFAULT NULL,
  drum_size decimal(6,2) DEFAULT NULL,
  power_rating decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (toolID)
);

CREATE TABLE PowerToolAccessories (
  toolID int(16) NOT NULL AUTO_INCREMENT,
  sub_type varchar(250) NOT NULL,
  sub_option varchar(250) NOT NULL,
  quantity int(6) NOT NULL,
  battery_type varchar(250) NOT NULL,
  description varchar(10000) NOT NULL,
  PRIMARY KEY (toolID),
  UNIQUE KEY (sub_type)
);

-- Constraints   Foreign Keys: FK_ChildTable_childColumn_ParentTable_parentColumn

ALTER TABLE Clerk
  ADD CONSTRAINT fk_Clerk_username_User_username FOREIGN KEY (username) REFERENCES User (username);
  
ALTER TABLE RentalCustomer
  ADD CONSTRAINT fk_RentalCustomer_username_User_username FOREIGN KEY (username) REFERENCES User (username);

ALTER TABLE CustomerPhoneNumber
  ADD CONSTRAINT fk_CustomerPhoneNumber_username_User_username FOREIGN KEY (username) REFERENCES RentalCustomer(username);

ALTER TABLE Reservation
  ADD CONSTRAINT fk_Reservation_rcID_RentalCustomer_rcID FOREIGN KEY (rcID) REFERENCES RentalCustomer(rcID);

ALTER TABLE Reservation
  ADD CONSTRAINT fk_Reservation_pickupClerkENO_Clerk_empNO FOREIGN KEY (pickupClerkENO) REFERENCES Clerk(empNO);

ALTER TABLE Reservation
  ADD CONSTRAINT fk_Reservation_dropoffClerkENO_Clerk_empNO FOREIGN KEY (dropoffClerkENO) REFERENCES Clerk(empNo);

ALTER TABLE Holds
  ADD CONSTRAINT fk_Holds_rNO_Reservation_rNO FOREIGN KEY (rNO) REFERENCES Reservation(rNO);

ALTER TABLE Holds
  ADD CONSTRAINT fk_Holds_toolID_Tool_toolID FOREIGN KEY (toolID) REFERENCES Tool(toolID);

ALTER TABLE Tool
  ADD CONSTRAINT fk_Tool_empNO_Clerk_empNO FOREIGN KEY (empNO) REFERENCES Clerk(empNO);

ALTER TABLE ServiceOrder
  ADD CONSTRAINT fk_ServiceOrder_toolID_Tool_toolID FOREIGN KEY (toolID) REFERENCES Tool(toolID);

ALTER TABLE ServiceOrder
  ADD CONSTRAINT fk_ServiceOrder_empNO_Clerk_empNO FOREIGN KEY (empNO) REFERENCES Clerk(empNO);

ALTER TABLE SalesOrder
  ADD CONSTRAINT fk_SalesOrder_empNO_Clerk_empNO FOREIGN KEY (empNO) REFERENCES Clerk(empNO);

ALTER TABLE SalesOrder
  ADD CONSTRAINT fk_SalesOrder_rcID_RentalCustomer_rcID FOREIGN KEY (rcID) REFERENCES RentalCustomer(rcID);

ALTER TABLE SalesOrder
  ADD CONSTRAINT fk_SalesOrder_toolID_Tool_toolID FOREIGN KEY (toolID) REFERENCES Tool(toolID);

ALTER TABLE CreditCard
  ADD CONSTRAINT fk_CreditCard_username_RentalCustomer_username FOREIGN KEY (username) REFERENCES RentalCustomer(username);

ALTER TABLE HandTool
  ADD CONSTRAINT fk_HandTool_toolID_Tool_toolID FOREIGN KEY (toolID) REFERENCES Tool(toolID);

ALTER TABLE GardenTool
  ADD CONSTRAINT fk_GardenTool_toolID_Tool_toolID FOREIGN KEY (toolID) REFERENCES Tool(toolID);

ALTER TABLE LadderTool
  ADD CONSTRAINT fk_LadderTool_toolID_Tool_toolID FOREIGN KEY (toolID) REFERENCES Tool(toolID);

ALTER TABLE PowerTool
  ADD CONSTRAINT fk_PowerTool_toolID_Tool_toolID FOREIGN KEY (toolID) REFERENCES Tool(toolID);

ALTER TABLE PowerToolAccessories
  ADD CONSTRAINT fk_PowerToolAccessories_toolID_Tool_toolID FOREIGN KEY (toolID) REFERENCES Tool(toolID);
