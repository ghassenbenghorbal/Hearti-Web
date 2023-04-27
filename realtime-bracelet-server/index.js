const express = require("express");
const app = express();
const http = require("http");
const server = http.createServer(app);
const { Server } = require("socket.io");
const validBraceletID = 'A1B2C3'
const io = new Server(server, {
  cors: {
    origin: "http://localhost:8000",
    methods: ["GET", "POST"]
  }
});

const getHourFormatted = () => {
  var date = new Date();
  var seconds = date.getSeconds();
  var minutes = date.getMinutes();
  var hours = date.getHours();
  let now = hours + ":" + minutes + ":" + seconds;
  return now;

}

const authenticate = (socket, validBraceletID) => {
  const { braceletId } = socket.handshake.auth;
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
    heartRate: {x: null, y: null},
    bloodPressure: {x: null,y: null},
    temperature: {x: null,y: null},
  };
  const sendDataInterval = setInterval(() => {
    setData(data)
    socket.emit("realtimeData", data)
  }, 1700)

  // On disconnect
  socket.on("disconnect", () => {
    clearInterval(sendDataInterval)
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
