CREATE TABLE Preassigns(
SUID text,
Name text, 
Points integer, 
PRIMARY KEY(SUID));

CREATE TABLE Events(
EventName text, 
Date text, 
Location text, 
Contact text, 
Points integer,
Primary Key(EventName));

CREATE TABLE Participated(
SUID text references Preassigns(SUID) 
	on DELETE CASCADE 
	on UPDATE CASCADE, 
EventName text references Events(EventName) 
	on DELETE CASCADE
	on UPDATE CASCADE, 
Approved integer, 
Primary Key(SUID, EventName));