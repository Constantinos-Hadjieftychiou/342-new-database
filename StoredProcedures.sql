-- Stored Procedure to Authenticate a User
CREATE PROCEDURE AuthenticateUser
    @username VARCHAR(100),
    @password VARCHAR(255)
AS
BEGIN
    -- Check if the user exists with the provided username and password
    IF EXISTS (
        SELECT 1
    FROM Users
    WHERE username = @username AND password = @password
    )
    BEGIN
        -- User authenticated successfully
        SELECT 'Authentication successful' AS Message, 1 AS Status;
    END
    ELSE
    BEGIN
        -- Authentication failed
        SELECT 'Invalid username or password' AS Message, 0 AS Status;
    END
END;
ALTER PROCEDURE RegisterUser
    @birth_date DATE,
    @identity_number VARCHAR(100),
    @is_active BIT,
    @registration_date DATE,
    @password_plaintext NVARCHAR(255),
    @username VARCHAR(100),
    @gender CHAR(1),
    @first_name VARCHAR(100),
    @last_name VARCHAR(100),
    @user_type VARCHAR(20),
    @valid_legalization BIT = NULL
AS
BEGIN
    BEGIN TRY
        SET NOCOUNT ON;
        BEGIN TRANSACTION;

        -- Check for duplicate identity number
        IF EXISTS (SELECT 1 FROM Users WHERE identity_number = @identity_number)
        BEGIN
            RAISERROR('The identity number already exists.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END

        -- Check for duplicate username
        IF EXISTS (SELECT 1 FROM Users WHERE username = @username)
        BEGIN
            RAISERROR('The username already exists.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END

        -- Encrypt the password
        DECLARE @encrypted_password NVARCHAR(255);
        SET @encrypted_password = CONVERT(NVARCHAR(255), HASHBYTES('SHA2_256', @password_plaintext), 1);

        -- Insert the user
        INSERT INTO Users
            (birth_date, identity_number, is_active, registration_date, password, username, gender, first_name, last_name, [user_type], valid_legalization)
        VALUES
            (@birth_date, @identity_number, @is_active, @registration_date, @encrypted_password, @username, @gender, @first_name, @last_name, @user_type, @valid_legalization);

        -- Commit the transaction
        COMMIT TRANSACTION;

    END TRY
    BEGIN CATCH
        -- Rollback transaction on error
        ROLLBACK TRANSACTION;
    END CATCH
END;
