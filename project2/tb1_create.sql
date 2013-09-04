DROP TABLE Users;
DROP TABLE Album;
DROP TABLE Photo;
DROP TABLE Com;
DROP TABLE Contain;
DROP TABLE AlbumAccess;
DROP TABLE RootUsers;

CREATE TABLE Users(
    username VARCHAR(20),
    firstname VARCHAR(20) NOT NULL,
    lastname VARCHAR(20) NOT NULL,
    pass_word VARCHAR(20) NOT NULL,
    email VARCHAR(40) NOT NULL,
    PRIMARY KEY(username));
    
CREATE TABLE Album(
    albumid int NOT NULL auto_increment,
    title VARCHAR(50),
    created DATE,
    lastupdated DATE,
    access_type VARCHAR(10), 
    username VARCHAR(20),
    
    PRIMARY KEY(albumid));

CREATE TABLE Photo(
    url VARCHAR(255),
    format CHAR(3),
    date_taken DATE,
    PRIMARY KEY(url));

CREATE TABLE Contain(
    albumid int NOT NULL,
    url VARCHAR(255),
    captain VARCHAR(255),
    sequencenum int NOT NULL,
    PRIMARY KEY(albumid,url),
	FOREIGN KEY(albumid) REFERENCES Album(albumid) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(url) REFERENCES Photo(url) ON DELETE CASCADE ON UPDATE CASCADE);
    
CREATE TABLE AlbumAccess(
    albumid INTEGER,
    username VARCHAR(20),
    PRIMARY KEY(albumid,username),
	FOREIGN KEY(albumid) REFERENCES Album(albumid) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY(username) REFERENCES Users(username) ON DELETE CASCADE ON UPDATE CASCADE);
	
CREATE TABLE Com(
	username VARCHAR(20),
	url VARCHAR(255),
	comments VARCHAR(255),
	floors INTEGER,
	FOREIGN KEY (url) REFERENCES Photo(url) ON DELETE CASCADE ON UPDATE CASCADE);
	
CREATE TABLE RootUsers(
	username VARCHAR(20),
	FOREIGN KEY (username) REFERENCES Users(username) ON DELETE CASCADE ON UPDATE CASCADE);	
        
    

