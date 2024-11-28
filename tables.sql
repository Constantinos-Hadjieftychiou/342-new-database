-- Create the Users table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[Users]') AND type in (N'U'))
DROP TABLE [chadji10].[Users]
GO
CREATE TABLE Users
(
    user_id INT NOT NULL PRIMARY KEY,
    birth_date DATE NOT NULL ,
    identity_number VARCHAR(100) NOT NULL UNIQUE,
    is_active BIT NOT NULL CHECK (is_active IN (0, 1)),
    registration_date DATE NOT NULL CHECK (registration_date <= GETDATE()),
    password VARCHAR(255) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    gender CHAR(1) NOT NULL CHECK (gender IN ('M', 'F','m','f')),
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    valid_legalization BIT NULL CHECK (valid_legalization IN (0, 1))
);

-- Create the UserType table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[UserType]') AND type in (N'U'))
DROP TABLE [chadji10].[UserType]
GO
CREATE TABLE UserType
(
    user_type_id INT NOT NULL PRIMARY KEY,
    [type] VARCHAR(20) NOT NULL CHECK ([type] IN ('FY', 'LT', 'AA','AX')),
    [description] TEXT NULL,
    user_id INT NULL UNIQUE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Create the Application table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[Application]') AND [type] in (N'U'))
DROP TABLE [chadji10].[Application]
GO

CREATE TABLE Application
(
    application_id INT NOT NULL PRIMARY KEY,
    submission_date DATE NOT NULL CHECK (submission_date <= GETDATE()),
    is_active BIT NOT NULL CHECK (is_active IN (0, 1))
);

-- Create the Applicant table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[Applicant]') AND type in (N'U'))
DROP TABLE [chadji10].[Applicant]
GO
CREATE TABLE Applicant
(
    user_id INT NOT NULL,
    application_id INT NOT NULL,
    PRIMARY KEY (user_id, application_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (application_id) REFERENCES Application(application_id) ON DELETE CASCADE
);

-- Create the NewVehicle table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[NewVehicle]') AND [type] in (N'U'))
DROP TABLE [chadji10].[NewVehicle]
GO
CREATE TABLE NewVehicle
(
    new_vehicle_id INT NOT NULL PRIMARY KEY,
    category VARCHAR(100) NOT NULL,
    [description] TEXT NULL,
    cc INT NOT NULL CHECK (cc > 49),
    brand VARCHAR(100) NOT NULL,
    manufacture_date DATE NOT NULL,
    application_id INT NOT NULL,
    FOREIGN KEY (application_id) REFERENCES Application(application_id) ON DELETE CASCADE
);

-- Create the Document table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[Document]') AND [type] in (N'U'))
DROP TABLE [chadji10].[Document]
GO
CREATE TABLE Document
(
    document_id INT NOT NULL PRIMARY KEY,
    [description] TEXT NOT NULL,
    [size] INT NOT NULL CHECK (size > 0),
    upload_date DATE NOT NULL CHECK (upload_date <= GETDATE()),
    file_path VARCHAR(255) NOT NULL,
    [type] VARCHAR(50) NOT NULL,
    withdrawal FLOAT NOT NULL CHECK (withdrawal >= 0),
    application_id INT NOT NULL,
    FOREIGN KEY (application_id) REFERENCES Application(application_id) ON DELETE CASCADE
);

-- Create the UsedFor table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[UsedFor]') AND type in (N'U'))
DROP TABLE [chadji10].[UsedFor]
GO
CREATE TABLE UsedFor
(
    application_id INT NOT NULL PRIMARY KEY,
    FOREIGN KEY (application_id) REFERENCES Application(application_id) ON DELETE CASCADE
);

-- Create the CategoryOfApplication table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[CategoryOfApplication]') AND type in (N'U'))
DROP TABLE [chadji10].[CategoryOfApplication]
GO
CREATE TABLE CategoryOfApplication
(
    category_of_application_id INT NOT NULL PRIMARY KEY,
    [name] VARCHAR(255) NOT NULL,
    [type] VARCHAR(100) NOT NULL,
    [description] TEXT NULL,
    value_of_grant DECIMAL(10, 2) NOT NULL CHECK (value_of_grant >= 0),
    application_id INT NOT NULL,
    FOREIGN KEY (application_id) REFERENCES Application(application_id) ON DELETE CASCADE
);

-- Create the Criteria table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[Criteria]') AND [type] in (N'U'))
DROP TABLE [chadji10].[Criteria]
GO
CREATE TABLE Criteria
(
    [type] VARCHAR(100) NOT NULL,
    [description] TEXT NULL,
    category_of_application_id INT NOT NULL,
    PRIMARY KEY (type, category_of_application_id),
    FOREIGN KEY (category_of_application_id) REFERENCES CategoryOfApplication(category_of_application_id) ON DELETE CASCADE
);

-- Create the ApplicationStatusHistory table
IF  EXISTS (SELECT *
FROM sys.objects
WHERE object_id = OBJECT_ID(N'[chadji10].[ApplicationStatusHistory]') AND type in (N'U'))
DROP TABLE [chadji10].[ApplicationStatusHistory]
GO
CREATE TABLE ApplicationStatusHistory
(
    history_id INT NOT NULL PRIMARY KEY,
    change_date DATE NOT NULL CHECK (change_date <= GETDATE()),
    justification TEXT NULL,
    [status] VARCHAR(50) NOT NULL CHECK (status IN ('Pending', 'Approved', 'Rejected')),
    application_id INT NOT NULL,
    FOREIGN KEY (application_id) REFERENCES Application(application_id) ON DELETE CASCADE
);


