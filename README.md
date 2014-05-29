#Weather Evaluation Terminal - Webinterface
The Wether Evaluation Tool Webinterface is a simple server sided overview of the weather data collected by the hardware associated with this project. The Interface will receive all its data via its api directory, directly sent to its index file with the respective URL-Parameters.

## Basic concept
1. The Hardware will call the API link (`192.168.1.x/api/time/data1/...`) every 15 minutes, giving it the parameters it needs.
2. The API gets the data and decided what to do with them. The data will either be written in an existing JSON file in the `/data/` directory or the API will generate a new JSON file to save the data. The API will create and handle a sepparate JSON file for every month.
3. The API will return the string `true` if the data has been saved successfully. It will display an error to the user if the data changed in a weird way (for example, temparature +20°C in 15min) or the API discovered an error.
4. The Frontend is an Ember based application that allows the user to see the current weather state and all the collected data divided into sepparate monts. The Frontend does not include an authentification system so everyone who knows its address will be able to see the weather informations.

## Libraries and Frameworks used
- jQuery for faster frontend development
- Ember.js for simple and powerful frontend

## Precompiler instructions
```
coffee --join assets/compiled/application.js --compile assets/javascript/*
```