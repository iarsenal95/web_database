
INSERT INTO Users VALUES
('sportslover','Paul','Walker','123456','sportslover@hotmail.com'),
('traveler','Rebecca','Travolta','123456','rebt@explorer.org'),
('spacejunkie','Spacey','Bob','123456','bspace@spacejunkies.net');

INSERT INTO Album (title,created,lastupdated,access_type,username) VALUES
('I love sports','2013-01-16','2013-01-16','public','sportslover'),
('I love football','2013-01-16','2013-01-16','public','sportslover'),
('Around the world','2013-01-16','2013-01-16','public','traveler'),
('Cool Space Shot','2013-01-16','2013-01-16','private','spacejunkie');

INSERT INTO Photo VALUES
('./images/football_s1.jpg','jpg','2013-01-16'),
('./images/football_s2.jpg','jpg','2013-01-16'),
('./mages/football_s3.jpg','jpg','2013-01-16'),
('.//images/football_s4.jpg','jpg','2013-01-16'),
('./images/space_EagleNebula.jpg','jpg','2013-01-16'),
('./images/space_GalaxyCollision.jpg','jpg','2013-01-16'),
('./images/space_HelixNebula.jpg','jpg','2013-01-16'),
('./images/space_MilkyWay.jpg','jpg','2013-01-16'),
('./images/space_OrionNebula.jpg','jpg','2013-01-16'),
('./images/sports_s1.jpg','jpg','2013-01-16'),
('./images/sports_s2.jpg','jpg','2013-01-16'),
('./images/sports_s3.jpg','jpg','2013-01-16'),
('./images/sports_s4.jpg','jpg','2013-01-16'),
('./images/sports_s5.jpg','jpg','2013-01-16'),
('./images/sports_s6.jpg','jpg','2013-01-16'),
('./images/sports_s7.jpg','jpg','2013-01-16'),
('./images/sports_s8.jpg','jpg','2013-01-16'),
('./images/world_EiffelTower.jpg','jpg','2013-01-16'),
('./images/world_firenze.jpg','jpg','2013-01-16'),
('./images/world_GreatWall.jpg','jpg','2013-01-16'),
('./images/world_Isfahan.jpg','jpg','2013-01-16'),
('./images/world_Istanbul.jpg','jpg','2013-01-16'),
('./images/world_Persepolis.jpg','jpg','2013-01-16'),
('./images/world_Reykjavik.jpg','jpg','2013-01-16'),
('./images/world_Seoul.jpg','jpg','2013-01-16'),
('./images/world_Stonehenge.jpg','jpg','2013-01-16'),
('./images/world_TajMahal.jpg','jpg','2013-01-16'),
('./images/world_TelAviv.jpg','jpg','2013-01-16'),
('./images/world_Tokyo.jpg','jpg','2013-01-16'),
('./images/world_WashingtonDC.jpg','jpg','2013-01-16');



INSERT INTO Contain (albumid,url,captain,sequencenum) VALUES
(2,'./images/football_s1.jpg','football_s1',1),
(2,'./images/football_s2.jpg','football_s2',2),
(2,'./images/football_s3.jpg','football_s3',3),
(2,'./images/football_s4.jpg','football_s4',4),
(4,'./images/space_EagleNebula.jpg','space_EagleNebula',1),
(4,'./images/space_GalaxyCollision.jpg','space_GalaxyCollision',2),
(4,'./images/space_HelixNebula.jpg','space_HelixNebula',3),
(4,'./images/space_MilkyWay.jpg','space_MilkyWay',4),
(4,'./images/space_OrionNebula.jpg','space_OrionNebula',5),
(1,'./images/sports_s1.jpg','sports_s1',1),
(1,'./images/sports_s2.jpg','sports_s2',2),
(1,'./images/sports_s3.jpg','sports_s3',3),
(1,'./images/sports_s4.jpg','sports_s4',4),
(1,'./images/sports_s5.jpg','sports_s5',5),
(1,'./images/sports_s6.jpg','sports_s6',6),
(1,'./images/sports_s7.jpg','sports_s7',7),
(1,'./images/sports_s8.jpg','sports_s8',8),
(3,'./images/world_EiffelTower.jpg','world_EiffelTower',1),
(3,'./images/world_firenze.jpg','world_firenze',2),
(3,'./images/world_GreatWall.jpg','world_GreatWall',3),
(3,'./images/world_Isfahan.jpg','world_Isfahan',4),
(3,'./images/world_Istanbul.jpg','world_Istanbul',5),
(3,'./images/world_Persepolis.jpg','world_Persepolis',6),
(3,'./images/world_Reykjavik.jpg','world_Reykjavik',7),
(3,'./images/world_Seoul.jpg','world_Seoul',8),
(3,'./images/world_Stonehenge.jpg','world_Stonehenge',9),
(3,'./images/world_TajMahal.jpg','world_TajMahal',10),
(3,'./images/world_TelAviv.jpg','world_TelAviv',11),
(3,'./images/world_Tokyo.jpg','world_Tokyo',12),
(3,'./images/world_WashingtonDC.jpg','world_WashingtonDC',13);

INSERT INTO AlbumAccess VALUES
(1,'sportslover'),(2,'sportslover'),(3,'traveler'),(4,'spacejunkie');


