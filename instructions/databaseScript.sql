
CREATE TABLE Users (
  idUser INT UNSIGNED NOT NULL AUTO_INCREMENT,
  userLogin VARCHAR(255) NOT NULL,
  userPass VARCHAR(255) NOT NULL,
  userName VARCHAR(255) NOT NULL,
  userTotal DECIMAL(10, 2) NULL,
  PRIMARY KEY (idUser)
);

CREATE TABLE DailyTransactions (
  idDT INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Users_idUser INT UNSIGNED NOT NULL,
  DTname VARCHAR(255) NOT NULL,
  DTdate DATE NOT NULL,
  DTamount DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (idDT),
  INDEX FK_Users_DailyTransactions (Users_idUser),
  FOREIGN KEY (Users_idUser)
    REFERENCES Users (idUser)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE Savings (
  idSaving INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Users_idUser INT UNSIGNED NOT NULL,
  savingAmount DECIMAL(10, 2) NOT NULL,
  savingDate DATE NOT NULL,
  isActualSaving TINYINT(1) NOT NULL,
  savingCategory varchar(100) NOT NULL,  
  PRIMARY KEY (idSaving),
  INDEX FK_Users_Savings (Users_idUser),
  FOREIGN KEY (Users_idUser)
    REFERENCES Users (idUser)
    ON DELETE CASCADE
    ON UPDATE CASCADE

);

CREATE TABLE Income (
  idIncome INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Users_idUser INT UNSIGNED NOT NULL,
  incomeAmount DECIMAL(10, 2) NOT NULL,
  incomeName VARCHAR(255) NOT NULL,
  incomeDate DATE NOT NULL,
  PRIMARY KEY (idIncome),
  INDEX FK_Users_Income (Users_idUser),
  FOREIGN KEY (Users_idUser)
    REFERENCES Users (idUser)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE Charges (
  idCharge INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Users_idUser INT UNSIGNED NOT NULL,
  chargeAmount DECIMAL(10, 2) NOT NULL,
  chargeAddDate DATE NOT NULL,
  chargeExpiryDate DATE NULL,
  chargeCategory varchar(100) NOT NULL,  
  PRIMARY KEY (idCharge),
  INDEX FK_Users_Charges (Users_idUser),
  FOREIGN KEY (Users_idUser)
    REFERENCES Users (idUser)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE Budget (
  idBudget INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Users_idUser INT UNSIGNED NOT NULL,
  budgetAddDate DATE NOT NULL,
  budgetAmount DECIMAL(10, 2) NOT NULL,
  budgetWhere VARCHAR(255) NULL,
  budgetCategory varchar(100) NOT NULL,  
  PRIMARY KEY (idBudget, Users_idUser),  
  INDEX FK_Users_Budget (Users_idUser),
  FOREIGN KEY (Users_idUser)
    REFERENCES Users (idUser)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE BudgetHistory (
  idBudgetHistory INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Budget_idBudget INT UNSIGNED NOT NULL,
  Users_idUser INT UNSIGNED NOT NULL,
  BHamount DECIMAL(10, 2) NOT NULL,
  BHdate DATE NOT NULL,
  PRIMARY KEY (idBudgetHistory),
  INDEX FK_BudgetHistory_Budget (Budget_idBudget, Users_idUser),
  FOREIGN KEY (Budget_idBudget, Users_idUser)
    REFERENCES Budget (idBudget, Users_idUser)  
    ON DELETE CASCADE
    ON UPDATE CASCADE
);