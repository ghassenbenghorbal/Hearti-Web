const express = require("express");
const app = express();
const http = require("http");
const server = http.createServer(app);
const { Server } = require("socket.io");
const axios = require("axios");
const validBraceletID = 'A1B2C3'
const backendURL = "http://localhost:8000/api"
let token = null
const io = new Server(server, {
  cors: {
    origin: "http://localhost:8000",
    methods: ["GET", "POST"]
  }
});

const getHourFormatted = () => {
  const now = new Date();
  const year = now.getFullYear();
  const month = (now.getMonth() + 1).toString().padStart(2, '0');
  const day = now.getDate().toString().padStart(2, '0');
  const hours = now.getHours().toString().padStart(2, '0');
  const minutes = now.getMinutes().toString().padStart(2, '0');
  const seconds = now.getSeconds().toString().padStart(2, '0');
  const datetime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
  return datetime;
}

const authenticate = (socket, validBraceletID) => {
  const { braceletId } = socket.handshake.auth;
  token = socket.handshake.auth.token
  if (braceletId != validBraceletID) {
    const error = new Error('Invalid bracelet ID');
    socket.emit('connection_error', { message: error.message });
    socket.disconnect(true);
    return;
  }
  
}

const collectUsers = (users, io) => {
  for (let [id, socket] of io.of("/").sockets) {
    users.push({
      channelID: id,
      username: socket.handshake.auth.username,
      id: socket.handshake.auth.id,
    });
  }
}

const setData = (data) => {
  const now = getHourFormatted()

  data.heartRate.x = now
  data.bloodPressure.x = now
  data.temperature.x = now

  const heartRateY = Math.floor(Math.random() * (91 - 80)) + 80;
  const bloodPressureY = Math.floor(Math.random() * (121 - 100)) + 100;
  const temperature = Math.floor(Math.random() * (41 - 36)) + 36;

  data.heartRate.y = heartRateY
  data.bloodPressure.y = bloodPressureY
  data.temperature.y = temperature
}

const saveBloodPressure = async (bloodPressures) => {
  await axios.post(backendURL+"/blood-pressure/store", bloodPressures, {headers: {
    Authorization: `Bearer ${token}`
  }}).catch(err => console.log("Error saving blood pressure"))
}

const saveHeartRate = async (heartRates) => {
  await axios.post(backendURL+"/heart-rate/store", heartRates, {headers: {
    Authorization: `Bearer ${token}`
  }}).catch(err => console.log("Error saving heart rate"))
}

const saveTemperature = async (temperatures) => {
  await axios.post(backendURL+"/temperature/store", temperatures, {headers: {
    Authorization: `Bearer ${token}`
  }}).catch(err => console.log("Error saving temperature"))
}

const formatData = (data, type) => {
  switch(type){
    case 0:
      return {heart_rates: [{
        patient_id: data.patient_id,
        heart_rate: data.heartRate.y,
        time: data.heartRate.x
      }]}
    case 1:
      return {blood_pressures: [{
        patient_id: data.patient_id,
        blood_pressure: data.bloodPressure.y,
        time: data.bloodPressure.x
      }]}
    case 2:
      return {temperatures: [{
        patient_id: data.patient_id,
        temperature: data.temperature.y,
        time: data.temperature.x
      }]}
  }
}

const saveData = async (data) => {
    await saveHeartRate(formatData(data, 0))
    await saveBloodPressure(formatData(data, 1))
    await saveTemperature(formatData(data, 2))
}

const emitData = (data, socket) => {
  setData(data)
  socket.emit("realtimeData", data)
}

app.get("/", (req, res) => {
  res.send("<h1>Hello world</h1>");
});

io.on("connection", async function(socket) {
  // Authentication
  authenticate(socket, validBraceletID)

  console.log("a user connected");

  // Collect all connected users
  const users = [];
  collectUsers(users, io)
  // socket.emit("users", users);

  // Send Data
  let data = {
    patient_id: socket.handshake.auth.id,
    heartRate: {x: null, y: null},
    bloodPressure: {x: null,y: null},
    temperature: {x: null,y: null},
  };
  let heartRateToSave = []
  let bloodPressureToSave = []
  let temperatureToSave = []

  let sendDataInterval = null

  sendDataInterval = setInterval(() => {
    emitData(data, socket)
    heartRateToSave.push(data.heartRate.y)
    bloodPressureToSave.push(data.bloodPressure.y)
    temperatureToSave.push(data.temperature.y)
  }, 1700)

  const saveDataInterval = setInterval(() => {
    clearInterval(sendDataInterval)

    data.heartRate.y = heartRateToSave.reduce((a, b) => a + b, 0) / heartRateToSave.length
    data.bloodPressure.y = bloodPressureToSave.reduce((a, b) => a + b, 0) / bloodPressureToSave.length
    data.temperature.y = temperatureToSave.reduce((a, b) => a + b, 0) / temperatureToSave.length
    saveData(data)

    heartRateToSave = []
    bloodPressureToSave = []
    temperatureToSave = []

    sendDataInterval = setInterval(() => {
      emitData(data, socket)
      heartRateToSave.push(data.heartRate.y)
      bloodPressureToSave.push(data.bloodPressure.y)
      temperatureToSave.push(data.temperature.y)
    }, 1700)


  }, 1000 * 60)
    

  // On disconnect
  socket.on("disconnect", () => {
    clearInterval(sendDataInterval)
    clearInterval(saveDataInterval)
    console.log("user disconnected");
    socket.broadcast.emit("userDisconnected", {
      channelID: socket.id,
      username: socket.username,
    });
  });

});

server.listen(3001, () => {
  console.log("listening on *:3001");
});
