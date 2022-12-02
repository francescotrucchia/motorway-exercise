DROP TABLE IF EXISTS ROUTE;
CREATE TABLE ROUTE (
    id string,
    name string,
    price float,
    PRIMARY KEY (id)
);

DROP TABLE IF EXISTS STATION;
CREATE TABLE STATION (
    id string,
    name string,
    PRIMARY KEY (id)
);

DROP TABLE IF EXISTS ROUTE_STATION;
CREATE TABLE ROUTE_STATION (
    id string,
    route_id string,
    station_id string,
    km int,
    PRIMARY KEY (id)
);

DROP TABLE IF EXISTS DEVICE;
CREATE TABLE DEVICE (
    id string,
    user_id string,
    PRIMARY KEY (id)
);

DROP TABLE IF EXISTS DEVICE_LOG;
CREATE TABLE DEVICE_LOG (
    id string,
    route_id string,
    device_id string,
    enter_station_id string,
    exit_station_id string NULL,
    enter_date DATETIME,
    exit_date DATETIME NULL,
    PRIMARY KEY (id)
);
