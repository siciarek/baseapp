@ECHO OFF

set CONFIG=%~dp0dumpdb.cnf
REM Read data from configuration file:

FOR /f "delims=" %%x IN (%CONFIG%) DO (ECHO %%x)
FOR /f "delims=" %%x IN (%CONFIG%) DO (SET "%%x")

ECHO.
ECHO MAKING TIDY:

DEL /S *.sql

ECHO.
ECHO CREATE QUERY FOR LOCAL DATABASE CREATION:

ECHO SET NAMES utf8; > createdb.sql
ECHO. >> createdb.sql
ECHO use information_schema; >> createdb.sql
ECHO. >> createdb.sql
ECHO DROP DATABASE %TARGET_DB_NAME%; >> createdb.sql
ECHO CREATE DATABASE %TARGET_DB_NAME%; >> createdb.sql
ECHO. >> createdb.sql
ECHO use %TARGET_DB_NAME%; >> createdb.sql
ECHO. >> createdb.sql
ECHO \. %TARGET_DIR%/%TARGET_DB_NAME%.sql >> createdb.sql
ECHO. >> createdb.sql

ECHO.
ECHO # 1/4 DUMP DATABASE ON REMOTE SERVER:
ECHO.
%SSH_CLI% -P %SOURCE_PORT% %SOURCE_USER%@%SOURCE_HOST% "mysqldump -r%SOURCE_DIR%/%SOURCE_DB_NAME%.sql -h%SOURCE_DB_HOST% -u%SOURCE_DB_USER% -p%SOURCE_DB_PASS% %SOURCE_DB_NAME%"

ECHO.
ECHO # 2/4 DOWNLOAD DATABASE DUMP FROM REMOTE SERVER TO LOCAL DIRECTORY:
ECHO.
%SCP_CLI% -P %SOURCE_PORT% -C %SOURCE_USER%@%SOURCE_HOST%:%SOURCE_DIR%/%SOURCE_DB_NAME%.sql %TARGET_DIR%/%TARGET_DB_NAME%.sql

ECHO.
ECHO # 3/4 MAKE TIDY ON REMOTE SERVER:
ECHO.
%SSH_CLI% -P %SOURCE_PORT% %SOURCE_USER%@%SOURCE_HOST% "rm -rfv %SOURCE_DIR%/%SOURCE_DB_NAME%.sql"

ECHO.
ECHO # 4/4 RUN QUERY ON LOCAL DATABASE SERVER:
ECHO.
%MYSQL_CLI% -v -v -v --user=%TARGET_DB_USER% --pass=%TARGET_DB_PASS% --host=%TARGET_DB_HOST% < %TARGET_DIR%/createdb.sql | grep "Query OK"

REM Extra logic ie. Uploading files
IF EXIST extra.bat (CALL extra.bat)

ECHO.
ECHO DONE.
ECHO.
