CREATE TABLE ROUTE (
    id string,
    name string,
    price float,
    PRIMARY KEY (id)
);

CREATE TABLE ROUTE_STATION (
    id string,
    route_id string,
    station_id string,
    km int,
    PRIMARY KEY (id)
);

CREATE TABLE STATION (
    id string,
    name string,
    PRIMARY KEY (id)
);

CREATE TABLE DEVICE (
    id string,
    user_id string,
    PRIMARY KEY (id)
);

CREATE TABLE DEVICE_LOGS (
    id string,
    route_id string,
    device_id string,
    enter_station_id string,
    exit_station_id string NULL,
    enter_date DATETIME,
    exit_date DATETIME NULL,
    PRIMARY KEY (id)
);

--INSERT INTO ROUTE (1, 'A14', 2);
--INSERT INTO STATION (1, 'Ancona');
--INSERT INTO STATION (2, 'Bologna');
--INSERT INTO STATION (3, 'Rimini');

--INSERT INTO ROUTE_STATION (1, 1, 1, 0);
--INSERT INTO ROUTE_STATION (2, 1, 2, 200);
--INSERT INTO ROUTE_STATION (3, 1, 3, 100);

--INSERT INTO DEVICE(1, 1);
--INSERT INTO DEVICE(2, 1);

--INSERT INTO DEVICE_LOGS(1, 1, 1, 3, 2, '2022-01-01 10:00', '2022-01-01 12:00') // 200-100 = 100 
--INSERT INTO DEVICE_LOGS(2, 1, 1, 1, 2, '2022-01-01 10:00', '2022-01-01 12:00') // 200-0 = 200